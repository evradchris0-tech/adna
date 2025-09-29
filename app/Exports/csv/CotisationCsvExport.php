<?php

namespace App\Exports\csv;


class CotisationCsvExport extends BaseExcellExport
{

    public function headings():array{
        return [
            "Matricule",
            "nom",
            "association",
            "situation",
            "categorie",
            "Total",
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $cotisations = $this->data->transform(function($row) {
            return [
                "Matricule" => $row->old_matricule,
                "nom" => $row->firstname." ".$row->lastname,
                "association" =>  $row->association->name,
                "situation" =>  $row->situation,
                "categorie" =>  $row->categorie,
                "Total" =>  $row->cotisations["general"],
            ];
        });

        return $cotisations;
    }
}
