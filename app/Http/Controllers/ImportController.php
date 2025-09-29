<?php

namespace App\Http\Controllers;

use App\Models\Associations;
use App\Models\Cotisation;
use App\Models\Engagements;
use App\Models\FileStorage;
use App\Models\Gestionnaires;
use App\Models\Paroissien;
use App\Models\Versements;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index(Request $request){

        $data = $request->validate([
            "file" => "required|file",
            "model" => "required|string",
        ]);

        $res = FileStorage::csvToArray($data['file'], ";");

        $c = 0;
        if ($data['model'] == "gestionnaires") {
            foreach ($res as $dt) {
                if(Gestionnaires::newGestionnaire($dt) == 0) $c++;
            }
        }
        if ($data['model'] == "paroissiens") {
            foreach ($res as $dt) {
                if(Paroissien::newParoissien($dt) == 0) $c++;
            }
        }
        if ($data['model'] == "associations") {
            foreach ($res as $dt) {
                if(Associations::newAssociation($dt) == 0) $c++;
            }
        }
        if ($data['model'] == "engagements") {
            foreach ($res as $dt) {
                if(Engagements::newEngagement($dt) == 0) $c++;
            }
        }
        if ($data['model'] == "cotisations") {
            foreach ($res as $dt) {
                if(Cotisation::newCotisation($dt) == 0) $c++;
            }
        }
        if ($data['model'] == "versements") {
            foreach ($res as $dt) {
                if(Versements::newVersement($dt) == 0) $c++;
            }
        }
        if ($c != 0) {
            if (count($res) == $c) {
                return back()->with('error', "Aucun éléments enregistré verifiez vos données !");
            }
            return back()->with('message', "certains elements ne sont pas enregistrés !");
        }
        return back()->with('message', "Elements  enregistrés !");
    }
}
