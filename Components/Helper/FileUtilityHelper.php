<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 25.08.2017
 * Time: 12:06
 */

namespace PM\Bundle\ToolBundle\Components\Helper;


/**
 * Class FileUtilityHelper
 *
 * @package PM\Bundle\ToolBundle\Components\Helper
 */
class FileUtilityHelper
{
    /**
     * Get Extensions Archive
     *
     * @return array
     */
    public static function extensionsArchive()
    {
        return [
            'zip',
            'rar',
            '7zip',
        ];
    }

    /**
     * Get Extensions Image
     *
     * @return array
     */
    public static function extensionsImage()
    {
        return [
            'png',
            'jpeg',
            'jpg',
            'gif',
            'bmp',
            'raw',
            'eps',
        ];
    }

    /**
     * Extensions Code
     *
     * @return array
     */
    public static function extensionsCode()
    {
        return [
            'php',
            'php5',
            'html',
            'htm',
            'css',
        ];
    }

    /**
     * Extensions Video
     *
     * @return array
     */
    public static function extensionsVideo()
    {
        return [
            'mkv',
            'mp4',
            'avi',
        ];
    }

    /**
     * Extensions Audio
     *
     * @return array
     */
    public static function extensionsAudio()
    {
        return [
            'mp3',
            'wav',
        ];
    }

    /**
     * Extensions Excel
     *
     * @return array
     */
    public static function extensionsExcel()
    {
        return [
            'xls',
            'xlsx',
            'xlt',
            'csv',
            'ods',
        ];
    }

    /**
     * Extensions PowerPoint
     *
     * @return array
     */
    public static function extensionsPowerPoint()
    {
        return [
            'ppt',
            'pptx',
            'sxi',
            'odp',
        ];
    }

    /**
     * Extensions Word
     *
     * @return array
     */
    public static function extensionsWord()
    {
        return [
            'doc',
            'docx',
            'odt',
            'txt',
        ];
    }

    /**
     * Get FontAwesome Icon By Extension
     *
     * @param string $extension
     *
     * @return null|string
     */
    public static function getIconByExtension($extension)
    {
        if ('pdf' === $extension) {
            return 'file-pdf-o';
        }

        if (true === in_array($extension, self::extensionsArchive())) {
            return 'file-archive-o';
        }

        if (true === in_array($extension, self::extensionsImage())) {
            return 'file-image-o';
        }

        if (true === in_array($extension, self::extensionsCode())) {
            return 'file-code-o';
        }

        if (true === in_array($extension, self::extensionsVideo())) {
            return 'file-video-o';
        }

        if (true === in_array($extension, self::extensionsAudio())) {
            return 'file-audio-o';
        }

        if (true === in_array($extension, self::extensionsExcel())) {
            return 'file-excel-o';
        }

        if (true === in_array($extension, self::extensionsPowerPoint())) {
            return 'file-powerpoint-o';
        }

        if (true === in_array($extension, self::extensionsWord())) {
            return 'file-word-o';
        }

        return null;
    }
}