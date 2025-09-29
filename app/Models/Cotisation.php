<?php

namespace App\Models;

use App\Models\Scopes\ModelScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new ModelScope);
    }

    public static function newCotisation(array $data){
        try {

            // check if the paroissien exist
            $p = Paroissien::where("new_matricule", $data['matricule_paroissien'])->get();

            if (count($p) !== 1) {
                return 0;
            }

            if (in_array($data["type"], ['recolte','offrande de construction'])) {
                return 0;
            }


            Cotisation::create([
                "paroissiens_id" => $p[0]->id,
                "somme" => $data["somme"],
                "type" => $data["type"],
                "for_year" => session('year', Carbon::parse(now())->year),
            ]);

            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
