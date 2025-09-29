<?php

namespace App\Exports\pdf;


class DetailVersementPdfExport extends BasePdfExport
{
    public function headings():array{
        return [
            "N°",
            "Type",
            "Somme",
            "Date versement",
        ];
    }
}
