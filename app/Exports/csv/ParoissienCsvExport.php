<?php

namespace App\Exports\csv;

use App\Exports\csv\BaseExcellExport;

class ParoissienCsvExport extends BaseExcellExport
{

    public function headings():array{
        return [
            "id",
            "nom",
            "prenom",
            "genre",
            "date de naissance",
            "lieu de naissance",
            "email",
            "niveu d'etude",
            "address",
            "numero de téléphone",
            "ancien matricule",
            "nouveau matricule",
            "association",
            "categorie",
            "situation",
            "date de confirmation",
            "date d'adhesion",
            "date de baptem",
            "nom du pere",
            "nom de la mere",
            "nom epoux(se)",
            "status marital",
            "nombre d'enfant",
            "employeur",
            "poste",
            "lieu de service",
            "date de creation"
        ];
    }

    public function collection(){
        $d = $this->data->transform(
            function($row) {
                return [
                    "id" => $row->id,
                    "nom" => $row->firstname,
                    "prenom" => $row->lastname,
                    "genre" => $row->genre == "h" ? "homme" : "femme",
                    "date de naissance" => $row->birthdate,
                    "lieu de naissance" => $row->birthplace,
                    "email" => $row->email,
                    "niveu d'etude" => $row->school_level,
                    "address" => $row->address,
                    "numero de téléphone" => $row->phone,
                    "ancien matricule" => $row->old_matricule,
                    "nouveau matricule" => $row->new_matricule,
                    "association" => $row->association->name,
                    "categorie" => $row->categorie,
                    "situation" => $row->situation,
                    "date de confirmation" => $row->confirm_date,
                    "date d'adhesion" => $row->adhesion_date,
                    "date de baptem" => $row->baptise_date,
                    "nom du pere" => $row->father_name,
                    "nom de la mere" => $row->mother_date,
                    "nom epoux(se)" => $row->wife_or_hasban_name,
                    "status marital" => $row->marital_status,
                    "nombre d'enfant" => $row->nb_children,
                    "employeur" => $row->job,
                    "poste" => $row->job_poste,
                    "lieu de service" => $row->service_place,
                    "date de creation" => $row->created_at
                ];
            }
        );

        return $d;
    }
}
