<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.09.2018
 * Time: 13:06
 */

namespace PM\Bundle\ToolBundle\Components\Traits;


use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Trait HasTranslatorTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasTranslatorTrait
{
    /**
     * @var TranslatorInterface|Translator
     */
    private $translator;

    /**
     * @return Translator|TranslatorInterface
     */
    public function getTranslator()
    {
        if (null === $this->translator) {
            throw new \LogicException('Translator not found. Setter not called?');
        }

        return $this->translator;
    }

    /**
     * @param Translator|TranslatorInterface $translator
     *
     * @return HasTranslatorTrait
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;

        return $this;
    }
}