<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.12.2016
 * Time: 12:24
 */

namespace PM\Bundle\ToolBundle\Testing\TestCase;


use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AnonymousWebTestCase
 *
 * @package PM\Bundle\ToolBundle\Testing\TestCase
 */
class AnonymousWebTestCase extends WebTestCase
{

    /**
     * Create Client
     *
     * followRedirects: true
     *
     * @return Client
     */
    public static function createClientFollowingRedirects()
    {
        $client = self::createClient();
        $client->followRedirects(true);

        return $client;
    }

    /**
     * Get doctrine
     *
     * @param Client $client
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine(Client $client)
    {
        return $client->getContainer()->get('doctrine');
    }
}