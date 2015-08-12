<?php

namespace PM\Bundle\ToolBundle\Twig;

use Twig_Extension;

/**
 * Description of CssVersionExtension
 *
 * @author sjoder
 */
class CssVersionExtension extends Twig_Extension
{

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * CssVersionExtension constructor.
     *
     * @param string $kernelRootDir
     */
    public function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }


    /**
     * Get Filters
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            'cssversion' => new \Twig_Filter_Method($this, 'cssVersion'),
        );
    }

    /**
     * CSS Version
     *
     * @param string $fileName
     *
     * @return string
     */
    public function cssVersion($fileName)
    {
        $filePath = sprintf("%s/../web%s", $this->kernelRootDir, $fileName);

        if (file_exists($filePath)) {
            return "$fileName?v=" . substr(md5_file($filePath), 0, 5);
        }

        return "$fileName?v=" . substr(sha1(microtime()), 0, 6);
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return 'plugin_pm_cssversion';
    }

}
