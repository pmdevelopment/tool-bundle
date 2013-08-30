# DOMPDF

## Generate PDF

```php
      /* @var $pdfService PdfService */
      $pdfService = $this->get('pm.pdf');
      
      $html = $this->renderView("ACMEDemoBundle:Demo:pdf.html.twig", array('foo' => 'bar'));
      
      return $pdfService->get($html);  
```
