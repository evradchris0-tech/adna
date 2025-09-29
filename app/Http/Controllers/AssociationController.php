<?php

namespace App\Http\Controllers;

use App\Models\Associations;
use App\Models\PdfModel;
use Illuminate\Http\Request;

class AssociationController extends Controller
{
    //

    public function index(Request $request){
        return view('associations.associations', ['associations' => Associations::paginate(10),]);
    }

    public function downlodPdf(){
        $associations = Associations::all()->transform(function ($v) {
            return [
                "id" => $v->id,
                "nom" => $v->name,
                "sigle" => $v->sigle,
                "date de creation" => date_format($v->created_at, "d-m-Y"),
            ];
        })->toArray();
        $h = ["id","nom","sigle","date de creation"];


        return PdfModel::getPdf("Liste des associations",$h, $associations,"associations");

    }
}
