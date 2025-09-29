<?php

namespace App\Exports\pdf;


class AssociationsPdfExport extends BasePdfExport
{

    public function headings():array{
        return ['id','nom','sigle','date creation'];
    }
}
