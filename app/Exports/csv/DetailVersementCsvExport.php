<?php

namespace App\Exports\csv;


class DetailVersementCsvExport extends BaseExcellExport
{
    public function headings():array{
        return [
            "N°",
            "Type",
            "Somme",
            "Date versement",
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $perf = $this->data->transform(function($row) {
            return [
                "N°" => $row->id,
                "Type" => $row->type,
                "Somme" =>  $row->somme,
                "Date versement" =>  $row->created_at,
            ];
        });
        return $perf;
    }
}
