<?php
namespace App\Exports\pdf;
use App\Exports\pdf\BasePdfExport;

class GestionnairesPdfExport extends BasePdfExport{
    public function headings():array{
        return [
            "id",
            "nom et prenom",
            "association",
            "téléphone",
            "status",
            "Ajouter le",
        ];
    }
}
