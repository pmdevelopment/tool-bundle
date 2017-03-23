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
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use PM\Bundle\ToolBundle\Framework\Annotations\Encrypted;
use PM\Bundle\ToolBundle\Framework\Interfaces\EncryptedEntityInterface;
use PM\Bundle\ToolBundle\Framework\Utilities\CryptUtility;

/**
 * Class EncryptionSubscriber
 *
 * @package PM\Bundle\ToolBundle\EventListener
 */
class EncryptionSubscriber implements EventSubscriber
{
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
            'postLoad',
            'preFlush',
            'preUpdate'
        ];
    }

    /**
     * PostLoad: Decrypt
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad($args)
    {
        $this->process($args->getEntity(), 'decrypt');
    }

    /**
     * PreFlush: Encrypt
     *
     * @param PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        foreach ($args->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            $this->process($entity, 'encrypt');
        }
    }

    /**
     * Pre Update: Encrypt
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        foreach ($args->getEntityManager()->getUnitOfWork()->getScheduledEntityUpdates() as $entity) {
            $this->process($entity, 'encrypt');
        }

    }

    /**
     * Process
     *
     * @param mixed  $entity
     * @param string $method
     *
     * @return bool
     */
    private function process($entity, $method)
    {
        if (false === ($entity instanceof EncryptedEntityInterface)) {
            return false;
        }

        $entityClass = get_class($entity);
        $encryptionKey = substr(hash('sha256', $this->getSecret()), 1, 32);


        $reflectionClass = new \ReflectionClass($entityClass);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            /** @var Encrypted $propertyAnnotation */
            $propertyAnnotation = $this->getReader()->getPropertyAnnotation($reflectionProperty, Encrypted::class);
            if (null === $propertyAnnotation) {
                continue;
            }

            $reflectionProperty->setAccessible(true);

            $fieldValue = $reflectionProperty->getValue($entity);

            if (null === $fieldValue || true === empty($fieldValue)) {
                continue;
            }

            $reflectionProperty->setValue($entity, CryptUtility::$method($fieldValue, $encryptionKey));
        }

        return true;
    }
}