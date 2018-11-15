<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 15.11.2018
 * Time: 13:41
 */

namespace PM\Bundle\ToolBundle\Twig\Vendor;


use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

/**
 * Class EndroidQrCodeExtension
 *
 * @package PM\Bundle\ToolBundle\Twig\Vendor
 */
class EndroidQrCodeExtension extends \Twig_Extension
{
    /**
     * Get Filters
     *
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('endroid_qr_code_png', [
                $this,
                'getPng',
            ]),
        ];
    }

    /**
     * Get PNG
     *
     * @param string $string
     * @param int    $size
     *
     * @return string
     */
    public function getPng($string, $size = 300)
    {
        $qrCode = new QrCode($string);
        $qrCode->setSize($size);
        $qrCode->setWriterByName('png');


        $this->applyDefaultSettings($qrCode);

        return sprintf('data:image/png;base64,%s', base64_encode($qrCode->writeString()));
    }

    /**
     * Apply Default Settings
     *
     * @param QrCode $qrCode
     */
    private function applyDefaultSettings(QrCode $qrCode)
    {
        $qrCode->setMargin(0);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
    }
}