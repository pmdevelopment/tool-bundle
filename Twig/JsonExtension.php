<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 16.05.2016
 * Time: 17:48
 */

namespace PM\Bundle\ToolBundle\Twig;

/**
 * Class JsonExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class JsonExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter("json_decode", [
                $this,
                "getJsonDecode"
            ])
        ];
    }

    /**
     * Get JSON Decode
     *
     * @param mixed $input
     *
     * @return mixed
     */
    public function getJsonDecode($input)
    {
        return json_decode($input);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return "pm.twig.json";
    }


}