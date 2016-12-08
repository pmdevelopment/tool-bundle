<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.08.15
 * Time: 11:38
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

use Doctrine\Common\Collections\Collection;

/**
 * Class CollectionUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class CollectionUtility
{
    /**
     * Is Valid Collection
     *
     * @param mixed $collection
     *
     * @return bool
     */
    public static function isValid($collection)
    {
        if (false === is_array($collection) && false === ($collection instanceof Collection)) {
            return false;
        }

        return true;
    }

    /**
     * Get Property
     *
     * @param string                 $property
     * @param array|mixed|Collection $collection
     *
     * @return array
     */
    public static function get($property, $collection)
    {
        if (false === self::isValid($collection)) {
            return array();
        }

        $getter = sprintf("get%s", ucfirst($property));
        $result = [];
        foreach ($collection as $entity) {
            $function = array(
                $entity,
                $getter
            );

            if (false === is_callable($function)) {
                throw new \LogicException("Missing getter");
            }

            $result[] = $entity->$getter();
        }

        return $result;
    }

    /**
     * Get Ids From Collection
     *
     * @param array|mixed|Collection $collection
     *
     * @return array
     */
    public static function getIds($collection)
    {
        return self::get("id", $collection);
    }

    /**
     * Get Names From Collection
     *
     * @param array|mixed|Collection $collection
     *
     * @return array
     */
    public static function getNames($collection)
    {
        return self::get("name", $collection);
    }

    /**
     * Find By Id
     *
     * @param Collection|array $collection
     * @param int              $id
     *
     * @return null|object
     */
    public static function find($collection, $id)
    {
        $id = intval($id);
        foreach ($collection as $entity) {
            if ($id === $entity->getId()) {
                return $entity;
            }
        }

        return null;
    }
}