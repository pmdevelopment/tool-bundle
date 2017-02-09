<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.02.2017
 * Time: 15:36
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;


/**
 * Class NumberUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class NumberUtility
{
    /**
     * @param float $base
     * @param float $divider
     * @param int   $scale
     *
     * @param bool  $string
     *
     * @return float|string
     */
    public static function getPercentage($base, $divider, $scale = 2, $string = false)
    {
        if (0 === $divider) {
            return '-';
        }

        $value = round(($base / $divider) * 100, $scale);
        if (false === $string) {
            return $value;
        }

        return sprintf('%s%%', number_format($value, $scale));
    }

}