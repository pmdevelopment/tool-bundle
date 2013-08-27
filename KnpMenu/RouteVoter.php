<?php

namespace PM\Bundle\ToolBundle\KnpMenu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Description of RouteVoter
 *
 * @author sjoder
 */
class RouteVoter implements VoterInterface
{
    /**
     * @var Request
     */
    private $request;
    
    public function __construct(Container $container) {
       $request = $container->get('request');
       
       $this->setRequest($request);
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function matchItem(ItemInterface $item)
    {
        if (null === $this->request) {
            return null;
        }

        $route = $this->request->attributes->get('_route');
        if (null === $route) {
            return null;
        }

        $routes = (array) $item->getExtra('routes', array());
        
        // $parameters = (array) $item->getExtra('routesParameters', array());
        
        foreach ($routes as $testedRoute) {
            if ($route == $testedRoute['route']) {
                return true;
            }
            
            
        }

        return null;
    }
}