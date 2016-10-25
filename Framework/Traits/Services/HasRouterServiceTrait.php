<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 25.10.2016
 * Time: 15:57
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class HasRouterServiceTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Services
 */
trait HasRouterServiceTrait
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param Router $router
     *
     * @return HasRouterServiceTrait
     */
    public function setRouter($router)
    {
        $this->router = $router;

        return $this;
    }


}