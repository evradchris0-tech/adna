<?php

namespace App\Http\Controllers;

use App\Models\Paroissien;
use App\Models\PdfModel;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index(Request $request){

        return view('performance.performance');
    }

    public function show(Request $request, $id){

        return view('performance.performance-detail', compact('id'));
    }
    public function show_global(Request $request){

        return view('performance.performance-global');
    }

    public function downlodPdf(Request $request)
    {
        $title = "Liste des performances";
        $performances = Paroissien::with(["association","engagements"]);

        if ($request->query("id")) {
            $title = $title." pour une association";
            $performances = $performances->whereHas('association',function ($query) use ($request){
                return $query->where('id', $request->query("id"));
            });
        }
        $performances = $performances->get()->transform(function ($row) {
            return [
                "matricule" =>  $row->new_matricule,
                "Nom & Prenom" =>  $row->firstname." ".$row->lastname,
                "Association" =>  $row->association->name,
                "Dime" =>  $row->performance["dimeR"] ." / ". $row->performance["dime"]." FCFA",
                "Offrande construction" =>  $row->performance["cotisationR"] ." / ". $row->performance["cotisation"]." FCFA",
                "Status" =>  $row->performance["taux"],
            ];
        })->toArray();
        $h = [
            "matricule",
            "Nom & Prenom",
            "Association",
            "Dime",
            "Offrande construction",
            "Status",
        ];


        return PdfModel::getPdf($title, $h, $performances, "performances", true);
    }
}
