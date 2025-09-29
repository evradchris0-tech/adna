<?php

namespace App\Exports\csv;

use App\Exports\csv\BaseExcellExport;

class EngagementsCsvExport extends BaseExcellExport
{
    public function headings():array{
        return [
            "matricule",
            "paroissien",
            "association",
            "dime total",
            "Offrande construction total",
            "dette dime total",
            "dette construction total",
            "dime versé",
            "Offrande construction versé",
            "dette dime versé",
            "dette construction versé",
            "Categorie",
            "Situation",
            "debut engagement",
            "fin engagement",
            "date de creation"
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $engagements = $this->data->transform(function($row) {
            return [
                "matricule" => $row['paroissien']->old_matricule,
                "paroissien" => $row['paroissien']->firstname." ".$row['paroissien']->lastname,
                "association" => $row['association']->name,
                "dime total" => $row['dime'],
                "Offrande construction total" => $row['cotisation'],
                "dette dime total" => $row['dette_dime'],
                "dette construction total" => $row['dette_cotisation'],
                "dime versé" => $row['available_dime'],
                "Offrande construction versé" => $row['available_cotisation'],
                "dette dime versé" => $row['available_dette_dime'],
                "dette construction versé" => $row['available_dette_cotisation'],
                "Categorie" => $row['paroissien']->categorie,
                "Situation" => $row['paroissien']->situation,
                "debut engagement" => $row['periode_start'],
                "fin engagement" => $row['periode_end'],
                "date de creation" => $row['created_at'],
            ];
        });

        return $engagements;
    }
}
