<?php

namespace App\Livewire;

use App\Livewire\Forms\ParoissienForm;
use App\Models\Associations;
use App\Models\Paroissien;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class AddParoissien extends Component
{
    public $current = 0;
    public int $id = 0;
    public bool $isProfessional = false;
    public bool $showConfirm = false;
    public bool $isLoading = false;
    public ParoissienForm $paroissien;


    public function mount(int $id = 0){
        if ($id || $id != 0) {
            $this->id = $id;
            $paroissien = Paroissien::find($this->id);
            $this->paroissien->setParoissien($paroissien);
        }
    }

    public function goForward()
    {
        $this->checkStep($this->current);
        if ($this->current == 2) {
            return ;
        }
        return $this->current++;
    }
    public function backForward()
    {
        if ($this->current == 0) {
            return ;
        }
        return $this->current--;
    }

    public function onShowConfirmModal($id=null){
        if ($this->isLoading) {
            return;
        }
        $this->showConfirm = !$this->showConfirm;
    }
    public function onSubmit()
    {
        $this->isLoading = true;
        if ($this->id && $this->id != 0) {
            $this->paroissien->update();
            $this->isLoading = false;
            session()->flash('message',"paroissien modifié avec success!");
            return $this->redirect("/paroissiens/".$this->id);
        }
        $this->paroissien->store();
        $this->isLoading = false;
        session()->flash('message',"nouveau paroissien ajouté!");
        return $this->redirect('/paroissiens');
    }
    public function render()
    {
        $associations = Associations::all();
        return view('paroissiens.livewire.add-paroissien', compact('associations'));
    }

    public function checkStep($stepNum){
        if ($stepNum == 0) {

            $this->validate([
                "paroissien.firstname" => "required",
                "paroissien.lastname" => "required",
                "paroissien.genre" => "required",
                "paroissien.birthdate" => "required",
                "paroissien.birthplace" => "required",
                "paroissien.address" => "required",
                "paroissien.phone" => "required|unique:paroissiens,phone,".$this->id,
                "paroissien.school_level" => "required",
                "paroissien.email" => "nullable|unique:paroissiens,email,".$this->id,
            ]);

            $p = Paroissien::where("firstname", $this->paroissien->firstname)
            ->where("lastname", $this->paroissien->lastname);

            if ($this->id) {
                $p = $p->where('id','!=',$this->id);
            }
            $p = $p->get();

            if (count($p) > 0) {
                throw ValidationException::withMessages([
                    'paroissien.firstname' => 'La combinaison nom prenom est deja utilisé !',
                    'paroissien.lastname' => 'La combinaison nom prenom est deja utilisé !'
                ]);
            }
        }elseif ($stepNum == 1) {
            $this->validate([
                "paroissien.old_matricule" => "required|unique:paroissiens,old_matricule,".$this->id,
                // "paroissien.new_matricule" => "required|unique:paroissiens,new_matricule",
                "paroissien.association_id" => "required",
                "paroissien.categorie" => "required",
                "paroissien.baptise_date" => "nullable",
                "paroissien.confirm_date" => "nullable",
                "paroissien.adhesion_date" => "nullable",
            ]);
        }
    }


}
