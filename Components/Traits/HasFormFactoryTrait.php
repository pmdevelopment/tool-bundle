<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 12.10.2018
 * Time: 13:55
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * Trait HasFormFactoryTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasFormFactoryTrait
{
    /**
     * @var FormFactoryInterface $formFactory
     */
    private $formFactory;

    /**
     * @return FormFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    /**
     * @param FormFactoryInterface $formFactory
     *
     * @return HasFormFactoryTrait
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;

        return $this;
    }

}