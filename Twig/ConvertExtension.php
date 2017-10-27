<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 20.03.2017
 * Time: 11:54
 */

namespace PM\Bundle\ToolBundle\Twig;

use DateTime;
use Parsedown;
use PM\Bundle\ToolBundle\Framework\Traits\Services\HasTranslatorServiceTrait;
use Twig_Extension;

/**
 * Class ConvertExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class ConvertExtension extends Twig_Extension
{
    use HasTranslatorServiceTrait;

    /**
     * @var bool
     */
    private $parameterParseDownBreaksEnabled;

    /**
     * @return boolean
     */
    public function isParameterParseDownBreaksEnabled()
    {
        return $this->parameterParseDownBreaksEnabled;
    }

    /**
     * @param boolean $parameterParseDownBreaksEnabled
     *
     * @return ConvertExtension
     */
    public function setParameterParseDownBreaksEnabled($parameterParseDownBreaksEnabled)
    {
        $this->parameterParseDownBreaksEnabled = $parameterParseDownBreaksEnabled;

        return $this;
    }

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
            new \Twig_SimpleFilter(
                'convert_date_to_relative_string',
                [
                    $this,
                    'getStringByDate',
                ]
            ),
            new \Twig_SimpleFilter(
                'convert_class_path_to_short_name',
                [
                    $this,
                    'getClassShortName',
                ]
            )
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
        $parse->setBreaksEnabled($this->isParameterParseDownBreaksEnabled());

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

    /**
     * Get String By Date
     *
     * @param DateTime $date
     *
     * @return string
     */
    public function getStringByDate(DateTime $date)
    {
        $dateNow = new DateTime();

        if ($dateNow < $date) {
            return 'Future date not implemented.';
        }

        if ($date->format('Ymd') === $dateNow->format('Ymd')) {
            return $this->getTranslator()->trans('time.today');
        }

        if ($date->format('Ymd') === ((new DateTime('yesterday'))->format('Ymd'))) {
            return $this->getTranslator()->trans('time.yesterday');
        }

        $diffDays = ceil(($dateNow->getTimestamp() - $date->getTimestamp()) / 86400);

        if (7 > $diffDays) {
            return $this->getTranslator()->trans('time.days_ago', [
                '{days}' => $diffDays,
            ]);
        }

        if ($date->format('W') === (new DateTime('-1 Week'))->format('W')) {
            return $this->getTranslator()->trans('time.last_week');
        }

        if (28 > $diffDays) {
            return $this->getTranslator()->trans('time.weeks_ago', [
                '{weeks}' => floor($diffDays / 7),
            ]);
        }

        if ($date->format('Ym') === (new DateTime('-1 month'))->format('Ym')) {
            return $this->getTranslator()->trans('time.last_month');
        }

        if ($date->format('Y') === ($dateNow->format('Y') - 1)) {
            return $this->getTranslator()->trans('time.last_year');
        }

        return $this->getTranslator()->trans('time.years_ago', [
            '{years}' => $dateNow->format('Y') - $date->format('Y'),
        ]);
    }

    /**
     * Get ShortName by Class Name with Namespace
     *
     * @param string $class
     *
     * @return string
     */
    public function getClassShortName($class)
    {
        $reflection = new \ReflectionClass($class);

        return $reflection->getShortName();
    }
}