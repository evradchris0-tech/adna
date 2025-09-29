<?php

namespace App\Http\Controllers;

use App\Models\Engagements;
use App\Models\Paroissien;
use App\Models\PdfModel;
use Illuminate\Http\Request;

class ParoissienController extends Controller
{
    public function index(Request $request)
    {

        return view('paroissiens.paroissiens');
    }

    public function create(Request $request)
    {
        $id = 0;
        return view('paroissiens.ajout', compact("id"));
    }
    public function show(Request $request, $id)
    {
        $paroissien = Paroissien::with(["association"])->find($id);
        $engagements = Engagements::with(["versements"])->where("paroissiens_id", $paroissien->id)->get();
        $engagementsData = [];
        $recu = [
            "dime" => 0,
            "cotisation" => 0,
            "detteDime" => 0,
            "detteCotisation" => 0,
        ];
        foreach ($engagements as $key => $engagement) {
            $total = $engagement->dime + $engagement->dette_dime + $engagement->dette_cotisation + $engagement->cotisation;
            $totalVerser = $engagement->available_dette_dime + $engagement->available_dette_cotisation + $engagement->available_cotisation+ $engagement->available_dime;
            $recu["dime"] += $engagement->available_dime;
            $recu["detteDime"] += $engagement->available_dette_dime;
            $recu["detteCotisation"] += $engagement->available_dette_cotisation;
            $recu["cotisation"] += $engagement->available_cotisation;

            $data = [
                "dime" => $engagement->dime,
                "cotisation" => $engagement->cotisation,
                "detteDime" => $engagement->dette_dime,
                "detteCotisation" => $engagement->dette_cotisation,
                "taux" => round(($totalVerser / ($total == 0 ? 1 : $total)) * 100, 2),
                "periode" => "Du " . date('d/m/Y', strtotime($engagement->periode_start)) . " Au " . date('d/m/Y', strtotime($engagement->periode_end)),
                "recu" => $recu
            ];
            array_push($engagementsData, $data);
        }
        return view('paroissiens.detail', compact("paroissien", "engagementsData"));
    }
    public function updateView(Request $request, $id)
    {
        return view('paroissiens.ajout', compact("id"));
    }



    public function downlodPdf()
    {
        $paroissiens = Paroissien::with(['association'])->get()->transform(function ($row) {
            return [
                "id" =>  $row->id,
                "matricule(old/new)" =>  $row->old_matricule . "/" . $row->new_matricule,
                "nom" =>  $row->name,
                "genre" =>  $row->genre == 'h' ? 'Homme' : 'Femme',
                "date de naissance" =>  $row->birthdate,
                "niveu d'etude" =>  $row->school_level,
                "address" =>  $row->address,
                "numero de téléphone" =>  $row->phone,
                "association" =>  $row->association->name,
                "categorie" =>  $row->categorie,
                "situation" =>  $row->situation,
                "date de creation" => date_format($row->created_at, "d-m-Y"),
            ];
        })->toArray();
        $h = [
            "id",
            "matricule(old/new)",
            "nom",
            "genre",
            "date de naissance",
            "niveu d'etude",
            "address",
            "numero de téléphone",
            "association",
            "categorie",
            "situation",
            "date de creation",
        ];


        return PdfModel::getPdf("Liste des paroissiens", $h, $paroissiens, "paroissiens", true);
    }
}
