<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 25.04.2018
 * Time: 12:52
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use Symfony\Component\Translation\TranslatorInterface;


/**
 * trait HasTranslatorTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasTranslatorTrait
{
    /**
     * @var TranslatorInterface
     */
    private $tanslator;

    /**
     * @return TranslatorInterface
     */
    public function getTranslator()
    {
        if ($this instanceof ContainerAwareInterface) {
            if ($this instanceof ContainerAwareCommand) {
                return $this->getContainer()->get('translator');
            }

            return $this->get('translator');
        }

        if (null === $this->tanslator) {
            throw new \LogicException('Missing translator. Setter not called?');
        }

        return $this->tanslator;
    }

    /**
     * @param TranslatorInterface $translator
     *
     * @return $this
     */
    public function setTranslator($translator)
    {
        $this->tanslator = $translator;

        return $this;
    }


}