<?php

namespace App\Exports\csv;


class GestionnairesCsvExport extends BaseExcellExport
{
    public function headings():array{
        return [
            "id",
            "nom et prenom",
            "association",
            "addresse",
            "téléphone",
            "status",
            "Ajouter le",
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $gestionnaires = $this->data->transform(function($row) {
            return [
                "id" => $row['id'],
                "nom et prenom" => $row['name'],
                "association" => $row['associations']->name,
                "addresse" => $row['address'],
                "téléphone" => $row['phone'],
                "status" => $row['statut'],
                "Ajouter le" => $row['created_at'],
            ];
        });

        return $gestionnaires;
    }
}
