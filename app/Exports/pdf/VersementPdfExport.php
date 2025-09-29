<?php
namespace App\Exports\pdf;

class VersementPdfExport extends BasePdfExport{
    public function headings():array{
        return [
            "Matricule",
            "nom",
            "association",
            "situation",
            "categorie",
            "Total versé",
            "Total restant",
        ];
    }
}
