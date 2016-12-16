<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 12.12.2016
 * Time: 15:58
 */

namespace PM\Bundle\ToolBundle\Testing\Helper;

use PM\Bundle\ToolBundle\Constants\HttpStatusCode;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AssertHelper
 *
 * @package PM\Bundle\ToolBundle\Testing\Helper
 */
class AssertHelper
{
    /**
     * Assert Empty Response
     *
     * @param WebTestCase $case
     * @param Client      $client
     */
    public static function assertEmptyResponse(WebTestCase $case, Client $client)
    {
        $case->assertEquals(HttpStatusCode::OK, $client->getResponse()->getStatusCode(), self::saveResponseForFailedAssert($client));
        $case->assertEmpty($client->getResponse()->getContent(), self::saveResponseForFailedAssert($client));
    }

    /**
     * Save Response For Failed Asserts
     *
     * @param Client $client
     * @param string $prefix
     *
     * @return string
     */
    public static function saveResponseForFailedAssert($client, $prefix = "")
    {
        if (true === empty($prefix)) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            if (true === isset($trace[1]['class'])) {
                $prefix = $trace[1]['class'];
            }
        }

        $prefix = str_replace('\\', '_', $prefix);
        $prefix = sprintf('%s-', $prefix);

        $tempFileName = tempnam(sys_get_temp_dir(), $prefix);

        $debug = [
            $client->getRequest()->getUri(),
            $client->getResponse()->getStatusCode(),
            '',
            '',
            $client->getResponse()->getContent(),
        ];

        file_put_contents($tempFileName, implode(PHP_EOL, $debug));

        return sprintf('Get your full response body here: %s', $tempFileName);
    }

    /**
     * Get Filter contains
     *
     * @param string $element
     * @param string $value
     *
     * @return string
     */
    public static function getFilterContains($element, $value)
    {
        return sprintf('%s:contains("%s")', $element, $value);
    }
}