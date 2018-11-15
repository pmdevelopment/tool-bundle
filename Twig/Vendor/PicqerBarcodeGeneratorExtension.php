<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 15.11.2018
 * Time: 13:56
 */

namespace PM\Bundle\ToolBundle\Twig\Vendor;


use Picqer\Barcode\BarcodeGeneratorHTML;

/**
 * Class PicqerBarcodeGeneratorExtension
 *
 * @package PM\Bundle\ToolBundle\Twig\Vendor
 */
class PicqerBarcodeGeneratorExtension extends \Twig_Extension
{
    /**
     * Get Filters
     *
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('picqer_barcode_generator_html_128', [
                $this,
                'getHtml128',
            ]),
        ];
    }

    /**
     * Get HTML as CODE-128
     *
     * @param string $string
     *
     * @return string
     */
    public function getHtml128($string, $pixelPerByte = 2, $height = 30)
    {
        $generator = new BarcodeGeneratorHTML();

        return $generator->getBarcode($string, BarcodeGeneratorHTML::TYPE_CODE_128, $pixelPerByte, $height);
    }

}