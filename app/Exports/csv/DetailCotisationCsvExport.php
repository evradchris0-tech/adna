<?php
namespace App\Exports\csv;

use App\Exports\csv\BaseExcellExport;

class DetailCotisationCsvExport extends BaseExcellExport{
    public function headings():array{
        return [
            "id",
            "Type",
            "somme",
            "date versement",
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $cotisations = $this->data->transform(function($row) {
            return [
                "id" => $row->id,
                "Type" => $row->type,
                "somme" =>  $row->somme,
                "date versement" =>  $row->created_at,
            ];
        });

        return $cotisations;
    }
}
