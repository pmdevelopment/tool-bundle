<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.04.2018
 * Time: 16:39
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\RequestStack;


/**
 * Trait HasRequestStackTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasRequestStackTrait
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Get RequestStack
     *
     * @param bool $optional
     *
     * @return RequestStack
     */
    public function getRequestStack($optional = false)
    {
        if ($this instanceof ContainerAwareInterface) {
            return $this->get('request_stack');
        }

        if (false === $optional && null === $this->requestStack) {
            throw new \RuntimeException('RequestStack not found. Setter not called?');
        }

        return $this->requestStack;
    }

    /**
     * @param RequestStack $requestStack
     *
     * @return HasRequestStackTrait
     */
    public function setRequestStack($requestStack)
    {
        $this->requestStack = $requestStack;

        return $this;
    }

    /**
     * Get current Request
     *
     * @param bool $optional
     *
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    public function getRequestCurrent($optional = false)
    {
        $requestStack = $this->getRequestStack($optional);

        if (null === $requestStack) {
            return null;
        }

        return $this->getRequestStack($optional)->getCurrentRequest();
    }
}