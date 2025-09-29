<?php

namespace App\Models;

use App\Models\Scopes\ModelScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Paroissien extends Model
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

    public function association()
    {
        return $this->belongsTo(Associations::class, 'association_id');
    }

    public function engagements()
    {
        return $this->hasMany(Engagements::class, 'paroissiens_id');
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class, 'paroissiens_id');
    }


    public function getMaritalStatusTextAttribute()
    {
        if ($this->attributes['marital_status'] == 'c') {
            return 'Celibataire';
        }
        if ($this->attributes['marital_status'] == 'm') {
            return 'Marié';
        }
        if ($this->attributes['marital_status'] == 'v') {
            return $this->attributes['genre'] == 'h' ? 'Veuf' : 'Veuve';
        }
        return 'non-renseigner';
    }
    public function getNewMatriculeAttribute()
    {
        return strtoupper($this->attributes['new_matricule']);
    }
    public function getOldMatriculeAttribute()
    {
        return strtoupper($this->attributes['old_matricule']);
    }

    public function getNameAttribute()
    {
        return $this->attributes['firstname']." ".$this->attributes['lastname'];
    }

    public static function getMatricule(){
        $lastNewMatricule = Paroissien::orderby('id','desc')->get(['new_matricule']);
        if (count($lastNewMatricule) == 0) {
            return "000-aa";
        }
        $matParts = explode('-',$lastNewMatricule[0]->new_matricule);
        $matParts[0] = intval($matParts[0]);
        $mat = "";
        if ($matParts[0] == 999) {
            $letter = [$matParts[1][0],$matParts[1][1]];
            $letter[0] = ord($letter[0]);
            $letter[1] = ord($letter[1]);
            $mat = "000"."-".(chr($letter[1]) == 'z' ? chr(++$letter[0]) : chr($letter[0])).(chr($letter[1]) == 'z' ? 'a' : chr(++$letter[1]));
        }else{
            $mat = Paroissien::add_fake_zero(++$matParts[0])."-".$matParts[1];
        }


        return $mat;
    }

    public function getPerformanceAttribute(){
        $engagement = Engagements::where("paroissiens_id", $this->attributes['id'])->orderBy("created_at", "DESC")->get();
        if (count($engagement) > 0) {
            $engagement = $engagement[0];
            $totalVerser = $engagement->available_dime + $engagement->available_cotisation + $engagement->available_dette;
            $total = $engagement->dime + $engagement->cotisation + $engagement->dette;
            $recu = [
                "dimeR" => $engagement->available_dime,
                "offrandeR" => $engagement->available_dime,
                "cotisationR" => $engagement->available_cotisation,
                "detteDimeR" => $engagement->available_dette_dime,
                "detteCotisationR" => $engagement->available_dette_cotisation,
            ];
            $data = [
                "dime" => $engagement->dime,
                "offrande" => $engagement->offrande,
                "cotisation" => $engagement->cotisation,
                "detteDime" => $engagement->dette_dime,
                "detteCotisation" => $engagement->dette_cotisation,
                "taux" => round(($totalVerser / ($total == 0 ? 1 : $total))*100,2),
                "tauxDime" => round(($recu["dimeR"] / ($engagement->dime == 0 ? 1 : $engagement->dime))*100,2),
                "tauxDetteDime" => round(($recu["detteDimeR"] / ($engagement->dette_dime == 0 ? 1 : $engagement->dette_dime))*100,2),
                "tauxDetteCotisation" => round(($recu["detteCotisationR"] / ($engagement->dette_cotisation == 0 ? 1 : $engagement->dette_cotisation))*100,2),
                "tauxCotisation" => round(($recu["cotisationR"] / ($engagement->cotisation == 0 ? 1 : $engagement->cotisation))*100,2),
                ...$recu
            ];
        }else{
            $data = [
                "dime" => 0,
                "offrande" => 0,
                "cotisation" => 0,
                "detteDime" => 0,
                "detteCotisation" => 0,
                "taux" => 0,
                "tauxDime" => 0,
                "tauxDetteDime" => 0,
                "tauxDetteCotisation" => 0,
                "tauxCotisation" => 0,
                "dimeR" => 0,
                "detteDimeR" => 0,
                "detteCotisationR" => 0,
                "offrandeR" => 0,
                "cotisationR" => 0,
            ];
        }
        return $data;
    }

    public function getCotisationsAttribute(){
        $cotisations = Cotisation::selectRaw("type, sum(somme) somme")
        ->where('paroissiens_id', $this->attributes['id'])
        ->groupBy('type')
        ->get()
        ->toArray();

        $data = [
            "general" => 0,
            "recolte" => 0,
            "autres recettes" => 0,
        ];
        foreach ($cotisations as $key) {
            $data["general"] += $key['somme'];
            if ($key['type'] == "recolte") {
                $data["recolte"] += $key['somme'];
            }else {
                $data["autres recettes"] += $key['somme'];
            }
        }
        return $data;
    }

    public static function add_fake_zero($val,$defaultZeroCount = 3){
        return sprintf('%0'.$defaultZeroCount.'s',$val);
    }

    public static function newParoissien(array $data){
        try {
            // check if the phone number, firstname&lastname and email are unique
            $u = Paroissien::where("firstname", $data['firstname'])
            ->where("lastname", $data['lastname'])->orWhere("phone", $data['phone'])
            ->orWhere("email", $data['email'])->get();

            // check if the association exist
            $a = Associations::where("name", $data['association'])->get();


            if (count($u) > 0 || count($a) == 0) {
                return 0;
            }

            if ($data["baptise_date"] == "" ) $data["baptise_date"] = null;
            if ($data["adhesion_date"] == "" ) $data["adhesion_date"] = null;
            if ($data["confirm_date"] == "" ) $data["confirm_date"] = null;
            if ($data["birthdate"] == "" ) $data["birthdate"] = null;

            Paroissien::create([
                "firstname" => $data["firstname"],
                "lastname" => $data["lastname"],
                "situation" => $data["situation"],
                "job" => $data["job"],
                "service_place" => $data["service_place"],
                "job_poste" => $data["job_poste"],
                "baptise_date" => $data["baptise_date"] == null ? $data["baptise_date"] : Carbon::parse($data["baptise_date"]),
                "adhesion_date" => $data["adhesion_date"] == null ? $data["adhesion_date"] : Carbon::parse($data["adhesion_date"]),
                "confirm_date" => $data["confirm_date"] == null ? $data["confirm_date"] : Carbon::parse($data["confirm_date"]),
                "new_matricule" => Paroissien::getMatricule(),
                "genre" => $data["genre"],
                "birthdate" => $data["birthdate"] == null ? $data["birthdate"]  : Carbon::parse($data["birthdate"]),
                "birthplace" => $data["birthplace"],
                "email" => $data["email"],
                "school_level" => $data["school_level"],
                "address" => $data["address"],
                "phone" => $data["phone"],
                "categorie" => $data["categorie"],
                "father_name" => $data["father_name"],
                "mother_name" => $data["mother_name"],
                "wife_or_husban_name" => $data["wife_or_husban_name"],
                "marital_status" => $data["marital_status"],
                "nb_children" => $data["nb_children"],
                "old_matricule" => $data["old_matricule"],
                "association_id" => $a[0]->id,
            ]);
            return 1;
        } catch (\Throwable $th) {
            dd($th);
            return 0;
        }
    }
}
