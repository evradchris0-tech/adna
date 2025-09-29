<?php

namespace App\Models;

use App\Models\Scopes\ModelScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versements extends Model
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


    public function engagement()
    {
        return $this->belongsTo(Engagements::class, 'engagement_id');
    }

    public function paroissien()
    {
        return $this->belongsTo(Paroissien::class, 'paroissiens_id');
    }

    public static function newVersement(array $data){
        try {

            // check if the paroissien exist
            $p = Paroissien::where("new_matricule", $data['matricule_paroissien'])->get();

            if (count($p) !== 1) {
                return 0;
            }

            $e = Engagements::where("id", $data['engagement_id'])
            ->where("paroissiens_id", $p[0]->id)->get();

            if (count($e) == 0) {
                return 0;
            }

            if (in_array($data["type"], ['dime','cotisation', 'dette_cotisation', "dette_dime"])) {
                return 0;
            }


            Versements::create([
                "paroissiens_id" => $p[0]->id,
                "engagement_id" => $e[0]->id,
                "somme" => $data["somme"],
                "type" => $data["type"],
            ]);
            if ($data["type"] == 'dime') {
                $e->available_dime = $e->available_dime + $data["somme"];
            } else if($data["type"] == 'dette_cotisation') {
                $e->available_dette_cotisation = $e->available_dette_cotisation + $data["somme"];
            }else if($data["type"] == 'dette_dime') {
                $e->available_dette_dime = $e->available_dette_dime + $data["somme"];
            }else{
                $e->available_cotisation = $e->available_cotisation + $data["somme"];
            }
            $e->save();

            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
