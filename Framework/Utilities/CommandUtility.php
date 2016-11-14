<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 01.11.2016
 * Time: 15:56
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class CommandUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class CommandUtility
{
    /**
     * Get Command Running count.
     *
     * Returns running php process count for given command name.
     *
     * @param string $name e.g. pm:bundle:command
     *
     * @return int
     */
    public static function getCommandRunningCount($name)
    {
        exec(sprintf("ps auxwww|grep %s|grep -v grep", $name), $output);

        if (false === is_array($output)) {
            return 1;
        }

        /*
         * Remove Crontab sh calls
         */
        foreach ($output as $lineIndex => $line) {
            if (false !== strpos($line, '/bin/sh -c')) {
                unset($output[$lineIndex]);
            }
        }

        return count($output);
    }

}