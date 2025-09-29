<?php

namespace App\Livewire;

use App\Livewire\Forms\VersementForm;
use App\Models\Engagements;
use App\Models\Paroissien;
use App\Models\Versements;
use Livewire\Component;

class AddVersement extends Component
{
    public $montant = 0;
    public $dette_dime = 0;
    public $dette_cotisation = 0;
    public $dette_dime_verser = 0;
    public $dette_cotisation_verser = 0;
    public $montantVerse = 0;
    public int $id = 0;
    public $isLoadingVersement = false;
    public $showConfirm = false;
    public $engagements = [];
    public VersementForm $versementForm;
    public $paroissien = 0;
    protected $listeners = ['paroisienSelected'];


    public function paroisienSelected($id){
        if ($id) {
            $this->versementForm->paroissiens_id = $id;
            $this->paroissien = $id;
            $this->loadEngagement();
        }
    }


    public function mount(int $id = 0){
        if ($id != 0) {
            $this->id = $id;
            $versement = Versements::find($this->id);
            $this->versementForm->setVersement($versement);
            $this->paroisienSelected($this->versementForm->paroissiens_id);
            $this->loadSommeEngagement();
        }
    }

    public function onSubmit()
    {
        $this->isLoadingVersement = true;
        $isValid = ($this->id && $this->id != 0) ? $this->checkIfCan(true) : $this->checkIfCan(false);
        $this->isLoadingVersement = false;
        if (!$isValid) {
            session()->flash('error',"la somme est superieur à celle prevu pour l'engagement !");
            return;
        }
        if ($this->id && $this->id != 0) {
            $r = $this->versementForm->update();
        }else{
            $r = $this->versementForm->store();
        }
        if ($r) {
            session()->flash('message',($this->id && $this->id != 0) ? "versement modifié avec success!" : "nouveau versement ajouté!");
            return redirect()->route('versement.index');
        }else{
            session()->flash('error',"la somme est superieur à celle prevu pour l'engagement !");
        }
    }

    public function checkIfCan($isUpdate = false){
        $d = Engagements::with("versements")->find($this->versementForm->engagement_id);
        $dime = $d->dime;
        $dette_dime = $d->dette_dime;
        $dette_cotisation = $d->dette_cotisation;
        $construction = $d->cotisation;
        if ($isUpdate) {
            foreach ($d->versements as $key => $v) {
                if ($this->versementForm && $v->id != $this->id) {
                    if ($v->type == "Offrande de construction") {
                        $construction -= $v->somme;
                    }elseif ($v->type == "dime") {
                        $dime -= $v->somme;
                    }elseif ($v->type == "dette_dime") {
                        $dette_dime -= $v->somme;
                    }elseif ($v->type == "dette_cotisation") {
                        $dette_cotisation -= $v->somme;
                    }
                }
            }
        } else {
            $dime = $d->res_dime;
            $construction = $d->res_cotisation;
            $dette_dime = $d->res_dette_dime;
            $dette_cotisation = $d->res_dette_cotisation;
        }
        if (
            $this->versementForm->type == 'dime' && (+$this->versementForm->somme > +$dime)
            ||
            $this->versementForm->type == 'Offrande de construction' && (+$this->versementForm->somme > +$construction)
            ||
            $this->versementForm->type == 'dette_dime' && (+$this->versementForm->somme > +$dette_dime)
            ||
            $this->versementForm->type == 'dette_cotisation' && (+$this->versementForm->somme > +$dette_cotisation)
        ) {
            return false;
        }
        return true;
    }

    public function loadEngagement(){
        $this->isLoadingVersement = true;
        $this->engagements = Engagements::with(['paroissien'])->where("paroissiens_id",$this->versementForm->paroissiens_id)->get();
        $this->isLoadingVersement = false;
        $this->montantVerse = 0;
        $this->montant = 0;
        if ($this->id == 0 || null || "") {
            $this->versementForm->engagement_id = "";
        }
    }
    public function loadSommeEngagement(){
        if ($this->versementForm->engagement_id != "") {
            $engagement = Engagements::find(+$this->versementForm->engagement_id);

            $this->montant = ($engagement->dime + $engagement->offrande + $engagement->cotisation);

            $this->montantVerse = $engagement->available_dime + $engagement->available_cotisation;
            $this->dette_cotisation = $engagement->dette_cotisation;
            $this->dette_dime = $engagement->dette_dime;
            $this->dette_dime_verser = $engagement->available_dette_dime;
            $this->dette_cotisation_verser = $engagement->available_dette_cotisation;
        }else{
            $this->montantVerse = 0;
            $this->montant = 0;
        }
    }

    public function onShowConfirmModal($id=null){
        if ($this->isLoadingVersement) {
            return;
        }
        $this->showConfirm = !$this->showConfirm;
    }

    public function render()
    {
        $paroissiens = Paroissien::all();
        return view('versements.livewire.add-versement', compact('paroissiens'));
    }
}
