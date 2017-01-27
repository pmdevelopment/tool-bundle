<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 27.01.2017
 * Time: 11:24
 */

namespace PM\Bundle\ToolBundle\Testing\Config;

/**
 * Class HelperConfig
 *
 * @package PM\Bundle\ToolBundle\Testing\Config
 */
class HelperConfig
{
    /**
     * Get Output Cache Folder
     *
     * @return string
     */
    public static function getOutputCacheFolder()
    {
        return sprintf('%s/../../Resources/public/testing', __DIR__);
    }

}