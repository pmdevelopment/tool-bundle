<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.12.2017
 * Time: 12:45
 */

namespace PM\Bundle\ToolBundle\Components\Helper;


/**
 * Class ObjectHelper
 *
 * @package PM\Bundle\ToolBundle\Components\Helper
 */
class ObjectHelper
{
    /**
     * Get Contant Values By Prefix
     *
     * @param string $className
     * @param string $prefix
     *
     * @return array
     */
    public static function getConstantValuesByPrefix($className, $prefix)
    {
        $prefixLength = strlen($prefix);
        $result = [];

        $reflection = new \ReflectionClass($className);
        foreach ($reflection->getConstants() as $constKey => $constValue) {
            if ($prefix === substr($constKey, 0, $prefixLength)) {
                $result[] = $constValue;
            }
        }

        return $result;
    }
}