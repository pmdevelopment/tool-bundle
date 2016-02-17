<?php

namespace PM\Bundle\ToolBundle\KnpMenu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Description of RouteVoter
 *
 * @author sjoder
 */
class RouteVoter implements VoterInterface
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Constructor
     *
     * @param $request
     */
    public function __construct(ContainerInterface $container)
    {
        $this->requestStack = $container->get("request_stack");
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @param ItemInterface $item
     *
     * @return bool|null
     */
    public function matchItem(ItemInterface $item)
    {
        if (null === $this->getRequest()) {
            return null;
        }

        $route = $this->getRequest()->attributes->get('_route');
        $routeParameters = $this->getRequest()->attributes->get('_route_params');

        if (null === $route) {
            return null;
        }


        $routes = (array)$item->getExtra('routes', array());


        foreach ($routes as $testedRoute) {
            if ($route == $testedRoute['route']) {

                if (is_array($routeParameters) && 0 < count($routeParameters)) {
                    if (isset($testedRoute['parameters']) && is_array($testedRoute['parameters'])) {
                        $matching = true;

                        foreach ($routeParameters as $index => $value) {
                            if ('_' != substr($index, 0, 1)) {
                                if (!isset($testedRoute['parameters'][$index]) || ($testedRoute['parameters'][$index] != $value && (0 < $testedRoute['parameters'][$index]) || !is_numeric($value))) {
                                    $matching = false;
                                }
                            }
                        }

                        if ($matching) {
                            return true;
                        }
                    }
                } else {
                    return true;
                }
            }
        }

        return null;
    }

}
