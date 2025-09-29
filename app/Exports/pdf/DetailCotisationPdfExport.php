<?php
namespace App\Exports\pdf;

use App\Exports\pdf\BasePdfExport;

class DetailCotisationPdfExport extends BasePdfExport{
    public function headings():array{
        return [
            "id",
            "Type",
            "somme",
            "date versement",
        ];
    }
}
