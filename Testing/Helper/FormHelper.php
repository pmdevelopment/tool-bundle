<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 13.12.2016
 * Time: 15:52
 */

namespace PM\Bundle\ToolBundle\Testing\Helper;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FormHelper
 *
 * @package PM\Bundle\ToolBundle\Testing\Helper
 */
class FormHelper
{
    /**
     * Get CSRF Token
     *
     * @param Crawler $crawler
     * @param string  $formName
     *
     * @return string
     */
    public static function getCsrfToken(Crawler $crawler, $formName)
    {
        return $crawler->filter('form')->form()->get(sprintf('%s[_token]', $formName))->getValue();
    }

    /**
     * Create File Image
     *
     * @return UploadedFile
     */
    public static function createFileImage()
    {
        return new UploadedFile(
            sprintf('%s/../../Resources/testing/file_photo.jpg', __DIR__),
            'file_photo.jpg',
            'image/jpep',
            97298
        );
    }
}