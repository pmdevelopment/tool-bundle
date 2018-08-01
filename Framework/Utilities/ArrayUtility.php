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

    /**
     * Get Multidimensional-Array as string
     *
     * @param mixed  $input
     * @param string $glue
     *
     * @return string
     */
    public static function getFlat($input, $glue = ',')
    {
        if (false === is_array($input)) {
            return $input;
        }

        $result = [];

        foreach ($input as $inputKey => $inputText) {
            if (true === is_numeric($inputKey)) {
                $result[] = self::getFlat($inputText, $glue);
            }

            $result[] = sprintf('%s: %s', $inputKey, self::getFlat($inputText, $glue));
        }

        return implode($glue, $result);
    }

    /**
     * Get first result
     *
     * @param array      $array
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public static function getFirst($array, $default = null)
    {
        if (0 === count($array)) {
            return $default;
        }

        return reset($array);
    }

    /**
     * Remove Items Not Numeric
     *
     * @param array $array
     *
     * @return array
     */
    public static function removeItemsNotNumeric($array)
    {
        foreach ($array as $index => $value) {
            if (false === is_numeric($value)) {
                unset($array[$index]);
            }
        }

        return $array;
    }

}