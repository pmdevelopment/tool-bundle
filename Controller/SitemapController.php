<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 29.08.2018
 * Time: 14:49
 */

namespace PM\Bundle\ToolBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SitemapController
 *
 * @package PM\Bundle\ToolBundle\Controller
 */
class SitemapController extends Controller
{
    /**
     * Default
     *
     * @return Response
     *
     * @Route("/sitemap.xml")
     */
    public function defaultAction(Request $request)
    {
        $localeDefault = $request->getDefaultLocale();

        $locales = $this->getParameter('pm__tool.configuration.sitemap.locales');

        if (true === empty($locales)) {
            $locales = [];
        } else {
            $locales = explode('|', $locales);
        }


        $router = $this->get('router');
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml"></urlset>');

        foreach ($router->getRouteCollection()->all() as $routeKey => $routeAnnotation) {
            if (false === array_key_exists('sitemap', $routeAnnotation->getOptions())) {
                continue;
            }

            $routeOptions = $routeAnnotation->getOptions();
            if (true !== $routeOptions['sitemap']) {
                continue;
            }

            $url = $router->generate($routeKey, [
                '_locale' => $localeDefault,
            ], RouterInterface::ABSOLUTE_URL);

            $xmlUrl = $xml->addChild('url');

            /* Default locale */
            $xmlUrl->addChild('loc', $url);

            /* All locales */
            foreach ($locales as $locale) {
                $url = $router->generate($routeKey, [
                    '_locale' => $locale,
                ], RouterInterface::ABSOLUTE_URL);

                $xmlLocale = $xmlUrl->addChild('xhtml:link', null);
                $xmlLocale->addAttribute('rel', 'alternate');
                $xmlLocale->addAttribute('hreflang', $locale);
                $xmlLocale->addAttribute('href', $url);
            }
        }

        return new Response(
            $xml->asXML(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/xml',
            ]
        );
    }

}