<?php

namespace PM\TwigtoolBundle\Twig;

use Twig_Extension;

/**
 * Description of CssVersionExtension
 *
 * @author sjoder
 */
class CssVersionExtension extends Twig_Extension {

   public function getFilters() {
      return array(
          'cssversion' => new \Twig_Filter_Method($this, 'cssVersion'),
      );
   }

   public function cssVersion($file) {
      if (file_exists($file))
         $string = "?v=" . substr(md5_file($file), 0, 5);
      else
         return '';

      return $string;
   }

   public function getName() {
      return 'plugin_pm_cssversion';
   }

}