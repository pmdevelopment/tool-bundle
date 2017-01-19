<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.01.2017
 * Time: 15:01
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class ArrayUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class ArrayUtility
{
    /**
     * Does given array has array with elements for key?
     *
     * @param array  $array
     * @param string $key
     *
     * @return bool
     */
    public static function isKeyWithElements($array, $key)
    {
        if (false === is_array($array) || false === isset($array[$key])) {
            return false;
        }

        if (false === is_array($array[$key])) {
            return false;
        }

        if (0 === count($array[$key])) {
            return false;
        }

        return true;
    }

}