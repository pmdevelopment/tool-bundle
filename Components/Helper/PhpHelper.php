<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 04.04.2018
 * Time: 13:18
 */

namespace PM\Bundle\ToolBundle\Components\Helper;


/**
 * Class PhpHelper
 *
 * @package PM\Bundle\ToolBundle\Components\Helper
 */
class PhpHelper
{
    /**
     * Undo parse_url()
     *
     * @param array $url
     *
     * @return string
     */
    public static function unParseUrl($url)
    {
        $result = [];

        $parts = [
            'scheme'   => '%s://',
            'user'     => '%s',
            'pass'     => ':%s',
            'host'     => '%s',
            'port'     => ':%s',
            'path'     => '%s',
            'query'    => '?%s',
            'fragment' => '#%s',
        ];

        foreach ($parts as $partKey => $partValue) {
            if (false === isset($url[$partKey])) {
                continue;
            }

            $value = sprintf($partValue, $url[$partKey]);
            if ('host' === $partKey && (true === isset($url['user']) || true === isset($url['pass']))) {
                $value = sprintf('@%s', $value);
            }

            $result[] = $value;
        }

        return implode('', $result);
    }
}