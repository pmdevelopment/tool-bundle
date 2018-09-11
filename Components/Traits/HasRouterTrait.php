<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.09.2018
 * Time: 13:07
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * Trait HasRouterTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasRouterTrait
{
    /**
     * @var RouterInterface|Router
     */
    private $router;

    /**
     * @return Router|RouterInterface
     */
    public function getRouter()
    {
        if (null === $this->router) {
            throw new \LogicException('Router not found. Setter not called?');
        }

        return $this->router;
    }

    /**
     * @param Router|RouterInterface $router
     *
     * @return HasRouterTrait
     */
    public function setRouter($router)
    {
        $this->router = $router;

        return $this;
    }

}