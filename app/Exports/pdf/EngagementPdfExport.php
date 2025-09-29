<?php

namespace App\Exports\pdf;


class EngagementPdfExport extends BasePdfExport
{

    public function headings():array{
        return ['id','nom','montant(dime/construction)','dette(dime/construction)', "periode(debut/fin)"];
    }
}
