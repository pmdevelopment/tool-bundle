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
use PM\Bundle\ToolBundle\Framework\Annotations\Encrypted;
use PM\Bundle\ToolBundle\Framework\Interfaces\HasEncryptedFieldsEntityInterface;
use PM\Bundle\ToolBundle\Framework\Utilities\CryptUtility;
use ReflectionClass;
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

        foreach ($this->getEntityEncryptedReflectionProperties($entity) as $property) {
            $fieldValue = $property->getValue($entity);

            if (null === $fieldValue || true === empty($fieldValue)) {
                continue;
            }

            $property->setValue($entity, CryptUtility::decrypt($fieldValue, $this->getEncryptionKey()));
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

            $properties = $this->getEntityEncryptedReflectionProperties($entity);
            $changeSet = $unitOfWork->getEntityChangeSet($entity);

            if (0 === count($properties)) {
                continue;
            }

            foreach ($properties as $property) {
                if (false === isset($changeSet[$property->getName()])) {
                    continue;
                }

                $encryptedValue = CryptUtility::encrypt($changeSet[$property->getName()][1], $this->getEncryptionKey());

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
        $properties = [];

        if (true === ($entity instanceof Proxy)) {
            $entityClass = get_parent_class($entity);
        } else {
            $entityClass = get_class($entity);
        }

        $reflectionClass = new ReflectionClass($entityClass);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {

            /** @var Encrypted $propertyAnnotation */
            $propertyAnnotation = $this->getReader()->getPropertyAnnotation($reflectionProperty, Encrypted::class);
            if (null === $propertyAnnotation) {
                continue;
            }

            $reflectionProperty->setAccessible(true);

            $properties[] = $reflectionProperty;
        }

        return $properties;
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


}