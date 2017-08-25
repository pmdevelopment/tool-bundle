<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 20.03.2017
 * Time: 11:54
 */

namespace PM\Bundle\ToolBundle\Twig;

use Parsedown;
use Twig_Extension;

/**
 * Class ConvertExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class ConvertExtension extends Twig_Extension
{
    /**
     * Get Filters
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'convert_markdown_to_html',
                [
                    $this,
                    'getHtmlByMarkdown',
                ],
                [
                    'is_safe' => [
                        'html',
                    ],
                ]
            ),
            new \Twig_SimpleFilter(
                'convert_byte_to_string',
                [
                    $this,
                    'getStringByByte',
                ]
            ),
        ];
    }

    /**
     * Get HTML By Markdown
     *
     * @param string $string
     *
     * @return string
     */
    public function getHtmlByMarkdown($string)
    {
        if (false === class_exists('\Parsedown')) {
            throw new \RuntimeException('Missing Parsedown. Try: composer require "erusev/parsedown" "^1.6.1"');
        }

        $parse = new Parsedown();

        return $parse->text($string);
    }

    /**
     * Get Human Readable Bytes
     *
     * @param int    $bytes
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandsSeparator
     *
     * @return string
     */
    public function getStringByByte($bytes, $decimals = 2, $decimalPoint = '.', $thousandsSeparator = ',')
    {
        $size = [
            'B',
            'kB',
            'MB',
            'GB',
            'TB',
            'PB',
            'EB',
            'ZB',
            'YB',
        ];

        $factor = intval(floor((strlen($bytes) - 1) / 3));
        if (false === isset($size[$factor])) {
            return $bytes;
        }

        return sprintf('%s %s', number_format($bytes / pow(1024, $factor), $decimals, $decimalPoint, $thousandsSeparator), $size[$factor]);
    }

}