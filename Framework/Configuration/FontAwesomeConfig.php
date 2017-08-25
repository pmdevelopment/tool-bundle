<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.08.15
 * Time: 11:37
 */

namespace PM\Bundle\ToolBundle\Framework\Configuration;

/**
 * Class FontAwesomeConfig
 *
 * @package PM\Bundle\ToolBundle\Framework\Configuration
 */
class FontAwesomeConfig
{

    /**
     * Get Versions available for this config
     *
     * @return array
     */
    public static function getVersions()
    {
        return [
            '4.4.0',
        ];

        /* Todo: Add missing versions */
    }

    /**
     * Get Icon Classes
     *
     * @param string $version
     *
     * @return array
     */
    public static function getClasses($version)
    {
        if (false === in_array($version, self::getVersions())) {
            throw new \LogicException(sprintf("Version is not supported. Available: %s", implode(", ", self::getVersions())));
        }

        $version = explode(".", $version);
        $subVersion = $version[1];

        $icons = array();

        /**
         * 4.4.0 Icons
         */
        if (4 <= $subVersion) {
            $icons = FontAwesome\Version440::getIconsAll();
        }

        return $icons;
    }

}