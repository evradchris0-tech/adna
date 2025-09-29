<?php

namespace App\Exports\pdf;


class ParoissienPdfExport extends BasePdfExport
{

    public function headings():array{
        return [
            "nom",
            "genre",
            "date & lieu de naissance",
            "niveu d'etude",
            "numero de téléphone",
        ];
    }
}
