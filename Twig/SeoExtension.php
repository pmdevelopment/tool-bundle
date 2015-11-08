<?php

namespace PM\Bundle\ToolBundle\Twig;

use PM\Bundle\ToolBundle\Framework\Utilities\SeoUtility;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Description of CssVersionExtension
 *
 * @author sjoder
 */
class SeoExtension extends Twig_Extension
{

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter("slug", array(
                $this,
                'slug'
            ))
        );
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function slug($string)
    {
        return SeoUtility::getPath($string);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return "pm_seo_extension";
    }


}
