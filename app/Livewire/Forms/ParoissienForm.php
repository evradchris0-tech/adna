<?php

namespace App\Livewire\Forms;

use App\Models\Paroissien;
use Carbon\Carbon;
use DateTime;
use Livewire\Attributes\Rule;
use Livewire\Form;
use Nette\Utils\Random;

class ParoissienForm extends Form
{
    #[Rule('required')]
    public $firstname = '';
    #[Rule('required')]
    public $lastname = '';
    #[Rule('required')]
    public $genre = '';
    #[Rule('required')]
    public $birthdate = '';
    #[Rule('required')]
    public $birthplace = '';
    #[Rule('nullable')]
    public $email = '';
    #[Rule('nullable')]
    public $school_level = '';
    #[Rule('required')]
    public $address = '';
    #[Rule('required')]
    public $phone = '';
    #[Rule('nullable')]
    public $old_matricule = '';
    #[Rule('nullable')]
    public $new_matricule = '';
    #[Rule('required|exists:associations,id')]
    public $association_id = '';
    #[Rule('required')]
    public $categorie = '';
    #[Rule('nullable')]
    public $confirm_date = null;
    #[Rule('nullable')]
    public $adhesion_date = null;
    #[Rule('nullable')]
    public $baptise_date = null;
    #[Rule('required|string')]
    public $father_name = '';
    #[Rule('required|string')]
    public $mother_name = '';
    #[Rule('nullable')]
    public $wife_or_husban_name = '';
    #[Rule('required|string|min:1|max:1')]
    public $marital_status = '';
    #[Rule('required|integer')]
    public $nb_children = 0;
    #[Rule('required')]
    public $situation = '';
    #[Rule('required_if:situation,Employer')]
    public $service_place = '';
    #[Rule('required_if:situation,Employer')]
    public $job = '';
    #[Rule('required_if:situation,Employer')]
    public $job_poste = '';

    public ?Paroissien $paroissienToUpdate;


    public function store()
    {
        Paroissien::create($this->serealizeData());
    }

    public function update()
    {
        $this->paroissienToUpdate->update($this->serealizeData());
    }

    public function serealizeData(){
        $this->validate();
        $data = $this->all();
        $data["adhesion_date"] = null;
        $data["confirm_date"] = null;
        if ($data["baptise_date"] != "" ) {
            $data["baptise_date"] = Carbon::parse($data["baptise_date"]);
        }else{
            unset($data["baptise_date"]);
        }
        if ($data["adhesion_date"] != "" ) {
            $data["adhesion_date"] = Carbon::parse($data["adhesion_date"]);
        }else{
            unset($data["adhesion_date"]);
        }
        if ($data["confirm_date"] != "" ) {
            $data["confirm_date"] = Carbon::parse($data["confirm_date"]);
        }else{
            unset($data["confirm_date"]);
        }
        if ($data["paroissienToUpdate"] == null) {
            $data["new_matricule"] = Paroissien::getMatricule();
        }
        unset($data["paroissienToUpdate"]);
        if ($data["situation"] !== "Employer") {
            $data["job"] = "";
            $data["service_place"] = "";
            $data["job_poste"] = "";
        }

        return $data;
    }

    public function setParoissien(Paroissien $paroissien)
    {
        $this->paroissienToUpdate = $paroissien;
        $this->firstname = $paroissien->firstname;
        $this->lastname = $paroissien->lastname;
        $this->genre = $paroissien->genre;
        $this->birthdate = $paroissien->birthdate;
        $this->birthplace = $paroissien->birthplace;
        $this->email = $paroissien->email;
        $this->school_level = $paroissien->school_level;
        $this->address = $paroissien->address;
        $this->phone = $paroissien->phone;
        $this->old_matricule = $paroissien->old_matricule;
        $this->new_matricule = $paroissien->new_matricule;
        $this->association_id = $paroissien->association_id;
        $this->categorie = $paroissien->categorie;
        $this->confirm_date = $paroissien->confirm_date;
        $this->adhesion_date = $paroissien->adhesion_date;
        $this->baptise_date = $paroissien->baptise_date;
        $this->father_name = $paroissien->father_name;
        $this->mother_name = $paroissien->mother_name;
        $this->wife_or_husban_name = $paroissien->wife_or_husban_name;
        $this->marital_status = $paroissien->marital_status;
        $this->nb_children = $paroissien->nb_children;
        $this->job = $paroissien->job;
        $this->job_poste = $paroissien->job_poste;
        $this->situation = $paroissien->situation;
        $this->service_place = $paroissien->service_place;
    }




}
