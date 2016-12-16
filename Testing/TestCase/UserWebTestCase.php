<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.12.2016
 * Time: 15:00
 */

namespace PM\Bundle\ToolBundle\Testing\TestCase;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;

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
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $client->getContainer()->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $client->getContainer()->get('fos_user.security.login_manager');

        $firewallName = $client->getContainer()->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserByUsername($userName);
        $loginManager->loginUser($firewallName, $user);

        $session->set(sprintf('_security_%s', $firewallName), serialize($client->getContainer()->get('security.token_storage')->getToken()));
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

}