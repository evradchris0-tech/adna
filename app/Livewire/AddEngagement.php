<?php

namespace App\Livewire;

use App\Livewire\Forms\EngagementForm;
use App\Models\Associations;
use App\Models\Engagements;
use App\Models\Paroissien;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class AddEngagement extends Component
{
    public $current = 0;
    public int $id = 0;
    public int $paroissien = 0;
    public bool $showConfirm = false;
    public bool $isLoading = false;
    protected $listeners = ['paroisienSelected'];
    public EngagementForm $engagementForm;


    public function mount(int $id = 0){
        if ($id || $id != 0) {
            $this->id = $id;
            $engagement = Engagements::find($this->id);
            $this->paroissien = $engagement->paroissien->id;
            $this->engagementForm->setEngagement($engagement);
        }
    }

    public function paroisienSelected($id){
        $this->engagementForm->paroissiens_id = $id;
        $this->paroissien = $id;
    }

    public function onShowConfirmModal($id=null){
        if ($this->isLoading) {
            return;
        }
        $this->showConfirm = !$this->showConfirm;
    }

    public function onSubmit()
    {
        if ($this->isLoading) {
            return;
        }
        $this->isLoading = true;
        $isUpdate = $this->id && $this->id != 0;
        $this->validateDate();

        if ($isUpdate) {
            $this->engagementForm->update();
        }else{
            $this->engagementForm->store();
        }
        session()->flash('message', ($isUpdate) ? "engagement modifié avec success!" : "nouvelle engagement ajouté!");
        $this->isLoading = false;
        return $this->redirect('/engagements');
    }
    public function render()
    {
        $paroissiens = Paroissien::all();
        return view('engagements.livewire.add-engagement', compact('paroissiens'));
    }

    public function validateDate(){
        $periode_start = Carbon::parse($this->engagementForm->periode_start);
        $engagements = Engagements::where("paroissiens_id", $this->paroissien)
        ->whereRAW('YEAR(periode_start) =?', [$periode_start->year])->get();
        if (count($engagements) > 0) {
            $p = Carbon::parse($engagements[0]->periode_start);
            if ($this->id == 0 || $p->year != $periode_start->year) {
                $this->isLoading = false;
                throw ValidationException::withMessages(['engagementForm.periode_start' => "il y'a deja un engagement pour ce paroissien en ".$periode_start->year]);
            }
        }

    }

    public function loadEndDate(){
        $date = Carbon::parse($this->engagementForm->periode_start);
        $this->engagementForm->periode_end = date('Y-m-d', strtotime($date->addYear(1)->subDay()));
    }

}
