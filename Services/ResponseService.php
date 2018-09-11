<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.09.2018
 * Time: 13:05
 */

namespace PM\Bundle\ToolBundle\Services;


use PM\Bundle\ToolBundle\Components\Traits\HasRouterTrait;
use PM\Bundle\ToolBundle\Components\Traits\HasSessionTrait;
use PM\Bundle\ToolBundle\Components\Traits\HasTranslatorTrait;
use PM\Bundle\ToolBundle\Constants\Bootstrap4;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ResponseService
 *
 * @package PM\Bundle\ToolBundle\Services
 */
class ResponseService
{
    use HasTranslatorTrait;
    use HasRouterTrait;
    use HasSessionTrait;

    /**
     * ResponseService constructor.
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router, SessionInterface $session)
    {
        $this->setTranslator($translator);
        $this->setRouter($router);
        $this->setSession($session);
    }

    /**
     * Get redirect with saved message
     *
     * @param string      $route
     * @param string      $message
     * @param array       $routeParameters
     * @param string|null $routeFragment
     *
     * @return RedirectResponse
     */
    public function getSavedRedirect($route, $message = 'flash_bag.success.default', $routeParameters = [], $routeFragment = null)
    {
        $this->addSessionFlashBagMessage($message);

        return $this->getRedirectToRoute($route, $routeParameters);
    }

    /**
     * Get response with saved message
     *
     * @param string $body
     * @param string $message
     *
     * @return Response
     */
    public function getSavedResponse($body = '', $message = 'flash_bag.success.default')
    {
        $this->addSessionFlashBagMessage(Bootstrap4::STATE_SUCCESS, $message);

        return new Response($body);
    }

    /**
     * Get RedirectResponse by route
     *
     * @param string $route
     * @param array  $parameters
     * @param int    $referenceType
     * @param int    $status
     *
     * @return RedirectResponse
     */
    public function getRedirectToRoute($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $status = Response::HTTP_FOUND)
    {
        return new RedirectResponse($this->getRouter()->generate($route, $parameters, $referenceType), $status);
    }

    /**
     * Add Session Flash Bag Message
     *
     * @param string $message
     * @param string $type
     * @param string $translationDomain
     */
    private function addSessionFlashBagMessage($message, $type = Bootstrap4::STATE_SUCCESS, $translationDomain = 'messages')
    {
        if (false !== $translationDomain) {
            $message = $this->getTranslator()->trans($message, [], $translationDomain);
        }

        $this->getSession()->getFlashBag()->add($type, $message);
    }
}