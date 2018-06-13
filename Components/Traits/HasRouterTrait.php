<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 13.06.2018
 * Time: 13:23
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
     * @var RouterInterface
     */
    private $router;

    /**
     * @return RouterInterface|Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     *
     * @return HasRouterTrait
     */
    public function setRouter($router)
    {
        $this->router = $router;

        return $this;
    }

}