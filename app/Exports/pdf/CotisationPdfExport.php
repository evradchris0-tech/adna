<?php
namespace App\Exports\pdf;

use App\Exports\pdf\BasePdfExport;

class CotisationPdfExport extends BasePdfExport{
    public function headings():array{
        return [
            "Matricule",
            "nom",
            "association",
            "situation",
            "categorie",
            "Total",
        ];
    }
}
