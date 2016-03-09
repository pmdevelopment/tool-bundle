<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 24.10.2015
 * Time: 12:23
 */

namespace PM\Bundle\ToolBundle\Framework;


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
     * Init
     */
    protected function initializeContainer()
    {
        parent::initializeContainer();
        if (PHP_SAPI === 'cli') {
            $request = new Request();
            $request->create('/');
            $this->getContainer()->enterScope('request');
            $this->getContainer()->set('request', $request, 'request');
        }
    }

    /**
     * @inheritDoc
     */
    public function getCacheDir()
    {
        if ('dev' !== $this->getEnvironment()) {
            return sprintf("%s/../var/cache/%s", __DIR__, $this->getEnvironment());
        }

        return implode(DIRECTORY_SEPARATOR, array(
            $this->getBaseTmpDir(),
            "cache"
        ));
    }

    /**
     * @inheritDoc
     */
    public function getLogDir()
    {
        if ('dev' !== $this->getEnvironment()) {
            return sprintf("%s/../var/logs", __DIR__);
        }

        return implode(DIRECTORY_SEPARATOR, array(
            $this->getBaseTmpDir(),
            "logs"
        ));
    }

    /**
     * Get Base Tmp Dir
     *
     * @return string
     */
    private function getBaseTmpDir()
    {
        $tempDir = array(
            sys_get_temp_dir(),
            "sf2"
        );

        $folders = explode(DIRECTORY_SEPARATOR, $this->getRootDir());
        $foldersCount = count($folders);

        /**
         * Base Path
         */
        if (true === isset($folders[$foldersCount - 3])) {
            $tempDir[] = $folders[$foldersCount - 3];
        }

        if (true === isset($folders[$foldersCount - 2])) {
            $tempDir[] = $folders[$foldersCount - 2];
        }
        /**
         * Environment
         */
        $tempDir[] = $this->getEnvironment();

        $tempDirPath = implode(DIRECTORY_SEPARATOR, $tempDir);

        if (false === file_exists($tempDirPath)) {
            mkdir($tempDirPath, 0777, true);
        }

        return $tempDirPath;
    }
}