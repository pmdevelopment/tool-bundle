<?php

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class SeoUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class SeoUtility
{
    /**
     * Get Uri Path
     *
     * @param string $input
     *
     * @return mixed
     */
    public static function getPath($input)
    {
        $t = strtolower($input);

        $t = str_replace("Ö", "Oe", $t);
        $t = str_replace("Ü", "Ue", $t);
        $t = str_replace("Ä", "Ae", $t);
        $t = str_replace("ö", "oe", $t);
        $t = str_replace("ü", "ue", $t);
        $t = str_replace("ä", "ae", $t);
        $t = str_replace("'", "", $t);
        $t = str_replace('"', "", $t);
        $t = str_replace("ß", "ss", $t);
        $t = str_replace(" ", "-", $t);
        $t = str_replace(".", "-", $t);
        $t = str_replace(",", "-", $t);
        $t = str_replace("%", "prozent", $t);
        $t = str_replace("/", "-", $t);
        $t = str_replace("_", "-", $t);
        $t = str_replace("!", "", $t);
        $t = str_replace("`", "-", $t);
        $t = str_replace("´", "-", $t);
        $t = str_replace("<", "-unter-", $t);
        $t = str_replace(">", "-ueber-", $t);
        $t = str_replace("+", "-", $t);
        $t = str_replace("&", "und", $t);
        $t = str_replace("é", "e", $t);
        $t = str_replace("È", "e", $t);
        $t = str_replace("è", "e", $t);
        $t = str_replace("à", "a", $t);
        $t = str_replace("ô", "o", $t);
        $t = str_replace("(", "-", $t);
        $t = str_replace(")", "-", $t);
        $t = str_replace(":", "-", $t);
        $t = str_replace('²', "2", $t);
        $t = str_replace('*', "", $t);
        $t = str_replace('|', '-', $t);
        $t = str_replace('€', "euro", $t);

        while (strpos($t, '--') > 0) {
            $t = str_replace('--', '-', $t);
        }

        if (substr($t, -1) == '-') {
            $t = substr($t, 0, -1);
        }
        if (substr($t, 0, 1) == '-') {
            $t = substr($t, 1);
        }

        $t = urlencode($t);

        return $t;
    }
}