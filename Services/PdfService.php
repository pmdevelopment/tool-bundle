<?php

namespace PM\Bundle\ToolBundle\Services;

use DOMPDF;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PdfService
 *
 * @author sjoder
 */
class PdfService {

   /**
    * 
    * @param string $kerneldir
    */
   private $kerneldir;

   /**
    *
    * @var DOMPDF
    */
   private $pdf;

   /**
    *
    * @var array
    */
   private $options;

   public function __construct($kerneldir) {
      $this->kerneldir = $kerneldir;
      $this->options = array();
   }

   /**
    * 
    * @param string $key
    * @param string $value
    */
   public function setOption($key, $value) {
      $this->options[$key] = $value;
   }

   /**
    * Get PDF By HTML
    * 
    * @param string $html
    * @return Response
    */
   public function get($html) {
      $kernel_dir = $this->kerneldir;
      require __DIR__ . "/../Resources/config/dompdf.php";

      $this->pdf = new DOMPDF;

      foreach ($this->options as $optionKey => $optionValue) {
         $this->pdf->set_option($optionKey, $optionValue);
      }

      $this->pdf->load_html($html);
      $this->pdf->set_paper("a4", 'portrait');
      $this->pdf->render();

      $response = new Response();

      $response->setContent($this->pdf->output());
      $response->setStatusCode(200);
      $response->headers->set('Content-Type', 'application/pdf');

      return $response;
   }

}
