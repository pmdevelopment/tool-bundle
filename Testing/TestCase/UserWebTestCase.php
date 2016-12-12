<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.12.2016
 * Time: 15:00
 */

namespace PM\Bundle\ToolBundle\Testing\TestCase;

use PM\Bundle\ToolBundle\Framework\Utilities\SecurityUtility;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class UserWebTestCase
 *
 * @package PM\Bundle\ToolBundle\Testing\TestCase
 */
class UserWebTestCase extends AnonymousWebTestCase
{
    /**
     * Create Logged In Client
     *
     *
     * @param Client|null $client
     * @param object      $user
     * @param array       $roles
     * @param array       $firewalls
     *
     * @return Client
     */
    public static function logIn($user, $roles, $firewalls = [], $client = null)
    {
        if (null === $client) {
            $client = self::createClientFollowingRedirects();
        }

        $session = $client->getContainer()->get('session');

        if (0 === count($firewalls)) {
            $firewalls = SecurityUtility::getFirewallWithAccessListenerNames($client->getContainer());
        }

        foreach ($firewalls as $firewallName) {
            $token = new UsernamePasswordToken(
                $user,
                null,
                $firewallName,
                $roles
            );

            $session->set(sprintf('_security_%s', $firewallName), serialize($token));
        };

        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        return $client;
    }

}