<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 12.09.2018
 * Time: 14:22
 */

namespace PM\Bundle\ToolBundle\Components\Interfaces;

/**
 * Interface ResponseServiceInterface
 *
 * @package PM\Bundle\ToolBundle\Components\Interfaces
 */
interface ResponseServiceInterface
{

    /**
     * Get Kernel Root Dir
     *
     * @return string
     */
    public function getKernelRootDir();

    /**
     * Get Kernel Environment
     *
     * @return string
     */
    public function getKernelEnvironment();

    /**
     * Get redirect with saved message
     *
     * @param string            $route
     * @param string            $message
     * @param array             $routeParameters
     * @param string|null       $routeFragment
     * @param string|null|false $translationDomain
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getSavedRedirect($route, $message = 'flash_bag.success.default', $routeParameters = [], $routeFragment = null, $translationDomain = null);

    /**
     * Get redirect with fail message
     *
     * @param string            $route
     * @param string            $message
     * @param array             $routeParameters
     * @param string|null       $routeFragment
     * @param string|null|false $translationDomain
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getFailedRedirect($route, $message = 'flash_bag.error.default', $routeParameters = [], $routeFragment = null, $translationDomain = null);

    /**
     * Get response with saved message
     *
     * @param string $body
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getSavedResponse($body = '', $message = 'flash_bag.success.default');

    /**
     * Get RedirectResponse by route
     *
     * @param string $route
     * @param array  $parameters
     * @param int    $referenceType
     * @param int    $status
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getRedirectToRoute($route, $parameters = [], $referenceType = \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_PATH, $status = \Symfony\Component\HttpFoundation\Response::HTTP_FOUND);

    /**
     * Get Response From Template
     *
     * @param string                                          $template
     * @param array                                           $context
     * @param \Symfony\Component\HttpFoundation\Response|null $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponseFromTemplate($template, $context = [], $response = null);
}