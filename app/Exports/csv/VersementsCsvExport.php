<?php

namespace App\Exports\csv;

class VersementsCsvExport extends BaseExcellExport
{
    public function headings():array{
        return [
            "Matricule",
            "nom",
            "association",
            "situation",
            "categorie",
            "Dime recu",
            "Dime total",
            "Offrande construction recu",
            "Offrande construction total",
            "Dette dime reçu",
            "Dette dime total",
            "Dette construction reçu",
            "Dette construction total",
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $perf = $this->data->transform(function($row) {
            return [
                "Matricule" => $row->old_matricule,
                "nom" => $row->firstname." ".$row->lastname,
                "association" =>  $row->association->name,
                "situation" =>  $row->situation,
                "categorie" =>  $row->categorie,
                "Dime recu" =>  count($row->engagements) != 0 ? $row->engagements[0]->available_dime : 0,
                "Dime total" =>  count($row->engagements) != 0 ? $row->engagements[0]->dime : 0,
                "Offrande construction recu" =>  count($row->engagements) != 0 ? $row->engagements[0]->available_cotisation : 0,
                "Offrande construction total" =>  count($row->engagements) != 0 ? $row->engagements[0]->cotisation : 0,
                "Dette dime reçu" =>  count($row->engagements) != 0 ? $row->engagements[0]->dette_dime: 0,
                "Dette dime total" =>  count($row->engagements) != 0 ? $row->engagements[0]->available_dette_dime : 0,
                "Dette construction reçu" =>  count($row->engagements) != 0 ? $row->engagements[0]->available_dette_cotisation : 0,
                "Dette construction total" =>  count($row->engagements) != 0 ? $row->engagements[0]->dette_cotisation : 0,
            ];
        });
        return $perf;
    }
}
