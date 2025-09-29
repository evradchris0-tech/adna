<?php

namespace App\Exports\pdf;

class DetailPerformancePdfExport extends BasePdfExport
{
    public function headings():array{
        return [
            "N°",
            "Nom",
            "Dime",
            "Offrande construction",
            "Dette dime",
            "Dette construction",
            "Status",
        ];
    }
}
