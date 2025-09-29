<?php

namespace App\Livewire\Forms;

use App\Models\Engagements;
use App\Models\Paroissien;
use Livewire\Attributes\Rule;
use Livewire\Form;

class EngagementForm extends Form
{
    #[Rule('required|exists:paroissiens,id')]
    public $paroissiens_id = '';
    #[Rule('required|date')]
    public $periode_start = '';
    #[Rule('required|date')]
    public $periode_end = '';
    #[Rule('required|numeric')]
    public $dime = 0;
    #[Rule('required|numeric')]
    public $offrande = 0;
    #[Rule('numeric')]
    public $cotisation = 0;
    #[Rule('numeric')]
    public $dette_dime = 0;
    #[Rule('numeric')]
    public $available_dette_dime = 0;
    public $dette_cotisation = 0;
    #[Rule('numeric')]
    public $available_dette_cotisation = 0;
    #[Rule('numeric')]
    public $available_dime = 0;
    #[Rule('numeric')]
    public $available_cotisation = 0;

    public ?Engagements $engagementToUpdate;


    public function store()
    {
        Engagements::create($this->serealizeData());
    }

    public function update()
    {
        $this->engagementToUpdate->update($this->serealizeData());
    }

    public function serealizeData(){
        $this->validate();
        $data = $this->all();

        $paroissien = Paroissien::find($data["paroissiens_id"]);
        $data["associations_id"] = $paroissien->association_id;
        unset($data["engagementToUpdate"]);

        return $data;
    }

    public function setEngagement(Engagements $engagement)
    {
        $this->engagementToUpdate = $engagement;
        $this->paroissiens_id = $engagement->paroissiens_id;
        $this->periode_start = $engagement->periode_start;
        $this->periode_end = $engagement->periode_end;
        $this->dime = $engagement->dime;
        $this->offrande = $engagement->offrande;
        $this->cotisation = $engagement->cotisation;
        $this->dette_dime = $engagement->dette_dime;
        $this->available_dette_dime = $engagement->available_dette_dime;
        $this->dette_cotisation = $engagement->dette_cotisation;
        $this->available_dette_cotisation = $engagement->available_dette_cotisation;
        $this->available_dime = $engagement->available_dime;
        $this->available_cotisation = $engagement->available_cotisation;
    }
}
