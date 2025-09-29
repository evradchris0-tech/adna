<?php

namespace App\Console\Commands;

use App\Models\Engagements;
use App\Models\Paroissien;
use App\Models\Scopes\ModelScope;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FillNewEngagementField extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'engagement:dette {year} {passYear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charger les datas dans les nouveaux champs de la table engagement !';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (+$this->argument('year') < +$this->argument('passYear')) {
            $this->error('year must be upper than the past year!');
            return;
        }
        if (+$this->argument('year') - +$this->argument('passYear') != 1) {
            $this->error('The difference between the 2 years must be 1 !');
            return;
        }
        if (+$this->argument('year') > session('year', Carbon::parse(now())->year)) {
            $this->error('The year must be less than '.session('year', Carbon::parse(now())->year).' !');
            return;
        }
        $paroissiens = Paroissien::all();

        foreach ($paroissiens as $paroissien) {
            $current_engagemnts = Engagements::withoutGlobalScope(ModelScope::class)->where("paroissiens_id", $paroissien->id)
            ->whereRaw('YEAR(periode_start) = ?', [$this->argument('year')])->get();
            $pass_engagemnts = Engagements::withoutGlobalScope(ModelScope::class)->where("paroissiens_id", $paroissien->id)
            ->whereRaw('YEAR(periode_start) = ?', [$this->argument('passYear')])->get();

            if (count($current_engagemnts) > 0) {
                $versements = $current_engagemnts[0]->versements()->get();
                $d = [
                    "dette_dime" => 0,
                    "dette_cotisation" => 0,
                    "dime" => 0,
                    "cotisation" => 0,
                ];
                foreach ($versements as $versement) {
                    if ($versement->type == "dime") {
                        $d["dime"] = $d["dime"] + $versement->somme;
                    }elseif ($versement->type == "dette_dime") {
                        $d["dette_dime"] = $d["dette_dime"] + $versement->somme;
                    }elseif ($versement->type == "dette_cotisation") {
                        $d["dette_cotisation"] = $d["dette_cotisation"] + $versement->somme;
                    }elseif ($versement->type == "Offrande de construction") {
                        $d["cotisation"] = $d["cotisation"] + $versement->somme;
                    }
                }
                $current_engagemnts[0]->update([
                    "available_dime" => $d["dime"],
                    "available_dette_dime" => $d["dette_dime"],
                    "available_dette_cotisation" => $d["dette_cotisation"],
                    "available_cotisation" => $d["cotisation"],
                    "dette_cotisation" => count($pass_engagemnts) == 0 ? 0: ($pass_engagemnts[0]->res_dette_cotisation + $pass_engagemnts[0]->res_cotisation),
                    "dette_dime" => count($pass_engagemnts) == 0 ? 0: ($pass_engagemnts[0]->res_dette_dime + $pass_engagemnts[0]->res_dime),
                ]);
            }
        }
        $this->info('dette, availlable_dette, availlable_cotisation, availlable_dime data added successfully !');
    }
}
