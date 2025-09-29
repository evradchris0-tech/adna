<?php

namespace App\Livewire\Forms;

use App\Models\Gestionnaires;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Form;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GestionnaireForm extends Form
{
    #[Rule('required|exists:associations,id')]
    public $association_id = '';
    #[Rule('required|exists:roles,id')]
    public $role_id = '';
    #[Rule('required')]
    public $name = '';
    #[Rule('required')]
    public $address = '';
    #[Rule('required')]
    public $statut = '';
    #[Rule('sometimes|email')]
    public $email = '';
    #[Rule('required')]
    public $phone = '';
    #[Rule('sometimes|exists:gestionnaire,id')]
    public $id = "";

    public ?Gestionnaires $gestionnaireToUpdate;


    public function store()
    {
        $serealizeData = $this->serealizeData();
        $u = User::create([
            'phone' => $this->phone,
            'email' => $this->email,
            'firstname' => $this->name,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'password' => Hash::make('gestionnaire1234')
        ]);
        $u->assignRole([$serealizeData["role_id"]]);
        $serealizeData["user_id"] = $u->id;
        unset($serealizeData["email"]);
        Gestionnaires::create($serealizeData);
    }

    public function update()
    {
        $serealizeData = $this->serealizeData();
        $user = User::find($this->gestionnaireToUpdate->user_id);
        $this->gestionnaireToUpdate->update($serealizeData);
        $user->update([
            "phone" => $this->phone,
            "email" => $this->email
        ]);
    }

    public function serealizeData(){
        $this->validate();
        $data = $this->all();
        $up = [];
        $ue = [];
        if (in_array($this->id, ["",null]) || ($this->phone != $this->gestionnaireToUpdate->phone)) {
            $up = User::where("phone", $this->phone)->get();
        }
        if (in_array($this->id, ["",null]) || ($this->email != $this->gestionnaireToUpdate->user->email)) {
            $ue = User::where("email", $this->email)->get();
        }


        if (count($up) > 0) {
            throw ValidationException::withMessages([
                'gestionnaireForm.phone' => 'La valeur du champ téléphone est déjà utilisée.'
            ]);
        }
        if (count($ue) > 0) {
            if (in_array($this->id, ["",null]) || ($ue[0]->id != $this->gestionnaireToUpdate->user->id)) {
                throw ValidationException::withMessages([
                    'gestionnaireForm.email' => 'La valeur du champ email est déjà utilisée.'
                ]);
            }
        }

        unset($data["gestionnaireToUpdate"],$data["id"]);
        return $data;
    }

    public function setEngagement(Gestionnaires $gestionnaire)
    {
        $this->gestionnaireToUpdate = $gestionnaire;
        $this->id = $gestionnaire->id;
        $this->association_id = $gestionnaire->association_id;
        $this->role_id = $gestionnaire->role_id;
        $this->name = $gestionnaire->name;
        $this->address = $gestionnaire->address;
        $this->phone = $gestionnaire->phone;
        $this->statut = $gestionnaire->statut;
        $this->email = $gestionnaire->user->email;
    }
}
