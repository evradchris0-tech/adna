<?php

namespace App\Http\Controllers;

use App\Models\Gestionnaires;
use App\Models\PdfModel;
use Illuminate\Http\Request;

class GestionnaireController extends Controller
{
    public function index(Request $request){
        return view('gestionnaires.gestionnaires');
    }

    public function downlodPdf()
    {
        $gestionnaires = Gestionnaires::with(["associations","roles"])->get()->transform(function ($row) {
            return [
                "id" =>  $row['id'],
                "nom et prenom" =>  $row['name'],
                "association" =>  $row->associations->name,
                "role" =>  $row->roles->name,
                "addresse" =>  $row['address'],
                "téléphone" =>  $row['phone'],
                "status" =>  $row['statut'],
                "Ajouter le" => date_format($row['created_at'], "d-m-Y"),
            ];
        })->toArray();
        $h = [
            "id",
            "nom et prenom",
            "association",
            "role",
            "addresse",
            "téléphone",
            "status",
            "Ajouter le",
        ];

        return PdfModel::getPdf("Liste des gestionnaires", $h, $gestionnaires, "gestionnaires", true);
    }
}
