<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.09.2018
 * Time: 13:05
 */

namespace PM\Bundle\ToolBundle\Services;


use PM\Bundle\ToolBundle\Components\Interfaces\ResponseServiceInterface;
use PM\Bundle\ToolBundle\Components\Traits\HasRouterTrait;
use PM\Bundle\ToolBundle\Components\Traits\HasSessionTrait;
use PM\Bundle\ToolBundle\Components\Traits\HasTranslatorTrait;
use PM\Bundle\ToolBundle\Components\Traits\HasTwigTrait;
use PM\Bundle\ToolBundle\Constants\Bootstrap4;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class ResponseService
 *
 * @package PM\Bundle\ToolBundle\Services
 */
class ResponseService implements ResponseServiceInterface
{
    use HasTranslatorTrait;
    use HasRouterTrait;
    use HasSessionTrait;
    use HasTwigTrait;

    /**
     * @var string
     */
    private $kernelEnvironment;

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * ResponseService constructor.
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router, SessionInterface $session, Environment $twig, $kernelEnvironment, $kernelRootDir)
    {
        $this->setTranslator($translator);
        $this->setRouter($router);
        $this->setSession($session);
        $this->setTwig($twig);

        $this->kernelEnvironment = $kernelEnvironment;
        $this->kernelRootDir = $kernelRootDir;
    }

    /**
     * @return string
     */
    public function getKernelEnvironment()
    {
        return $this->kernelEnvironment;
    }

    /**
     * @return string
     */
    public function getKernelRootDir()
    {
        return $this->kernelRootDir;
    }

    /**
     * Get redirect with saved message
     *
     * @param string            $route
     * @param string            $message
     * @param array             $routeParameters
     * @param string|null       $routeFragment
     * @param string|null|false $translationDomain
     *
     * @return RedirectResponse
     */
    public function getSavedRedirect($route, $message = 'flash_bag.success.default', $routeParameters = [], $routeFragment = null, $translationDomain = null)
    {
        $this->addSessionFlashBagMessage($message, Bootstrap4::STATE_SUCCESS, $translationDomain);

        return $this->getRedirectToRoute($route, $routeParameters, UrlGeneratorInterface::ABSOLUTE_PATH, Response::HTTP_FOUND, $routeFragment);
    }

    /**
     * Get redirect with fail message
     *
     * @param string            $route
     * @param string            $message
     * @param array             $routeParameters
     * @param string|null       $routeFragment
     * @param string|null|false $translationDomain
     *
     * @return RedirectResponse
     */
    public function getFailedRedirect($route, $message = 'flash_bag.error.default', $routeParameters = [], $routeFragment = null, $translationDomain = null)
    {
        $this->addSessionFlashBagMessage($message, Bootstrap4::STATE_DANGER, $translationDomain);

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
        $this->addSessionFlashBagMessage($message, Bootstrap4::STATE_SUCCESS);

        return new Response($body);
    }

    /**
     * Get RedirectResponse by route
     *
     * @param string      $route
     * @param array       $parameters
     * @param int         $referenceType
     * @param int         $status
     * @param null|string $fragment
     *
     * @return RedirectResponse
     */
    public function getRedirectToRoute($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $status = Response::HTTP_FOUND, $fragment = null)
    {
        $uri = $this->getRouter()->generate($route, $parameters, $referenceType);

        if (null !== $fragment) {
            $uri = sprintf('%s#%s', $uri, $fragment);
        }

        return new RedirectResponse($uri, $status);
    }

    /**
     * Get Response From Template
     * replaces old $this->render() in controller
     *
     * @param string        $template
     * @param array         $context
     * @param Response|null $response
     *
     * @return Response
     */
    public function getResponseFromTemplate($template, $context = [], $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        try {
            $html = $this->getTwig()->render($template, $context);
        } catch (\Twig_Error $twigError) {
            if (\PM\Bundle\ToolBundle\Constants\Environment::DEV === $this->getKernelEnvironment()) {
                dump($twigError);
            } else {
                throw $twigError;
            }

            /* Throw previous if it was a http exception */
            if ($twigError->getPrevious() instanceof HttpException) {
                throw $twigError->getPrevious();
            }

            $html = '<html><body>Unable to render template.</body></html>';
        }

        $response->setContent($html);

        return $response;
    }

    /**
     * Add Session Flash Bag Message
     *
     * @param string            $message
     * @param string            $type
     * @param null|string|false $translationDomain
     */
    private function addSessionFlashBagMessage($message, $type = Bootstrap4::STATE_SUCCESS, $translationDomain = null)
    {
        if (false !== $translationDomain) {
            $message = $this->getTranslator()->trans($message, [], $translationDomain);
        }

        $this->getSession()->getFlashBag()->add($type, $message);
    }
}
