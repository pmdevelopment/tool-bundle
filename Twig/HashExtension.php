<?php

namespace PM\Bundle\ToolBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;
/**
 * Description of CssVersionExtension
 *
 * @author sjoder
 */
class HashExtension extends Twig_Extension {

   public function getFilters() {
      return array(
          'sha1' => new Twig_Filter_Method($this, 'hashSHA1'),
          'md5' => new Twig_Filter_Method($this, 'hashMD5'),
      );
   }

   public function hashSHA1($string){
      if(empty($string))
         return sha1(microtime());
      
      return sha1($string);
   }
   
   public function hashMD5($string){
      if(empty($string))
         return md5(microtime());
      
      return md5($string);
   }
   
   
   

   public function getName() {
      return 'plugin_pm_hash';
   }

}
