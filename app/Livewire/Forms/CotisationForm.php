<?php

namespace App\Livewire\Forms;

use App\Models\Cotisation;
use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Form;

class CotisationForm extends Form
{
    #[Rule('required|exists:paroissiens,id')]
    public $paroissiens_id = '';
    #[Rule('required')]
    public $type = '';
    #[Rule('required|min:0')]
    public $somme = '';

    public ?Cotisation $cotisationToUpdate;


    public function store()
    {
        $d = $this->serealizeData();
        $d['for_year'] = session('year', Carbon::parse(now())->year);
        Cotisation::create($d);
        return true;
    }


    public function update()
    {
        $d = $this->serealizeData();
        $this->cotisationToUpdate->update($d);
        return true;
    }

    public function serealizeData(){
        $this->validate();
        $data = $this->all();
        unset($data["cotisationToUpdate"]);
        return $data;
    }

    public function setVersement(Cotisation $cotisation)
    {
        $this->cotisationToUpdate = $cotisation;
        $this->paroissiens_id = $cotisation->paroissiens_id;
        $this->type = $cotisation->type;
        $this->somme = $cotisation->somme;
    }
}
