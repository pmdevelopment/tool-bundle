<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.05.2017
 * Time: 09:13
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class StringUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class StringUtility
{

    /**
     * Convert under_score to camelCase
     *
     * @param string $string
     *
     * @return string
     */
    public static function underscoreToCamelcase($string)
    {
        return lcfirst(implode('', array_map('ucfirst', explode('_', $string))));
    }

}