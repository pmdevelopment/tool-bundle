<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.12.2016
 * Time: 12:24
 */

namespace PM\Bundle\ToolBundle\Testing\TestCase;


use PM\Bundle\ToolBundle\Testing\Helper\AssertHelper;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * Get Json
     *
     * @param Client $client
     *
     * @return array
     */
    public function getClientResponseJson(Client $client)
    {
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode(), AssertHelper::saveResponseForFailedAssert($client));
        $this->assertNotFalse(strpos($client->getResponse()->headers->get('Content-Type'), '/json'));
        $this->assertNotEmpty($client->getResponse()->getContent());

        $body = $client->getResponse()->getContent();
        $json = json_decode($body, true);

        $this->assertTrue(is_array($json));

        return $json;
    }

    /**
     * Find Entity By
     *
     * @param Client $client
     * @param string $className
     * @param string $fieldName
     * @param string $fieldValue
     *
     * @return null|object
     */
    public function findEntityBy(Client $client, $className, $fieldName, $fieldValue)
    {
        $entity = $this->getDoctrine($client)->getRepository($className)->findOneBy(
            [
                $fieldName => $fieldValue,
            ]
        );

        $this->assertInstanceOf($className, $entity);

        return $entity;
    }
}