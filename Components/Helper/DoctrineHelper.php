<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.01.2018
 * Time: 13:45
 */

namespace PM\Bundle\ToolBundle\Components\Helper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use ReflectionClass;


/**
 * Class DoctrineHelper
 *
 * @package PM\Bundle\ToolBundle\Components\Helper
 */
class DoctrineHelper
{
    /**
     * Set Entity Change Set
     *
     * @param object     $entity
     * @param array      $changeSet
     * @param UnitOfWork $unitOfWork
     */
    public static function setUnitOfWorkEntityChangeSet($entity, $changeSet, $unitOfWork)
    {
        $reflectionClass = new ReflectionClass(UnitOfWork::class);

        $reflectionProperty = $reflectionClass->getProperty('entityChangeSets');
        $reflectionProperty->setAccessible(true);

        $changeSetFull = $reflectionProperty->getValue($unitOfWork);
        $changeSetFull[$oid = spl_object_hash($entity)] = $changeSet;

        $reflectionProperty->setValue($unitOfWork, $changeSetFull);
    }

    /**
     * Get all Entity Class Names
     *
     * @param EntityManager $entityManager
     *
     * @return array
     */
    public static function getAllEntityClassNames(EntityManager $entityManager)
    {
        $classes = [];

        foreach ($entityManager->getMetadataFactory()->getAllMetadata() as $meta) {
            $classes[] = $meta->getName();
        }

        return $classes;
    }

    /**
     * Get Properties by Annotation
     *
     * @param string           $entityClass
     * @param string           $annotationClass
     * @param AnnotationReader $reader
     *
     * @return array|\ReflectionProperty[]
     *
     * @throws \ReflectionException
     */
    public static function getPropertiesByAnnotation($entityClass, $annotationClass, AnnotationReader $reader)
    {
        $properties = [];

        $reflectionClass = new ReflectionClass($entityClass);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {

            if (false === self::hasPropertyAnnotation($reflectionProperty, $annotationClass, $reader)) {
                continue;
            }

            $reflectionProperty->setAccessible(true);

            $properties[] = $reflectionProperty;
        }

        return $properties;
    }

    /**
     * Get Property Annotation
     *
     * @param \ReflectionProperty $property
     * @param string              $annotationClass
     * @param AnnotationReader    $reader
     *
     * @return null|object
     */
    public static function getPropertyAnnotation(\ReflectionProperty $property, $annotationClass, AnnotationReader $reader)
    {
        return $reader->getPropertyAnnotation($property, $annotationClass);
    }

    /**
     * Get Event Listener
     *
     * @param string        $className
     * @param EntityManager $manager
     *
     * @return null|object|mixed
     */
    public static function getEventListener($className, EntityManager $manager)
    {
        foreach ($manager->getEventManager()->getListeners() as $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof $className) {
                    return $listener;
                }
            }
        }

        return null;
    }

    /**
     * Has Property Annotation
     *
     * @param \ReflectionProperty $property
     * @param string              $annotationClass
     * @param AnnotationReader    $reader
     *
     * @return bool
     */
    public static function hasPropertyAnnotation(\ReflectionProperty $property, $annotationClass, AnnotationReader $reader)
    {
        if (null === self::getPropertyAnnotation($property, $annotationClass, $reader)) {
            return false;
        }

        return true;
    }
}