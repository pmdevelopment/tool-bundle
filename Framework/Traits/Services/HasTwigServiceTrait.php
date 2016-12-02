<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 02.12.2016
 * Time: 10:48
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;

use Twig_Environment;

/**
 * Class HasTwigServiceTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Services
 */
trait HasTwigServiceTrait
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