<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfModel
{

    public static function getPdf(string $title, Array $headers, Array $data,string $filename, $landscape = false){
        $dataToPass = [
            "title" => $title,
            "filename" => $filename,
            "headers" => $headers,
            "datas" => $data,
        ];

        $name = $filename.".pdf";

        $pdf = Pdf::setPaper('a4', ($landscape) ? 'landscape' : 'portrait')->loadView('pdf.default', compact('dataToPass'));


        return $pdf->stream($name);
    }

}
