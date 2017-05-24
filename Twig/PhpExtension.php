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

}