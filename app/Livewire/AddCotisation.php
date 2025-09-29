<?php

namespace App\Livewire;

use App\Livewire\Forms\CotisationForm;
use App\Models\Cotisation;
use App\Models\Paroissien;
use Livewire\Component;

class AddCotisation extends Component
{
    public $montant = 0;
    public $montantVerse = 0;
    public int $id = 0;
    public $isLoadingVersement = false;
    public $showConfirm = false;
    public $engagements = [];
    public CotisationForm $cotisationForm;
    public $paroissien = 0;
    protected $listeners = ['paroisienSelected'];


    public function paroisienSelected($id){
        $this->cotisationForm->paroissiens_id = $id;
        $this->paroissien = $id;
    }


    public function mount(int $id = 0){
        if ($id != 0) {
            $this->id = $id;
            $cotisation = Cotisation::find($this->id);
            $this->cotisationForm->setVersement($cotisation);
            $this->paroisienSelected($this->cotisationForm->paroissiens_id);
        }
    }

    public function onSubmit()
    {
        $this->isLoadingVersement = true;
        ($this->id && $this->id != 0) ? $this->cotisationForm->update() : $this->cotisationForm->store();
        $this->isLoadingVersement = false;
        session()->flash('message',($this->id && $this->id != 0) ? "cotisation modifiée avec success!" : "nouvelle cotisation ajoutée!");
        return redirect()->route('cotisations.index');
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
        return view('cotisations.livewire.add-cotisation', compact('paroissiens'));
    }
}
