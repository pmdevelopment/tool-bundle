<?php

/**
 * This file is part of BcBootstrapBundle.
 *
 * (c) 2012-2013 by Florian Eckerstorfer
 */

namespace PM\Bundle\ToolBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;
use Twig_Environment;

/**
 * BootstrapIconExtension
 *
 * @category   TwigExtension
 * @package    BcBootstrapBundle
 * @subpackage Twig
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://bootstrap.braincrafted.com Bootstrap for Symfony2
 */
class FlagExtension extends Twig_Extension {

   protected $twig;
   protected $assetFunction;

   public function initRuntime(Twig_Environment $twig) {
      $this->twig = $twig;
   }

   protected function asset($asset) {
      if (empty($this->assetFunction)) {
         $this->assetFunction = $this->twig->getFunction('asset')->getCallable();
      }
      return call_user_func($this->assetFunction, $asset);
   }

   /**
    * {@inheritDoc}
    */
   public function getFilters() {
      return array(
          'flag' => new Twig_Filter_Method($this, 'flagFilter', array('is_safe' => array('html')))
      );
   }

   /**
    * Returns the HTML code for the given icon.
    *
    * @param string $icon  The name of the icon
    * @param string $color The color of the icon; can be "black" or "white"; defaults to "black"
    *
    * @return string The HTML code for the icon
    */
   public function flagFilter($flag) {
      $flag = strtolower($flag);
      return sprintf('<img src="%s" />', $this->asset("/bundles/pmtool/img/flags/$flag.png"));
   }

   /**
    * {@inheritDoc}
    */
   public function getName() {
      return 'pm_flag_extension';
   }

}
