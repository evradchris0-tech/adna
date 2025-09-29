<?php

namespace App\Exports\pdf;

class PerformancePdfExport extends BasePdfExport
{
    public function headings():array{
        return [
            "N°",
            "Nom",
            "Sigle",
            "Dime",
            "Offrande construction",
            "Dette dime",
            "Dette construction",
            "Status",
        ];
    }
}
