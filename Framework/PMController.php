<?php

namespace PM\Bundle\ToolBundle\Framework;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PMController
 *
 * @author sjoder
 */
class PMController extends Controller {

   /**
    * Denied
    * 
    * @return Response
    */
   public function denied($route, $routeParameters = array()) {
      $this->get('session')->getFlashBag()->add('error', 'Access denied.');

      return $this->redirect($this->generateUrl($route, $routeParameters));
   }

   /**
    * Denied
    * 
    * @return Response
    */
   public function saved($route, $routeParameters = array()) {
      $this->get('session')->getFlashBag()->add('success', 'Changes saved.');

      return $this->redirect($this->generateUrl($route, $routeParameters));
   }

}
