<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 13.12.2016
 * Time: 15:14
 */

namespace PM\Bundle\ToolBundle\Testing\Helper;

/**
 * Class TextHelper
 *
 * @package PM\Bundle\ToolBundle\Testing\Helper
 */
class TextHelper
{
    const SETTING_LENGTH_SHORT = 'short';
    const SETTING_LENGTH_MEDIUM = 'medium';
    const SETTING_LENGTH_LONG = 'long';
    const SETTING_LENGTH_LONGER = 'verylong';

    const SETTING_DECORATE = 'decorate';

    const FORMAT_PLAIN = 'plaintext';
    const FORMAT_HTML = null;

    const VALUE_ID_NOT_EXISTING = 1337;

    /**
     * Get lorem Ipsum Text
     *
     * @param int    $paragraphs
     * @param string $format
     * @param array  $settings
     *
     * @return string
     */
    public static function getLoremIpsum($paragraphs = 1, $format = self::FORMAT_PLAIN, $settings = [])
    {
        $uri = [
            'http://loripsum.net/api',
            $paragraphs,
        ];

        if (self::FORMAT_HTML !== $format) {
            $uri[] = $format;
        }

        $uri = array_merge($uri, $settings);

        return trim(file_get_contents(implode('/', $uri)));
    }
}