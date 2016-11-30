<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.11.2016
 * Time: 14:26
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class SystemUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class SystemUtility
{

    /**
     * Get Current User
     *
     * @return string
     */
    public static function getCurrentUser()
    {
        $processUser = posix_getpwuid(posix_geteuid());

        if (true === isset($processUser['name'])) {
            return $processUser['name'];
        }

        return get_current_user();
    }

}