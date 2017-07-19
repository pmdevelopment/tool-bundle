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
    const SESSION_FLASH_BAG_SUCCESS = 'success';
    const SESSION_FLASH_BAG_ERROR = 'error';

    /**
     * Add FlashBag Message
     *
     * @param string      $type
     * @param string      $message
     * @param string|bool $translationDomain
     *
     * @return $this
     */
    public function addSessionFlashBagMessage($type, $message, $translationDomain = 'messages')
    {
        if (false !== $translationDomain) {
            $message = $this->get('translator')->trans($message, [], $translationDomain);
        }

        $this->get('session')->getFlashBag()->add($type, $message);

        return $this;
    }

    /**
     * Denied
     *
     * @param string $route
     * @param array  $routeParameters
     * @param string $message
     *
     * @return Response
     */
    public function denied($route, $routeParameters = [], $message = 'flash_bag.error.default')
    {
        $this->addSessionFlashBagMessage(self::SESSION_FLASH_BAG_ERROR, $message);

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
    public function saved($route, $routeParameters = [], $message = 'flash_bag.success.default')
    {
        $this->addSessionFlashBagMessage(self::SESSION_FLASH_BAG_SUCCESS, $message);

        return $this->redirect($this->generateUrl($route, $routeParameters));
    }

    /**
     * Saved with Object Routing
     *
     * @param string      $type
     * @param mixed       $object
     * @param string      $message
     * @param string|null $fragment
     *
     * @return RedirectResponse
     */
    public function savedObject($type, $object, $message = 'flash_bag.success.default', $fragment = null)
    {
        $objectRouter = $this->get("bg_object_routing.object_router");
        $this->addSessionFlashBagMessage(self::SESSION_FLASH_BAG_SUCCESS, $message);

        $url = $objectRouter->generate($type, $object);
        if (null !== $fragment) {
            $url = sprintf('%s#%s', $url, $fragment);
        }

        return $this->redirect($url);
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
    public function deniedObject($type, $object, $message = 'flash_bag.error.default')
    {
        $objectRouter = $this->get("bg_object_routing.object_router");
        $this->addSessionFlashBagMessage(self::SESSION_FLASH_BAG_ERROR, $message);

        return $this->redirect($objectRouter->generate($type, $object));
    }

}