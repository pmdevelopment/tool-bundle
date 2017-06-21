<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 24.05.2017
 * Time: 10:19
 */

namespace PM\Bundle\ToolBundle\Twig;

use Twig_Extension;

/**
 * Class PhpExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class PhpExtension extends Twig_Extension
{
    /**
     * Get Tests
     *
     * @return array
     */
    public function getTests()
    {
        return [
            new \Twig_SimpleTest(
                'instanceof',
                [
                    $this,
                    'isInstanceOf'
                ],
                [
                    'is_safe' => [
                        'all',
                    ],
                ]
            ),
        ];
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
                'bcdiv',
                [
                    $this,
                    'getBcdiv',
                ]
            ),
        ];
    }


    /**
     * Is Instance Of
     *
     * @param mixed $var
     * @param mixed $instance
     *
     * @return bool
     */
    public function isInstanceOf($var, $instance)
    {
        return $var instanceof $instance;
    }

    /**
     * BCDIV
     *
     * Hint: use division by one to cut decimals without rounding
     *
     * @param float     $operandLeft
     * @param int|float $operandRight
     * @param int       $scale
     *
     * @return string
     */
    public function getBcdiv($operandLeft, $operandRight = 1, $scale = 0)
    {
        return bcdiv($operandLeft, $operandRight, $scale);
    }
}