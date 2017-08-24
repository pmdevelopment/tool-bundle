<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 26.07.2016
 * Time: 15:18
 */

namespace PM\Bundle\ToolBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Event\PreFlushEventArgs;
use PM\Bundle\ToolBundle\Framework\Annotations\Optional;
use PM\Bundle\ToolBundle\Framework\Interfaces\HasOptionalFieldsEntityInterface;

/**
 * Class OptionalAnnotationEventListener
 *
 * @package PM\Bundle\ToolBundle\EventListener
 */
class OptionalAnnotationEventListener
{

    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * UniqueIdAnnotationEventListener constructor.
     *
     * @param AnnotationReader $reader
     */
    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @return AnnotationReader
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * Pre FLush
     *
     * @param PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        foreach ($args->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            $this->process($entity);
        }
    }

    /**
     * Process
     *
     * @param mixed $entity
     *
     * @return bool
     */
    private function process($entity)
    {
        $entityClass = get_class($entity);

        if (false === ($entity instanceof HasOptionalFieldsEntityInterface)) {
            return false;
        }

        $reflectionClass = new \ReflectionClass($entityClass);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            /** @var Optional $propertyAnnotation */
            $propertyAnnotation = $this->getReader()->getPropertyAnnotation($reflectionProperty, Optional::class);
            if (null === $propertyAnnotation) {
                continue;
            }

            $reflectionProperty->setAccessible(true);

            if (null === $reflectionProperty->getValue($entity)) {
                $reflectionProperty->setValue($entity, $propertyAnnotation->default);
            }
        }

        return true;
    }
}