<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 23.03.2017
 * Time: 12:22
 */

namespace PM\Bundle\ToolBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Proxy\Proxy;
use PM\Bundle\ToolBundle\Components\Helper\DoctrineHelper;
use PM\Bundle\ToolBundle\Components\Helper\OpenSSLHelper;
use PM\Bundle\ToolBundle\Framework\Annotations\Encrypted;
use PM\Bundle\ToolBundle\Framework\Annotations\Encryption;
use PM\Bundle\ToolBundle\Framework\Interfaces\HasEncryptedFieldsEntityInterface;
use PM\Bundle\ToolBundle\Framework\Utilities\CryptUtility;
use ReflectionProperty;

/**
 * Class EncryptionSubscriber
 *
 * @package PM\Bundle\ToolBundle\EventListener
 */
class EncryptionSubscriber implements EventSubscriber
{
    const METHOD_ENCRYPT = 'encrypt';
    const METHOD_DECRYPT = 'decrypt';

    /**
     * @var string
     */
    private $secret;

    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * EncryptionSubscriber constructor.
     *
     * @param AnnotationReader $reader
     * @param string           $secret
     */
    public function __construct(AnnotationReader $reader, $secret)
    {
        $this->reader = $reader;
        $this->secret = $secret;
    }

    /**
     * @return AnnotationReader
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Get Subscribed Events
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        $secret = $this->getSecret();

        if (true === empty($secret)) {
            return [];
        }

        return [
            Events::postLoad,
            Events::onFlush,
        ];
    }

    /**
     * PostLoad: Decrypt properties
     *
     * @param LifecycleEventArgs $args
     *
     * @return bool
     */
    public function postLoad($args)
    {
        $entity = $args->getEntity();

        if (false === ($entity instanceof HasEncryptedFieldsEntityInterface)) {
            return false;
        }

        $properties = $this->getEntityEncryptionReflectionProperties($entity);
        foreach ($properties as $property) {
            $fieldValue = $property->getValue($entity);

            if (null === $fieldValue || true === empty($fieldValue)) {
                continue;
            }

            /** @var Encryption $annotation */
            $annotation = DoctrineHelper::getPropertyAnnotation($property, Encryption::class, $this->getReader());

            $property->setValue($entity, OpenSSLHelper::decrypt($fieldValue, $this->getEncryptionKey(), $annotation->cipher));
        }

        /*
         * Deprecated usage
         */
        if (0 === count($properties)) {
            foreach ($this->getEntityEncryptedReflectionProperties($entity) as $property) {
                $fieldValue = $property->getValue($entity);

                if (null === $fieldValue || true === empty($fieldValue)) {
                    continue;
                }

                $property->setValue($entity, CryptUtility::decrypt($fieldValue, $this->getEncryptionKey()));
            }
        }

        return true;
    }


    /**
     * OnFlush: Manipulate change set to encrypted values
     *
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();

        $related = array_merge(
            $unitOfWork->getScheduledEntityUpdates(),
            $unitOfWork->getScheduledEntityInsertions()
        );

        foreach ($related as $entity) {
            if (false === ($entity instanceof HasEncryptedFieldsEntityInterface)) {
                continue;
            }

            $deprecated = false;
            $properties = $this->getEntityEncryptionReflectionProperties($entity);

            if (0 === count($properties)) {
                $deprecated = true;
                $properties = $this->getEntityEncryptedReflectionProperties($entity);
            }

            $changeSet = $unitOfWork->getEntityChangeSet($entity);

            if (0 === count($properties)) {
                continue;
            }

            foreach ($properties as $property) {
                if (false === isset($changeSet[$property->getName()])) {
                    continue;
                }

                if (true === $deprecated) {
                    $encryptedValue = CryptUtility::encrypt($changeSet[$property->getName()][1], $this->getEncryptionKey());
                } else {
                    /** @var Encryption $annotation */
                    $annotation = DoctrineHelper::getPropertyAnnotation($property, Encryption::class, $this->getReader());

                    $encryptedValue = OpenSSLHelper::encrypt($changeSet[$property->getName()][1], $this->getEncryptionKey(), $annotation->cipher);
                }


                if ($encryptedValue === $changeSet[$property->getName()][0]) {
                    unset($changeSet[$property->getName()]);
                } else {
                    $changeSet[$property->getName()][1] = $encryptedValue;
                }
            }

            DoctrineHelper::setUnitOfWorkEntityChangeSet($entity, $changeSet, $unitOfWork);
        }
    }

    /**
     * @param object|mixed $entity
     *
     * @return array|ReflectionProperty[]
     */
    private function getEntityEncryptedReflectionProperties($entity)
    {
        return DoctrineHelper::getPropertiesByAnnotation($this->getClassName($entity), Encrypted::class, $this->getReader());
    }

    /**
     * @param object|mixed $entity
     *
     * @return array|ReflectionProperty[]
     */
    private function getEntityEncryptionReflectionProperties($entity)
    {
        return DoctrineHelper::getPropertiesByAnnotation($this->getClassName($entity), Encryption::class, $this->getReader());
    }

    /**
     * Get Encryption Key
     *
     * @return string
     */
    private function getEncryptionKey()
    {
        return substr(hash('sha256', $this->getSecret()), 1, 32);
    }

    /**
     * Get Class Name
     *
     * @param string $entity
     *
     * @return string
     */
    private function getClassName($entity)
    {
        if (true === ($entity instanceof Proxy)) {
            return get_parent_class($entity);
        }

        return get_class($entity);
    }

}