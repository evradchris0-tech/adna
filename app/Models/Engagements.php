<?php

namespace App\Models;

use App\Models\Scopes\ModelScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engagements extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    protected $appends = ['avg_versement', 'res_dime', 'res_cotisation', 'res_dette_dime', 'res_dette_cotisation'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new ModelScope);
    }

    public function getAvgVersementAttribute()
    {

        $total = $this->attributes['dime']+$this->attributes['cotisation']+$this->attributes['dette_dime']+$this->attributes['dette_cotisation'];
        $reste = $this->attributes['available_dime']+$this->attributes['available_cotisation']+$this->attributes['available_dette_cotisation']+$this->attributes['available_dette_dime'];

        return [
            "solde" => $reste,
            "reste" => $total-$reste,
            "total" => $total
        ];
    }

    public function getResDimeAttribute(){
        return $this->attributes['dime'] - $this->attributes['available_dime'];
    }
    public function getResDetteDimeAttribute(){
        return $this->attributes['dette_dime'] - $this->attributes['available_dette_dime'];
    }
    public function getResDetteCotisationAttribute(){
        return $this->attributes['dette_cotisation'] - $this->attributes['available_dette_cotisation'];
    }
    public function getResCotisationAttribute(){
        return $this->attributes['cotisation'] - $this->attributes['available_cotisation'];
    }

    public function association()
    {
        return $this->belongsTo(Associations::class, 'associations_id');
    }
    public function versements()
    {
        return $this->hasMany(Versements::class, 'engagement_id');
    }

    public function paroissien()
    {
        return $this->belongsTo(Paroissien::class, 'paroissiens_id');
    }

    public static function newEngagement(array $data){
        try {

            // check if the paroissien exist
            $p = Paroissien::where("old_matricule", $data['matricule_paroissien'])
                ->orWhere("new_matricule", $data['matricule_paroissien'])->get();

            if (count($p) !== 1) {
                return 0;
            }

            $periode_start = Carbon::parse($data['annee_engagement']);
            $engagemnts = Engagements::where("paroissiens_id", $p[0]->id)
            ->whereRAW('YEAR(periode_start) = ?', [$periode_start->year]);

            if (count($engagemnts) > 0) {
                return 0;
            }

            $start_date = date('Y-m-d', strtotime($periode_start));
            $end_date = date('Y-m-d', strtotime($periode_start->addYear(1)->subDay()));

            Engagements::create([
                "paroissiens_id" => $p[0]->id,
                "associations_id" => $p[0]->association->id,
                "periode_start" => $start_date,
                "periode_end" => $end_date,
                "dime" => $data["dime"],
                "offrande" => 0,
                "cotisation" => $data["construction"],
                "dette_dime" => 0,
                "dette_cotisation" => 0,
                "available_dime" => 0,
                "available_cotisation" => 0,
                "available_dette_cotisation" => 0,
                "available_dette_dime" => 0,
            ]);
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
