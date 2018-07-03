<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 24.10.2015
 * Time: 12:23
 */

namespace PM\Bundle\ToolBundle\Framework;


use LogicException;
use PM\Bundle\ToolBundle\Framework\Utilities\FileUtility;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class PMKernel
 *
 *  - Get different cache and log dir to improve
 *    speed when using a SMB share.
 *
 * @package PM\Bundle\ToolBundle\Framework
 *
 * @deprecated Use SymfonyHelper
 */
class PMKernel extends Kernel
{
    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var string
     */
    private $logDir;

    /**
     * @inheritDoc
     */
    public function registerBundles()
    {
        throw new LogicException('No bundles registered');
    }

    /**
     * @inheritDoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        throw new LogicException('Missing container configuration');
    }

    /**
     * @inheritDoc
     */
    public function getCacheDir()
    {
        if (null !== $this->cacheDir) {
            return $this->cacheDir;
        }

        if ('dev' !== $this->getEnvironment()) {
            return parent::getCacheDir();
        }

        $this->cacheDir = implode(DIRECTORY_SEPARATOR, [
            $this->getBaseTmpDir(),
            'cache',
        ]);

        return $this->cacheDir;
    }

    /**
     * @inheritDoc
     */
    public function getLogDir()
    {
        if (null !== $this->logDir) {
            return $this->logDir;
        }

        if ('dev' !== $this->getEnvironment()) {
            return parent::getLogDir();
        }

        $this->logDir = $this->getBaseTmpDir();

        return $this->logDir;
    }

    /**
     * Get Base Tmp Dir
     *
     * @return string
     */
    public function getBaseTmpDir()
    {
        $folders = explode(DIRECTORY_SEPARATOR, $this->getRootDir());
        $foldersCount = count($folders);

        /**
         * Base Path
         */
        $projectDir = '';
        if (true === isset($folders[$foldersCount - 2])) {
            $projectDir = $folders[$foldersCount - 2];
        }

        $tempDirPath = FileUtility::getUserBasedCachedDir(sprintf('%s-%s', $projectDir, $this->getEnvironment()));
        FileUtility::mkdir($tempDirPath);

        return $tempDirPath;
    }
}
