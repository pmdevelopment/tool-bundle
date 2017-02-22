<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 14.05.15
 * Time: 14:23
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class SoftdeleteUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class SoftdeleteUtility
{

    /**
     * Is Deleted?
     *
     * @param mixed $object
     *
     * @return bool
     */
    public static function isDeleted($object)
    {
        if (true === method_exists($object, 'getDeleted') && true === $object->getDeleted()) {
            return true;
        }

        if (true === method_exists($object, 'isDeleted') && true === $object->isDeleted()) {
            return true;
        }

        return false;
    }

    /**
     * Get Not deleted
     *
     * @param Collection|mixed[] $objects
     *
     * @return Collection|mixed[]|null
     */
    public static function getNotDeleted($objects)
    {
        if (null === $objects) {
            return null;
        }

        if (!$objects instanceof Collection) {
            $objects = new ArrayCollection($objects);
        }

        foreach ($objects as $object) {
            $functionDeletedOld = [
                $object,
                "getDeleted"
            ];

            $functionDeletedNew = [
                $object,
                "isDeleted"
            ];

            if (true === is_callable($functionDeletedOld) && true === $object->getDeleted()) {
                $objects->removeElement($object);
            } elseif (true === is_callable($functionDeletedNew) && true === $object->isDeleted()) {
                $objects->removeElement($object);
            }
        }

        return $objects;
    }

    /**
     * Soft delete given object and relations
     *
     * @param mixed $object
     */
    public static function delete($object)
    {
        if (null === $object) {
            throw new \LogicException("No object");
        }

        $setterDelete = [
            $object,
            "setDeleted"
        ];

        if (false === is_callable($setterDelete)) {
            throw new \LogicException(sprintf("%s is not softdeletable", get_class($object)));
        }

        $object->setDeleted(true);

        $oReflectionClass = new \ReflectionClass($object);
        foreach ($oReflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $propertyValue = $property->getValue($object);

            if ($propertyValue instanceof Collection || true === is_array($propertyValue)) {
                foreach ($propertyValue as $propertyValueRelation) {
                    $setterDelete = [
                        $propertyValueRelation,
                        "setDeleted"
                    ];

                    if (true === is_callable($setterDelete)) {
                        $propertyValueRelation->setDeleted(true);
                    }
                }
            }
        }
    }
}