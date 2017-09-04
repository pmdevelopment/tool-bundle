<?php

namespace PM\Bundle\ToolBundle\KnpMenu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
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
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * Match Item
     *
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


        $routes = $item->getExtra('routes', []);
        if (false === is_array($routes)) {
            return null;
        }

        foreach ($routes as $testedRoute) {
            /* Route matching? */
            if ($route !== $testedRoute['route']) {
                continue;
            }

            /* No Parameters? */
            if (false === is_array($routeParameters) || 0 === count($routeParameters)) {
                return true;
            }

            /* Parameters only in current route? */
            if (false === isset($testedRoute['parameters']) || false === is_array($testedRoute['parameters'])) {
                continue;
            }

            /* Assume match, until different parameters are found */
            $matching = true;

            foreach ($routeParameters as $index => $value) {
                /* Ignore underscored parameters */
                if ('_' === substr($index, 0, 1)) {
                    continue;
                }

                /* Parameter not found */
                if (false === isset($testedRoute['parameters'][$index])) {
                    $matching = false;

                    break;
                }

                /* Parameter not matching */
                if ($testedRoute['parameters'][$index] !== $value && ((0 < $testedRoute['parameters'][$index]) || false === is_numeric($value))) {
                    $matching = false;

                    break;
                }
            }

            if (true === $matching) {
                return true;
            }

        }

        return null;
    }

}
