<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 12.12.2016
 * Time: 15:58
 */

namespace PM\Bundle\ToolBundle\Testing\Helper;

/**
 * Class AssertHelper
 *
 * @package PM\Bundle\ToolBundle\Testing\Helper
 */
class AssertHelper
{
    /**
     * Save Response For Failed Asserts
     *
     * @param \Symfony\Bundle\FrameworkBundle\Client $client
     * @param string                                 $prefix
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

        $prefix = str_replace("\\", "_", $prefix);
        $prefix = sprintf("%s-", $prefix);

        $tempFileName = tempnam(sys_get_temp_dir(), $prefix);
        file_put_contents($tempFileName, $client->getResponse()->getContent());

        return sprintf('Get your full response body here: %s', $tempFileName);
    }

}