<?php

namespace App\Models;

use App\Models\Scopes\ModelScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class Gestionnaires extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    protected $table = "gestionnaire";

    public function associations()
    {
        return $this->belongsTo(Associations::class, 'association_id');
    }
    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function newGestionnaire(array $data){
        try {
            // check if the phone number, firstname&lastname and email are unique
            $u = User::where("firstname", $data['nom'])
            ->where("lastname", $data['prenom'])->orWhere("phone", $data['telephone'])
            ->orWhere("email", $data['email'])->get();

            // check if the association exist
            $a = Associations::where("name", $data['association'])->get();

            $r = Role::where('name', $data["role"])->get();

            if (count($u) > 0 || count($r) == 0 || count($a) == 0) {
                dd($u);
                return 0;
            }

            $u = User::create([
                'phone' => $data['telephone'],
                'email' => $data['email'],
                'firstname' => $data['nom'],
                'lastname' => $data['prenom'],
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'password' => Hash::make('gestionnaire1234')
            ]);

            $u->assignRole([$r[0]->id]);

            Gestionnaires::create([
                "user_id" => $u->id,
                "association_id" => $a[0]->id,
                "role_id" => $r[0]->id,
                "name" => $data["nom"]." ".$data["prenom"],
                "statut" => $data["status"],
                "address" => $data["address"],
                "phone" => $data["telephone"],
            ]);
            return 1;
        } catch (\Throwable $th) {
            dd($th);
            return 0;
        }
    }
}
