<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 03.07.2018
 * Time: 11:59
 */

namespace PM\Bundle\ToolBundle\Components\Helper;

use PM\Bundle\ToolBundle\Constants\Environment;
use PM\Bundle\ToolBundle\Framework\Utilities\FileUtility;

/**
 * Class SymfonyHelper
 *
 * @package PM\Bundle\ToolBundle\Components\Helper
 */
class SymfonyHelper
{
    /**
     * Get Cache dir
     *
     * @param string $rootDir
     * @param string $environment
     *
     * @return string
     */
    public static function getCacheTmpDir($rootDir, $environment = Environment::DEV)
    {
        return implode(
            DIRECTORY_SEPARATOR,
            [
                self::getBaseTmpDir($rootDir, $environment),
                'cache',
            ]
        );
    }

    /**
     * Get Log dir
     *
     * @param string $rootDir
     * @param string $environment
     *
     * @return string
     */
    public static function getLogTmpDir($rootDir, $environment = Environment::DEV)
    {
        return self::getBaseTmpDir($rootDir, $environment);
    }

    /**
     * Get Base Tmp Dir
     *
     * @param string $rootDir
     * @param string $environment
     *
     * @return string
     */
    public static function getBaseTmpDir($rootDir, $environment)
    {
        $folders = explode(DIRECTORY_SEPARATOR, $rootDir);
        $foldersCount = count($folders);

        $projectDir = '';
        if (true === isset($folders[$foldersCount - 2])) {
            $projectDir = $folders[$foldersCount - 2];
        }

        $tempDirPath = FileUtility::getUserBasedCachedDir(sprintf('%s-%s', $projectDir, $environment));
        FileUtility::mkdir($tempDirPath);

        return $tempDirPath;
    }
}