<?php
namespace PM\Bundle\ToolBundle\Twig;

use Twig_Extension;
use Twig_Environment;

/**
 * Class FlagExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class FlagExtension extends Twig_Extension
{

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        $callable = [
            $this,
            "flagFilter"
        ];

        return [
            new \Twig_SimpleFilter("flag", $callable, [
                "is_safe"           => ["html"],
                "needs_environment" => true
            ])
        ];
    }

    /**
     * Get Flag Image
     *
     * @param Twig_Environment $twig
     * @param string           $flag
     *
     * @return string
     */
    public function flagFilter(Twig_Environment $twig, $flag)
    {
        return sprintf('<img src="%s" />', call_user_func($twig->getFunction('asset')->getCallable(), sprintf("/bundles/pmtool/img/flags/%s.png", strtolower($flag))));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'pm_flag_extension';
    }

}
