<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.11.2015
 * Time: 12:52
 */

namespace PM\Bundle\ToolBundle\Twig;

use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Class TimeExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class TimeExtension extends Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter(
                "secondsAsText",
                [
                    $this,
                    "getSecondsAsText",
                ],
                [
                    'deprecated' => true,
                ]
            ),
            new Twig_SimpleFilter(
                "time_seconds_as_text",
                [
                    $this,
                    "getSecondsAsText",
                ]
            ),
            new Twig_SimpleFilter(
                "time_minutes_as_hours",
                [
                    $this,
                    "getMinutesAsHours",
                ]
            ),
            new Twig_SimpleFilter(
                "time_month_number_to_name",
                [
                    $this,
                    "getMonthName",
                ]
            ),
        );
    }

    /**
     * Get Seconds As Text
     *
     * @param float  $seconds
     * @param int    $decimals
     * @param string $decPoint
     * @param string $thousandsSep
     *
     * @return string
     */
    public function getSecondsAsText($seconds, $decimals = 2, $decPoint = ",", $thousandsSep = ".")
    {
        $seconds = floatval($seconds);

        if (60 > $seconds) {
            return sprintf("%ss", number_format($seconds, $decimals, $decPoint, $thousandsSep));
        }

        $minutes = floor($seconds / 60);

        if (3600 > $seconds) {
            return sprintf("%sm %ss", $minutes, number_format($seconds - ($minutes * 60), 0, $decPoint, $thousandsSep));
        }

        $hours = floor($minutes / 60);
        $minutes = $minutes - ($hours * 60);

        return sprintf("%sh %sm %ss", $hours, $minutes, number_format($seconds - ($minutes * 60) - ($hours * 3600), 0, $decPoint, $thousandsSep));
    }

    /**
     * Get Minutes as Minutes (hh:ii)
     *
     * @param int $minutes
     *
     * @return string
     */
    public function getMinutesAsHours($minutes)
    {
        $hours = floor($minutes / 60);
        $minutes = $minutes - ($hours * 60);

        return sprintf('%s:%s', $hours, str_pad($minutes, 2, "0", STR_PAD_LEFT));
    }

    /**
     * Get Month Name
     *
     * @param int $monthIndex
     *
     * @return string
     */
    public function getMonthName($monthIndex)
    {
        $date = new DateTime(sprintf('%d-%s-01', date('Y'), $monthIndex));

        return $date->format('F');
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return "pm_time_extension";
    }

}