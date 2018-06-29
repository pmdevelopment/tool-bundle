<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.12.2016
 * Time: 15:00
 */

namespace PM\Bundle\ToolBundle\Testing\TestCase;

use PM\Core\UserBundle\Component\Testing\UserTestHelper;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserWebTestCase
 *
 * @package PM\Bundle\ToolBundle\Testing\TestCase
 */
class UserWebTestCase extends AnonymousWebTestCase
{
    /**
     * Add Tests for status code
     *
     * @param int          $statusCode
     * @param string       $username
     * @param array|string $routes
     */
    public function addTestsForStatusCode($statusCode, $username, $routes)
    {
        $client = self::logIn($role);

        if (false === is_array($routes)) {
            $routes = [
                $routes,
            ];
        }

        foreach ($routes as $route) {
            $client->request(Request::METHOD_GET, $route);
            $this->assertEquals($statusCode, $client->getResponse()->getStatusCode(), sprintf('Role %s; Path %s', $role, $route));
        }
    }

    /**
     * Create Logged In Client
     *
     * Requires FOSUserBundle!
     *
     * @param string      $userName
     * @param Client|null $client
     *
     * @return Client
     */
    public static function logIn($userName, $client = null)
    {
        if (null === $client) {
            $client = self::createClientFollowingRedirects();
        }

        $session = $client->getContainer()->get('session');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $client->getContainer()->get('fos_user.security.login_manager');

        $firewallName = $client->getContainer()->getParameter('fos_user.firewall_name');

        $user = self::findUserByUsername($userName, $client);
        $loginManager->loginUser($firewallName, $user);

        $session->set(sprintf('_security_%s', $firewallName), serialize($client->getContainer()->get('security.token_storage')->getToken()));
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }


    /**
     * @param        $userName
     * @param Client $client
     *
     * @return mixed
     */
    public static function findUserByUsername($userName, Client $client)
    {
        return $client->getContainer()->get('fos_user.user_manager')->findUserByUsername($userName);
    }

}