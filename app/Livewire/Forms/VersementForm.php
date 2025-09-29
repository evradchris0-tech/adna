<?php

namespace App\Livewire\Forms;

use App\Models\Engagements;
use App\Models\Versements;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Rule;
use Livewire\Form;

class VersementForm extends Form
{
    #[Rule('required|exists:paroissiens,id')]
    public $paroissiens_id = '';
    #[Rule('exists:engagements,id')]
    public $engagement_id = '';
    #[Rule('required')]
    public $type = '';
    #[Rule('required|min:0')]
    public $somme = '';

    public ?Versements $versementToUpdate;


    public function store()
    {
        try {
            //code...
            $d = $this->serealizeData();
            Versements::create($d);
            $this->updateEngagement();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function updateEngagement($old_val = 0){
        $e = Engagements::where("id", $this->engagement_id)->get()[0];
        if ($this->type == 'dime') {
            $e->available_dime = $e->available_dime + $this->somme - $old_val;
        } elseif($this->type == 'dette_dime') {
            $e->available_dette_dime = $e->available_dette_dime + $this->somme - $old_val;
        } elseif($this->type == 'dette_cotisation') {
            $e->available_dette_cotisation = $e->available_dette_cotisation + $this->somme - $old_val;
        }else{
            $e->available_cotisation = $e->available_cotisation + $this->somme - $old_val;
        }
        $e->save();
    }


    public function update()
    {
        $d = $this->serealizeData();
        $this->updateEngagement($this->versementToUpdate->somme);
        $this->versementToUpdate->update($d);
        return true;
    }

    public function serealizeData()
    {
        $this->validate();
        $data = $this->all();
        if (in_array($data["engagement_id"], [0, "", null])) {
            throw ValidationException::withMessages(['versementForm.engagement_id' => "Il faut choisir un engagement!"]);
        }
        unset($data["versementToUpdate"]);
        return $data;
    }

    public function setVersement(Versements $versement)
    {
        $this->versementToUpdate = $versement;
        $this->paroissiens_id = $versement->paroissiens_id;
        $this->engagement_id = $versement->engagement_id;
        $this->type = $versement->type;
        $this->somme = $versement->somme;
    }
}
