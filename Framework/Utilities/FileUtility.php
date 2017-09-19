<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 23.11.2016
 * Time: 10:36
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

use PM\Bundle\ToolBundle\Components\Helper\FileUtilityHelper;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FileUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class FileUtility
{
    const EXCLUDE_FOLDERS = true;
    const INCLUDE_FOLDERS = false;

    /**
     * Get FontAwesome Icon by File Extension
     *
     * @param string $fileExtension
     * @param string $default
     *
     * @return null|string
     */
    public static function getFontAwesomeIcon($fileExtension, $default = 'file-o')
    {
        $result = FileUtilityHelper::getIconByExtension($fileExtension);
        if (null === $result) {
            return $default;
        }

        return $result;
    }

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
     * Get Response From DataUri
     *
     * @param string $dataUri
     *
     * @return Response
     */
    public static function getResponseFromDataUri($dataUri)
    {
        $mimeType = substr($dataUri, 5, strpos($dataUri, ';') - 5);
        $content = base64_decode(substr($dataUri, strpos($dataUri, ',') + 1));

        return new Response($content, Response::HTTP_OK, [
            'Content-Type' => $mimeType,
        ]);
    }

    /**
     * Get Files and folders
     *
     * @param array $folder
     * @param bool  $excludeFolders
     *
     * @return array
     */
    public static function getFiles($folder, $excludeFolders = self::INCLUDE_FOLDERS)
    {
        $files = [];

        if (false === is_dir($folder)) {
            return [];
        }

        $handle = opendir($folder);
        while (false !== ($entry = readdir($handle))) {
            if (true === in_array($entry, self::getDirSymbols())) {
                continue;
            }

            if (self::EXCLUDE_FOLDERS === $excludeFolders && true === is_dir(sprintf('%s/%s', $folder, $entry))) {
                continue;
            }

            $files[] = $entry;
        }

        closedir($handle);

        return $files;
    }

    /**
     * Get Folders
     *
     * @param string $root
     *
     * @return array
     */
    public static function getFolders($root)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
        );

        $paths = [
            $root,
        ];

        foreach ($iterator as $path => $dir) {
            if (true === $dir->isDir()) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * Get Folders and Files recursive
     *
     * @param string $root
     *
     * @return array
     */
    public static function getFolderAsArray($root)
    {
        if (false === is_dir($root)) {
            return null;
        }

        $result = [];
        $folderContent = scandir($root);

        $dirChecks = [
            true,
            false,
        ];

        foreach ($dirChecks as $dirCheck) {
            foreach ($folderContent as $entry) {
                if (true === in_array($entry, self::getDirSymbols())) {
                    continue;
                }

                $path = sprintf('%s/%s', $root, $entry);
                if ($dirCheck === is_dir($path)) {
                    $result[$entry] = self::getFolderAsArray($path);
                }
            }
        }

        return $result;
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
            'symfony2',
        ];

        if (null !== $folder) {
            $path[] = sprintf('%s_%s', $folder, self::getCurrentSetupHash());
        }

        $path[] = SystemUtility::getCurrentUser();

        $folder = implode(DIRECTORY_SEPARATOR, $path);

        return $folder;
    }

    /**
     * Get Dir Symbols (. and ..)
     *
     * @return array
     */
    public static function getDirSymbols()
    {
        return [
            '.',
            '..',
        ];
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

    /**
     * RMDIR recursive
     *
     * @param string $path
     *
     * @return bool
     */
    public static function rmdir($path)
    {
        if (true === is_dir($path)) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if (false === in_array($file->getBasename(), self::getDirSymbols())) {
                    if (true === $file->isDir()) {
                        rmdir($file->getPathName());
                    } else {
                        if ((true === $file->isFile()) || (true === $file->isLink())) {
                            unlink($file->getPathname());
                        }
                    }
                }
            }

            return rmdir($path);
        } else {
            if ((is_file($path) === true) || (is_link($path) === true)) {
                return unlink($path);
            }
        }

        return false;
    }

    /**
     * Copy recursive
     *
     * @param string $source
     * @param string $target
     *
     * @return bool
     */
    public static function copy($source, $target)
    {
        if (false === is_dir($source)) {
            return copy($source, $target);
        }

        self::mkdir($target);

        $dirSource = dir($source);

        while (false !== ($fileEntry = $dirSource->read())) {
            if (true === in_array($fileEntry, self::getDirSymbols())) {
                continue;
            }

            if (false === self::copy(sprintf('%s/%s', $source, $fileEntry), sprintf('%s/%s', $target, $fileEntry))) {
                return false;
            }

        }

        $dirSource->close();

        return true;
    }
}