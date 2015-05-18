<?php

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class SeoUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class SeoUtility
{
    const PATH_FILTER_NONE = 0;
    const PATH_FILTER_GERMAN = 1;

    /**
     * Get Uri Path
     *
     * @param string $input
     * @param int    $filter
     *
     * @return string
     */
    public static function getPath($input, $filter = self::PATH_FILTER_GERMAN)
    {
        // Basic normalization
        $text = strtolower($input);
        $text = strip_tags($text);
        $text = stripslashes($text);
        $text = html_entity_decode($text);

        // Remove quotes (can't, etc.)
        $text = str_replace('\'', '', $text);

        if (self::PATH_FILTER_GERMAN === $filter) {
            // Some replaces (german)
            $text = str_replace("Ö", "Oe", $text);
            $text = str_replace("Ü", "Ue", $text);
            $text = str_replace("Ä", "Ae", $text);
            $text = str_replace("ö", "oe", $text);
            $text = str_replace("ü", "ue", $text);
            $text = str_replace("ä", "ae", $text);
            $text = str_replace("ß", "ss", $text);
            $text = str_replace("%", " prozent ", $text);
            $text = str_replace("&", " und ", $text);
            $text = str_replace('€', " euro ", $text);
        }

        $text = str_replace("é", "e", $text);
        $text = str_replace("È", "e", $text);
        $text = str_replace("è", "e", $text);
        $text = str_replace("à", "a", $text);
        $text = str_replace("ô", "o", $text);
        $text = str_replace('²', "2", $text);

        // Only alphanumeric and spaces allowed
        $text = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($text));
        // Spaces to dash
        $text = preg_replace('!\s+!', '-', $text);
        // Trim dashes
        $text = trim($text, '-');

        // To be sure: url encode
        $text = urlencode($text);

        return $text;
    }
}