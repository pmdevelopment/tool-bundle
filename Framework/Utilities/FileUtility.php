<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 23.11.2016
 * Time: 10:36
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class FileUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class FileUtility
{

    /**
     * Get Files
     *
     * @param array $folder
     *
     * @return array
     */
    public static function getFiles($folder)
    {
        $files = [];

        if (false === is_dir($folder)) {
            return [];
        }

        $handle = opendir($folder);
        while (false !== ($entry = readdir($handle))) {
            if ('.' !== substr($entry, 0, 1)) {
                $files[] = $entry;
            }
        }

        closedir($handle);

        return $files;
    }


    /**
     * Get User Based Cache dir (e.g. /tmp/www-data/your-folder)
     *
     * @param string $folder
     *
     * @return string
     */
    public static function getUserBasedCachedDir($folder = null)
    {
        $path = [
            sys_get_temp_dir(),
            SystemUtility::getCurrentUser(),
        ];

        if (null !== $folder) {
            $path[] = $folder;
        }

        return implode(DIRECTORY_SEPARATOR, $path);
    }
}