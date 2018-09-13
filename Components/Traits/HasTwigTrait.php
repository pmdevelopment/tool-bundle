<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 12.09.2018
 * Time: 14:24
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use \Twig_Environment;

/**
 * Trait HasTwigTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasTwigTrait
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @return Twig_Environment
     */
    public function getTwig()
    {
        if (null === $this->twig) {
            throw new \LogicException('Twig not found. Setter not called?');
        }

        return $this->twig;
    }

    /**
     * @param Twig_Environment $twig
     *
     * @return HasTwigServiceTrait
     */
    public function setTwig($twig)
    {
        $this->twig = $twig;

        return $this;
    }
}