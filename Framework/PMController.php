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
   public function denied($route, $routeParameters = array(), $message = "Access denied.") {
      $this->get('session')->getFlashBag()->add('error', $message);

      return $this->redirect($this->generateUrl($route, $routeParameters));
   }

   /**
    * Denied
    * 
    * @return Response
    */
   public function saved($route, $routeParameters = array(), $message = "Changes saved.") {
      $this->get('session')->getFlashBag()->add('success', $message);

      return $this->redirect($this->generateUrl($route, $routeParameters));
   }

}