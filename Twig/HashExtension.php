<?php

namespace PM\Bundle\ToolBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;

/**
 * Description of CssVersionExtension
 *
 * @author sjoder
 */
class HashExtension extends Twig_Extension
{

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            'hash'  => new Twig_Filter_Method($this, 'hash'),
            'sha1'  => new Twig_Filter_Method($this, 'hashSHA1'),
            'md5'   => new Twig_Filter_Method($this, 'hashMD5'),
            'crc32' => new Twig_Filter_Method($this, 'hashCRC'),
        );
    }

    /**
     * @param string $string
     * @param string $algo
     *
     * @return string
     */
    public function hash($string, $algo)
    {
        if (true === empty($string)) {
            $string = microtime() . uniqid();
        }

        return hash($algo, $string);
    }

    /**
     * Get SHA1
     *
     * @param string $string
     *
     * @return string
     */
    public function hashSHA1($string)
    {
        return $this->hash($string, "sha1");
    }

    /**
     * Get MD5
     *
     * @param string $string
     *
     * @return string
     */
    public function hashMD5($string)
    {
        return $this->hash($string, "md5");
    }

    /**
     * Get CRC32
     *
     * @param string $string
     *
     * @return int
     */
    public function hashCRC($string)
    {
        return crc32($string);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'plugin_pm_hash';
    }

}
