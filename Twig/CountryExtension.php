<?php

namespace PM\Bundle\ToolBundle\Twig;

use Symfony\Component\Intl\Intl;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Class CountryExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class CountryExtension extends Twig_Extension
{

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('country', [
                $this,
                'countryFilter'
            ], ['is_safe' => ['html']])
        ];
    }

    /**
     * Returns the Country Name for a given code
     *
     * @param string $country A country code (e.g. "US").
     *
     * @return string
     */
    public function countryFilter($country)
    {
        $country = strtoupper($country);

        return Intl::getRegionBundle()->getCountryName($country);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'pm_country_extension';
    }

}
