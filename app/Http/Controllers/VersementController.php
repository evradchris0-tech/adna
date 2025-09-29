<?php

namespace App\Http\Controllers;

use App\Models\Paroissien;
use App\Models\PdfModel;
use Illuminate\Http\Request;

class VersementController extends Controller
{
    public function index(Request $request){
        return view('versements.versements');
    }

    public function create(Request $request){
        $id = 0;
        return view('versements.ajout', compact("id"));
    }
    public function updateView(Request $request, $id){
        return view('versements.ajout', compact("id"));
    }

    public function show(Request $request, $id){
        return view('versements.show', compact("id"));
    }

    public function downlodPdf()
    {
        $paroissiens = Paroissien::with(['engagements','association'])->get()->transform(function ($row){
            return [
                "Matricule" =>  $row->old_matricule,
                "nom" =>  $row->firstname." ".$row->lastname,
                "association" =>  $row->association->name,
                "situation" =>  $row->situation,
                "categorie" =>  $row->categorie,
                "Total versé" =>  count($row->engagements) != 0 ? $row->engagements[0]->avg_versement['solde'] : 0,
                "Total restant" =>  count($row->engagements) != 0 ? $row->engagements[0]->avg_versement['reste'] : 0
            ];
        })->toArray();

        $h = [
            "Matricule",
            "nom",
            "association",
            "situation",
            "categorie",
            "Total versé",
            "Total restant"
        ];

        return PdfModel::getPdf("Liste des versements", $h, $paroissiens, "versements", true);
    }
}
