<?php

namespace PM\Bundle\ToolBundle\Framework;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PMController
 *
 * @author sjoder
 */
class PMController extends Controller
{

    /**
     * Denied
     *
     * @param string $route
     * @param array  $routeParameters
     * @param string $message
     *
     * @return Response
     */
    public function denied($route, $routeParameters = array(), $message = "Zugriff verweigert.")
    {
        $this->get('session')->getFlashBag()->add('error', $message);

        return $this->redirect($this->generateUrl($route, $routeParameters));
    }

    /**
     * Saved
     *
     * @param string $route
     * @param array  $routeParameters
     * @param string $message
     *
     * @return Response
     */
    public function saved($route, $routeParameters = array(), $message = "Die Änderungen wurden erfolgreich gespeichert.")
    {
        $this->get('session')->getFlashBag()->add('success', $message);

        return $this->redirect($this->generateUrl($route, $routeParameters));
    }

    /**
     * Saved with Object Routing
     *
     * @param string $type
     * @param mixed  $object
     * @param string $message
     *
     * @return RedirectResponse
     */
    public function savedObject($type, $object, $message = "Die Änderungen wurden erfolgreich gespeichert.")
    {
        $objectRouter = $this->get("bg_object_routing.object_router");
        $this->get('session')->getFlashBag()->add('success', $message);

        return $this->redirect($objectRouter->generate($type, $object));
    }

    /**
     * Denied with Object Routing
     *
     * @param string $type
     * @param mixed  $object
     * @param string $message
     *
     * @return RedirectResponse
     */
    public function deniedObject($type, $object, $message = "Zugriff verweigert.")
    {
        $objectRouter = $this->get("bg_object_routing.object_router");
        $this->get('session')->getFlashBag()->add('error', $message);

        return $this->redirect($objectRouter->generate($type, $object));
    }

}