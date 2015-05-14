<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 14.05.15
 * Time: 14:25
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class SepaUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class SepaUtility
{
    /**
     * Slug
     *
     * Bestimmte Zeichen dürfen im SEPA nicht vorkommen
     * und werden so durch verträgliche alternativen
     * ersetzt.
     *
     * @param string $name
     *
     * @return mixed
     */
    public static function slug($name)
    {
        return str_replace("&", "+", $name);
    }
}