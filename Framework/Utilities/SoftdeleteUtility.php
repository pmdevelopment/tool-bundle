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
            $functionDeletedOld = array(
                $object,
                "getDeleted"
            );

            $functionDeletedNew = array(
                $object,
                "isDeleted"
            );

            if (true === is_callable($functionDeletedOld) && true === $object->getDeleted()) {
                $objects->removeElement($object);
            } elseif (true === is_callable($functionDeletedNew) && true === $object->isDeleted()) {
                $objects->removeElement($object);
            }
        }

        return $objects;
    }
}