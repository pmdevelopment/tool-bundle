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
     * Get User Based Cache dir (e.g. /tmp/symfony2/yourfolder_uniquehash/www-data)
     *
     * @param string $folder
     *
     * @return string
     */
    public static function getUserBasedCachedDir($folder = null)
    {
        $path = [
            sys_get_temp_dir(),
            "symfony2",
        ];

        if (false === file_exists(implode(DIRECTORY_SEPARATOR, $path))) {
            mkdir($path, 0777, true);
        }

        if (null !== $folder) {
            $path[] = sprintf("%s_%s", $folder, self::getCurrentSetupHash());
        }

        $path[] = SystemUtility::getCurrentUser();

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Get unique Setup hash based on __DIR__
     *
     * @param int $precision
     *
     * @return string
     */
    public static function getCurrentSetupHash($precision = 8)
    {
        return substr(sha1(__DIR__), 0, $precision);
    }
}