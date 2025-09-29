<?php

namespace App\Livewire;

use App\Exports\csv\GestionnairesCsvExport;
use App\Exports\pdf\GestionnairesPdfExport;
use App\Livewire\Forms\GestionnaireForm;
use App\Models\Associations;
use App\Models\Gestionnaires;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class GestionnaireTable extends Component
{
    use WithPagination;
    use WithFileUploads;


    public string $term = '';
    public string $orderField = "name";
    public string $orderDirection = "ASC";
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $isSending = false;
    public bool $isDeleting = false;
    public bool $showImportModal = false;
    public int $numPerPage = 30;
    public $file;
    public $id = null;
    public GestionnaireForm $gestionnaireForm;



    public function onSubmit(){

        if ($this->id && $this->id != null) {
            $this->gestionnaireForm->id = $this->id;
            $this->gestionnaireForm->update();
            session()->flash('message',"Gestionnaire modifié avec success!");
        }else{
            $this->gestionnaireForm->store();
            session()->flash('message',"Nouveau gestionnaire ajouté!");
        }
        $this->showModal = !$this->showModal;
        $this->reset(["id"]);
        $this->isSending = false;
        $this->gestionnaireForm->reset();
        $this->render();
    }

    public function paginationView()
    {
        return 'components.pagination';
    }

    public function setOrderField(string $field){
        if ($field == $this->orderField) {
            $this->orderDirection = $this->orderDirection  == 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->orderField = $field;
            $this->reset('orderDirection');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $this->isDeleting = true;
        $gestionnaire = Gestionnaires::find($this->id);
        $user = User::where("email", $gestionnaire->email);
        $user->delete();
        $gestionnaire->delete();
        session()->flash('message',"Gestionnaire suprimé!");
        $this->reset(['id','showDeleteModal']);
        $this->render();
        $this->isDeleting = false;
    }
    public function onSHowDeleteModal($id=null){
        if ($this->isDeleting) {
            return;
        }
        $this->id = $id;
        $this->showDeleteModal = !$this->showDeleteModal;
    }
    public function onShowModal($id = null){
        if ($this->isSending) {
            return;
        }
        if ($id != null) {
            $this->id = $id;
            $gestionnaire = Gestionnaires::find($this->id);
            $this->gestionnaireForm->setEngagement($gestionnaire);
        }else{
            $this->gestionnaireForm->reset();
            $this->reset(['id']);
        }
        $this->showModal = !$this->showModal;
        $this->resetErrorBag();
    }
    public function render()
    {
        $gestionnaires = $this->filter()->get();
        $page = Paginator::resolveCurrentPage() ?: 1;
        $gestionnaires = new LengthAwarePaginator(
            $gestionnaires->forPage($page, $this->numPerPage)->all(),
            $gestionnaires->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
        $associations = Associations::all();
        $roles = Roles::all();
        return view('gestionnaires.livewire.gestionnaire-table', compact('roles', 'associations','gestionnaires'));
    }

    public function downloadXlsx(){
        return (new GestionnairesCsvExport($this->filter(true)->get()))->download("gestionnaires.xlsx");
    }
    public function downloadPdf(){
        return (new GestionnairesPdfExport(
            data: $this->transformPdf(),
            title: "Liste des gestionnaires"
        ))->download("gestionnaires.pdf");
    }

    public function transformPdf()
    {
        $engagements = $this->filter(true)->get()->transform(function($row) {
            return [
                "id" => $row['id'],
                "nom et prenom" => $row['name'],
                "association" => $row['associations']->name,
                "téléphone" => $row['phone'],
                "status" => $row['statut'],
                "Ajouter le" => $row['created_at'],
            ];
        });
        return $engagements;
    }

    public function filter($is_printing = false){
        $gestionnaires = Gestionnaires::with(["roles","associations"]);

        if ($this->term != "") {
            $gestionnaires = $gestionnaires->whereHas("associations", function($query){
                return $query->where("name", "LIKE", "%{$this->term}%");
            })->orWhere("name", "LIKE", "%{$this->term}%")
            ->orWhereHas("roles", function($query){
                return $query->where("name", "LIKE", "%{$this->term}%");
            });
        }

        $gestionnaires = $gestionnaires->orderBy($this->orderField,$this->orderDirection);

        return $gestionnaires;
    }



    public function uploadFromFile(){
        // dd($this->file);
        $res = [];
        Excel::load($this->file, function ($reader) use($res){
            $res = $reader->all();
        });

        dd($res);
    }

    public function onShowImportModal(){
        if ($this->isSending) return;
        $this->showImportModal = !$this->showImportModal;
    }
}
