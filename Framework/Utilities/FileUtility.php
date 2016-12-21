<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 23.11.2016
 * Time: 10:36
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class FileUtility
{
    /**
     * Get Base64 Encoded Data Uri
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public static function getDataUriFromUploadedFile(UploadedFile $file)
    {
        return sprintf('data:%s;base64,%s', $file->getMimeType(), base64_encode(file_get_contents($file->getPathname())));
    }

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

        if (null !== $folder) {
            $path[] = sprintf("%s_%s", $folder, self::getCurrentSetupHash());
        }

        $path[] = SystemUtility::getCurrentUser();

        $folder = implode(DIRECTORY_SEPARATOR, $path);

        return $folder;
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

    /**
     * MKDIR recursive (sets chmod recursive as well)
     *
     * @param string $path
     * @param int    $chmod
     *
     * @return bool
     */
    public static function mkdir($path, $chmod = 0777)
    {
        if (true === file_exists($path)) {
            return true;
        }

        $path = explode(DIRECTORY_SEPARATOR, $path);
        $folderCount = count($path);


        for ($folderIndex = 1; $folderIndex <= $folderCount; $folderIndex++) {
            $subFolder = implode(DIRECTORY_SEPARATOR, array_slice($path, 0, $folderIndex));

            if (true === empty($subFolder)) {
                continue;
            }

            if (true === file_exists($subFolder)) {
                continue;
            }

            if (false === mkdir($subFolder, $chmod, true)) {
                return false;
            }

            chmod($subFolder, 0777);
        }

        return true;
    }
}