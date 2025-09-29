<?php

namespace App\Exports\csv;

use App\Models\Associations;

class AssociationsCsvExport extends BaseExcellExport
{
    public function headings():array{
        return ['id','nom','sigle','cumul des offrandes','date creation'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $d = $this->data->transform(
            function($row) {
                return [
                    "id" => $row->id,
                    "nom" => $row->name,
                    "sigle" => $row->sigle,
                    "sigle" => $row->std_min,
                    "date creation" => $row->created_at,
                ];
            }
        );

        return $d;
    }
}
