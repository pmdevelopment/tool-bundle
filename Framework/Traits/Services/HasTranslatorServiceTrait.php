<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 20.12.2016
 * Time: 13:48
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;

use Symfony\Component\Translation\IdentityTranslator;

/**
 * Class HasTranslatorServiceTrait
 *
 * @package    PM\Bundle\ToolBundle\Framework\Traits\Services
 *
 * @deprecated Use PM\Bundle\ToolBundle\Components\Traits\HasTranslatorTrait instead
 */
trait HasTranslatorServiceTrait
{
    /**
     * @var IdentityTranslator
     */
    private $translator;

    /**
     * @return IdentityTranslator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param IdentityTranslator $translator
     *
     * @return HasTranslatorServiceTrait
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;

        return $this;
    }

}