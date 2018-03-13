<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 26.07.2016
 * Time: 15:18
 */

namespace PM\Bundle\ToolBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PM\Bundle\ToolBundle\Framework\Annotations\UniqueId;
use PM\Bundle\ToolBundle\Framework\Interfaces\HasUniqueIdEntityInterface;

/**
 * Class UniqueIdAnnotationEventListener
 *
 * @package PM\Bundle\ToolBundle\EventListener
 */
class UniqueIdAnnotationEventListener
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
     * @param LifecycleEventArgs $args
     *
     * @return bool
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityClass = get_class($entity);

        if (false === ($entity instanceof HasUniqueIdEntityInterface)) {
            return false;
        }

        $reflectionClass = new \ReflectionClass($entityClass);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            /** @var UniqueId $propertyAnnotation */
            $propertyAnnotation = $this->getReader()->getPropertyAnnotation($reflectionProperty, UniqueId::class);
            if (null === $propertyAnnotation) {
                continue;
            }

            $repository = $args->getEntityManager()->getRepository($entityClass);

            $reflectionProperty->setAccessible(true);

            while (null === $reflectionProperty->getValue($entity)) {
                $randomId = $this->getRandomId($entityClass, $propertyAnnotation);
                if (null !== $propertyAnnotation->prefix) {
                    $randomId = sprintf("%s%s", $propertyAnnotation->prefix, $randomId);
                }

                if (null === $repository->findOneBy([$reflectionProperty->getName() => $randomId])) {
                    $reflectionProperty->setValue($entity, $randomId);
                }
            }
        }

        return true;
    }

    /**
     * Get Random Id
     *
     * @param string   $entityClass
     * @param UniqueId $annotation
     *
     * @return string
     */
    private function getRandomId($entityClass, $annotation)
    {
        if ('int' === $annotation->type || 'integer' === $annotation->type) {
            return mt_rand(pow(10, $annotation->length - 1), pow(10, $annotation->length) - 1);
        }

        return substr(hash('sha256', sprintf('%s - %s - %s', microtime(), $entityClass, uniqid())), 0, $annotation->length);
    }
}