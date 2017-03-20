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


}