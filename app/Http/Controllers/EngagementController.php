<?php

namespace App\Http\Controllers;

use App\Models\Associations;
use App\Models\Engagements;
use App\Models\Paroissien;
use App\Models\PdfModel;
use App\Models\Scopes\ModelScope;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EngagementController extends Controller
{
    public function index(Request $request){
        return view('engagements.engagements');
    }

    public function create(Request $request){
        $id = 0;
        return view('engagements.ajout', compact("id"));
    }
    public function updateView(Request $request, $id){
        return view('engagements.ajout', compact("id"));
    }

    public function downlodPdf()
    {
        $engagements = Engagements::with(["association","paroissien"])->get()->transform(function ($row) {
            return [
                "matricule" =>  $row->paroissien->old_matricule,
                "paroissien" =>  $row->paroissien->firstname." ".$row->paroissien->lastname,
                "association" =>  $row->association->name,
                "dime" =>  $row->dime,
                "Offrande construction" =>  $row->cotisation,
                "Categorie" =>  $row->paroissien->categorie,
                "Situation" =>  $row->paroissien->situation,
                "debut engagement" =>  $row->periode_start,
                "fin engagement" =>  $row->periode_end,
                "date de creation" => $row->created_at,
            ];
        })->toArray();
        $h = [
            "matricule",
            "paroissien",
            "association",
            "dime",
            "Offrande construction",
            "Categorie",
            "Situation",
            "debut engagement",
            "fin engagement",
            "date de creation"
        ];


        return PdfModel::getPdf("Liste des engagements", $h, $engagements, "engagements", true);
    }

    public function migrate(){
        if (session('year') == Carbon::parse(now())->year - 1 ) {
            $start_year_date = mktime(0,0,0,1,1,date("Y"));
            $periode_start = Carbon::parse($start_year_date);
            $start_date = date('Y-m-d', strtotime($periode_start));
            $end_date = date('Y-m-d', strtotime(Carbon::parse($start_year_date)->addYear(1)->subDay()));
            $engagements_pass_year = Engagements::whereRAW('YEAR(periode_start) = ?', [session('year')])->get();

            foreach ($engagements_pass_year as $eng) {
                $engagemnts = Engagements::withoutGlobalScope(ModelScope::class)->where("paroissiens_id", $eng->paroissiens_id)
                ->whereRAW('YEAR(periode_start) = ?', [$periode_start->year])->get();
                if (count($engagemnts) == 0) {
                    Engagements::create([
                        "paroissiens_id" => $eng->paroissiens_id,
                        "associations_id" => $eng->associations_id,
                        "periode_start" => $start_date,
                        "periode_end" => $end_date,
                        "dime" => 0,
                        "offrande" => 0,
                        "available_dime" => 0,
                        "available_dette_dime" => 0,
                        "available_dette_cotisation" => 0,
                        "available_cotisation" => 0,
                        "dette_dime" =>  $eng->res_dette_dime + $eng->res_dime,
                        "dette_cotisation" => $eng->res_dette_cotisation + $eng->res_cotisation,
                        "cotisation" => 0,
                    ]);
                }else{
                    $engagemnt = Engagements::withoutGlobalScope(ModelScope::class)->find($engagemnts[0]->id);
                    $engagemnt->dette_dime = $eng->res_dette_dime + $eng->res_dime;
                    $engagemnt->dette_cotisation = $eng->res_dette_cotisation + $eng->res_cotisation;
                    $engagemnt->save();
                }
            }
            session()->flash('message', "Engagement migrer vers ".Carbon::parse(now())->year." avec success !");
        }
        return redirect()->route('engagement.index');
    }
}
