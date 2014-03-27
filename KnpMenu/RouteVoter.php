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
class RouteVoter implements VoterInterface {

   /**
    * @var Request
    */
   private $request;

   public function __construct(Container $container) {
      $request = $container->get('request');

      $this->setRequest($request);
   }

   public function setRequest(Request $request) {
      $this->request = $request;
   }

   public function matchItem(ItemInterface $item) {
      if (null === $this->request) {
         return null;
      }

      $route = $this->request->attributes->get('_route');
      $routeParameters = $this->request->attributes->get('_route_params');

      if (null === $route) {
         return null;
      }


      $routes = (array) $item->getExtra('routes', array());


      foreach ($routes as $testedRoute) {
         if ($route == $testedRoute['route']) {

            if (is_array($routeParameters) && 0 < count($routeParameters)) {
               if (isset($testedRoute['parameters']) && is_array($testedRoute['parameters'])) {
                  $matching = true;

                  foreach ($routeParameters as $index => $value) {
                     if ('_' != substr($index, 0, 1)) {
                        if (!isset($testedRoute['parameters'][$index]) || ($testedRoute['parameters'][$index] != $value && 0 < $testedRoute['parameters'][$index])) {
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
