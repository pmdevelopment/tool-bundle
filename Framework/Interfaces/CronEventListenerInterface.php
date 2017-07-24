<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 24.07.2017
 * Time: 11:16
 */

namespace PM\Bundle\ToolBundle\Framework\Interfaces;


/**
 * interface CronEventListenerInterface
 *
 * @package PM\Bundle\ToolBundle\Framework\Interfaces
 */
interface CronEventListenerInterface
{
    /**
     * Get Repeated Type
     *
     * @return string
     */
    public static function getRepeatedType();

}