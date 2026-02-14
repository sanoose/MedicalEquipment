<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
// use Laravel\Pail\Options as PailOptions;

class PdfService
{
    public function generateInvoicePdf($data)
    {
        $options = new PailOptions();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        $html = view('pdf.invoice', $data)->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();
    }
}