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
        return [
            new \Twig_SimpleFilter("cssversion", [
                $this,
                "cssVersion"
            ])
        ];
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

        if (true === file_exists($filePath)) {
            return sprintf("%s?v=%s", $fileName, substr(md5_file($filePath), 0, 5));
        }

        return sprintf("%s?v=%s", $fileName, substr(sha1(microtime()), 0, 6));
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return 'pm_cssversion_extension';
    }

}
