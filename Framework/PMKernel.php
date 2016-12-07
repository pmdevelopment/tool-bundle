<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 24.10.2015
 * Time: 12:23
 */

namespace PM\Bundle\ToolBundle\Framework;


use PM\Bundle\ToolBundle\Framework\Utilities\FileUtility;
use Symfony\Component\HttpFoundation\Request;
use LogicException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class PMKernel
 *
 *  - Fixes the "scope" error for route voter.
 *  - Get different cache and log dir to improve
 *    speed when using a SMB share.
 *
 * @package PM\Bundle\ToolBundle\Framework
 */
class PMKernel extends Kernel
{
    /**
     * @inheritDoc
     */
    public function registerBundles()
    {
        throw new LogicException("No bundles registered");
    }

    /**
     * @inheritDoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        throw new LogicException("Missing container configuration");
    }

    /**
     * @inheritDoc
     */
    public function getCacheDir()
    {
        if ('dev' !== $this->getEnvironment()) {
            return sprintf("%s/../var/cache/%s", $this->getRootDir(), $this->getEnvironment());
        }

        return implode(DIRECTORY_SEPARATOR, [
            $this->getBaseTmpDir(),
            "cache",
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getLogDir()
    {
        if ('dev' !== $this->getEnvironment()) {
            return sprintf("%s/../var/logs", $this->getRootDir());
        }

        return implode(DIRECTORY_SEPARATOR, [
            $this->getBaseTmpDir(),
        ]);
    }

    /**
     * Get Base Tmp Dir
     *
     * @return string
     */
    private function getBaseTmpDir()
    {
        $folders = explode(DIRECTORY_SEPARATOR, $this->getRootDir());
        $foldersCount = count($folders);

        /**
         * Base Path
         */
        $projectDir = "";
        if (true === isset($folders[$foldersCount - 2])) {
            $projectDir = $folders[$foldersCount - 2];
        }

        $tempDirPath = FileUtility::getUserBasedCachedDir(sprintf('%s-%s', $projectDir, $this->getEnvironment()));

        if (false === file_exists($tempDirPath)) {
            mkdir($tempDirPath, 0777, true);
        }

        return $tempDirPath;
    }
}
