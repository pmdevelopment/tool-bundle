<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 26.03.2018
 * Time: 09:56
 */

namespace PM\Bundle\ToolBundle\Components\Helper;

use Parsedown;

/**
 * Class ParsedownHelper
 *
 * @package PM\Bundle\ToolBundle\Components\Helper
 */
class ParsedownHelper
{
    /**
     * Parse text
     *
     * @param string $string
     * @param bool   $breaksEnabled
     *
     * @return string
     */
    public static function text($string, $breaksEnabled = true)
    {
        $parse = new Parsedown();
        $parse->setBreaksEnabled($breaksEnabled);

        return $parse->text($string);
    }
}