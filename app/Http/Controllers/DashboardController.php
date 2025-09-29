<?php

namespace App\Http\Controllers;

use App\Models\Associations;
use App\Models\Paroissien;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request){
        // stat paroissiens
        $stat = $this->getParoissienStat();
        $nbParoissien = $stat["nbParoissien"];
        $nbMen = $stat["nbMen"];
        $nbWife = $stat["nbWife"];
        $nbAncien = $stat["nbAncien"];
        $nbDiacre = $stat["nbDiacre"];
        $reste = $stat["reste"];
        // stat associations
        $statAssociation = Associations::with('paroissiens')->get();
        $assoDimeStat = Associations::getPaiementStat("dime", true);
        $assoConsStat = Associations::getPaiementStat("Offrande de construction", true);

        return view('dashboard', compact("nbParoissien","nbMen","nbWife","nbAncien","nbDiacre","reste",'statAssociation',"assoDimeStat","assoConsStat"));
    }



    public function getParoissienStat(){
        $nbParoissiens = Paroissien::selectRaw("count(*) as nb,genre")->groupBy('genre')->get();
        $nbParoissien = 0;
        $nbMen = 0;
        $nbWife = 0;
        $nbAncien = Paroissien::where("categorie", "ancien")->count();
        $nbDiacre = Paroissien::where("categorie", "diacre")->count();
        $reste = 0;
        if (count($nbParoissiens) == 1) {
            $nbParoissien = $nbParoissiens[0]->nb;
            $nbMen = $nbParoissiens[0]->nb;
            $nbWife = $nbParoissiens[0]->genre == "f" ? $nbParoissiens[0]->nb : 0;
            $nbMen = $nbParoissiens[0]->genre == "h" ? $nbParoissiens[0]->nb : 0;
        }
        if (count($nbParoissiens) == 2) {
            $nbParoissien = $nbParoissiens[0]->nb + $nbParoissiens[1]->nb;
            $nbMen = $nbParoissiens[0]->nb;
            $nbWife = $nbParoissiens[0]->nb;
            $nbMen = $nbParoissiens[1]->nb;
        }
        $reste = $nbParoissien - ($nbDiacre+$nbAncien);

        return [
            "nbParoissien" => $nbParoissien,
            "nbMen" => $nbMen,
            "nbWife" => $nbWife,
            "nbAncien" => $nbAncien,
            "nbDiacre" => $nbDiacre,
            "reste" => $reste,
        ];
    }
}
