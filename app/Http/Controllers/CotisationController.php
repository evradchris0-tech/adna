<?php

namespace App\Http\Controllers;

use App\Models\Paroissien;
use App\Models\PdfModel;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    public function index(Request $request){
        return view('cotisations.cotisations');
    }

    public function create(Request $request){
        $id = 0;
        return view('cotisations.ajout', compact("id"));
    }
    public function updateView(Request $request, $id){
        return view('cotisations.ajout', compact("id"));
    }

    public function show(Request $request, $id){
        return view('cotisations.show', compact("id"));
    }

    public function downlodPdf(Request $request){
        $title = "Liste des cotisation";
        $perf = Paroissien::with(['cotisations','association']);

        if ($request->query("id")) {
            $title = $title." pour un paroissien";
            $perf = $perf->where("id", $request->query("id"));
        }

        $perf = $perf->get()->transform(function($row) {
            return [
                "Matricule" => $row->old_matricule,
                "nom" => $row->firstname." ".$row->lastname,
                "association" =>  $row->association->name,
                "situation" =>  $row->situation,
                "categorie" =>  $row->categorie,
                "Total" =>  $row->cotisations["general"],
                "date de creation" => $row['created_at'],
            ];
        })->toArray();

        $h = [
            "Matricule",
            "nom",
            "association",
            "situation",
            "categorie",
            "Total",
            "date de creation"
        ];


        return PdfModel::getPdf($title,$h, $perf,"cotisations");

    }
}
