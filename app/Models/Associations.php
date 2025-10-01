<?php

/**
 * 📁 EMPLACEMENT: app/Models/Associations.php
 * 
 * Modèle Associations avec support multi-utilisateurs
 */

namespace App\Models;

use App\Models\Scopes\ModelScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Associations extends Model
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

    public function getStdMinAttribute()
    {
        $d = Offrande::where("association_id", $this->id)->orderBy('offrande_day', 'desc')->get();
        $v = count($d) == 0 ? 0 : $d[0]->somme;
        return $v == 0 ? "0 FCFA" : formatNumber($v) . " FCFA le ( " . $d[0]->offrande_day . ")";
    }

    public function paroissiens()
    {
        return $this->hasMany(Paroissien::class, 'association_id');
    }

    public function offrandes()
    {
        return $this->hasMany(Offrande::class, 'association_id');
    }

    public static function getPaiementStat($type, $istotal = false)
    {
        $associations = Associations::with('paroissiens')->get();
        $assoStat = [];
        foreach ($associations as $key => $asso) {
            $totalEngagement = [
                "total" => 0,
                "recu" => 0,
            ];
            $paroissiens = $asso->paroissiens;

            foreach ($paroissiens as $key => $paroi) {
                $engagement = $paroi->engagements;
                if (count($engagement) > 0) {
                    $engagement = $engagement[0];
                    if ($type == "dime") {
                        $totalEngagement['total'] = $totalEngagement['total'] + $engagement->dime;
                        $totalEngagement['recu'] = $totalEngagement['recu'] + $engagement->available_dime;
                    } elseif ($type == "dette_dime") {
                        $totalEngagement['total'] = $totalEngagement['total'] + $engagement->dette_dime;
                        $totalEngagement['recu'] = $totalEngagement['recu'] + $engagement->available_dette_dime;
                    } elseif ($type == "dette_cotisation") {
                        $totalEngagement['total'] = $totalEngagement['total'] + $engagement->dette_cotisation;
                        $totalEngagement['recu'] = $totalEngagement['recu'] + $engagement->available_dette_cotisation;
                    } else {
                        $totalEngagement['total'] = $totalEngagement['total'] + $engagement->cotisation;
                        $totalEngagement['recu'] = $totalEngagement['recu'] + $engagement->available_cotisation;
                    }
                }
            }

            array_push($assoStat, [
                "id" => $asso->id,
                "sigle" => $asso->sigle,
                "data" => $totalEngagement,
                "percent" => round(($totalEngagement['total'] == 0 ? 0 : $totalEngagement['recu'] / $totalEngagement['total']) * 100, 2)
            ]);
        }

        if ($istotal) {
            $total = 0;
            $recu = 0;
            foreach ($assoStat as $key => $asso) {
                $total += $asso['data']['total'];
                $recu += $asso['data']['recu'];
            }
            $total = $total == 0 ? 1 : $total;
            return [
                "percent" => round(($recu / $total) * 100, 2),
                "total" => $total,
                "solde" => $recu,
            ];
        }
        return $assoStat;
    }

    public function getPerformanceAttribute()
    {
        $paroissiens = Paroissien::with("engagements")->where('association_id', $this->attributes['id'])->get();
        $data = [
            "dime" => 0,
            "detteDime" => 0,
            "detteCotisation" => 0,
            "cotisation" => 0,
            "dimeR" => 0,
            "detteDimeR" => 0,
            "detteCotisationR" => 0,
            "cotisationR" => 0,
            "taux" => 0,
            "tauxDime" => 0,
            "tauxCotisation" => 0,
        ];

        foreach ($paroissiens as $paroissien) {
            $data["dime"] += $paroissien->performance['dime'];
            $data["detteCotisation"] += $paroissien->performance['detteCotisation'];
            $data["detteDime"] += $paroissien->performance['detteDime'];
            $data["cotisation"] += $paroissien->performance['cotisation'];
            $data["dimeR"] += $paroissien->performance['dimeR'];
            $data["detteDimeR"] += $paroissien->performance['detteDimeR'];
            $data["detteCotisationR"] += $paroissien->performance['detteCotisationR'];
            $data["cotisationR"] += $paroissien->performance['cotisationR'];
        }
        $total = $data["dime"] + $data["cotisation"] + $data["detteDime"] + $data["detteCotisation"];
        $data["taux"] = round(100 * ($data["dimeR"] + $data["cotisationR"] + $data["detteDimeR"] + $data["detteCotisationR"]) / ($total == 0 ? 1 : $total), 2);
        $data["tauxCotisation"] = round(100 * ($data["cotisationR"]) / ($data["cotisation"] == 0 ? 1 : $data["cotisation"]), 2);
        $data["tauxDime"] = round(100 * ($data["dimeR"]) / ($data["dime"] == 0 ? 1 : $data["dime"]), 2);
        $data["tauxDetteDimeR"] = round(100 * ($data["detteDimeR"]) / ($data["detteDime"] == 0 ? 1 : $data["detteDime"]), 2);
        $data["tauxDetteCotisationR"] = round(100 * ($data["detteCotisationR"]) / ($data["detteCotisation"] == 0 ? 1 : $data["detteCotisation"]), 2);

        return $data;
    }

    public static function newAssociation(array $data)
    {
        try {
            // check if the association exist
            $a = Associations::where("name", $data['nom'])->get();

            if (count($a) > 0) {
                return 0;
            }

            Associations::create([
                "name" => $data["nom"],
            ]);
            return 1;
        } catch (\Throwable $th) {
            dd($th);
            return 0;
        }
    }

    // ==========================================
    // 🆕 MÉTHODES MULTI-UTILISATEURS
    // ==========================================

    /**
     * Relations many-to-many avec les utilisateurs
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_associations',
            'association_id',
            'user_id'
        )
        ->withPivot('is_primary', 'role_in_association')
        ->withTimestamps();
    }

    /**
     * Obtenir les gestionnaires de l'association
     */
    public function managers()
    {
        return $this->belongsToMany(
            User::class,
            'user_associations',
            'association_id',
            'user_id'
        )
        ->withPivot('is_primary', 'role_in_association')
        ->whereHas('roles', function ($query) {
            $query->whereIn('name', ['gestionnaire', 'responsable_association']);
        })
        ->withTimestamps();
    }

    /**
     * Vérifier si un utilisateur a accès à cette association
     */
    public function hasUser($userId): bool
    {
        return $this->users()->where('user_id', $userId)->exists();
    }
}