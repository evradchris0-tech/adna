<?php

namespace App\Livewire;

use App\Exports\csv\ParoissienCsvExport;
use App\Exports\pdf\ParoissienPdfExport;
use App\Http\Resources\ParoissienResource;
use App\Models\Associations;
use App\Models\Paroissien;
use App\Models\PdfModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ParoissienTable extends Component
{
    use WithPagination;

    public string $term = '';
    public string $orderField = "firstname";
    public string $orderDirection = "ASC";
    public string $paroi_name = "";
    public  $situation = '';
    public  $categorie = '';
    public  $association = '';
    public bool $showModal = false;
    public bool $showImportModal = false;
    public bool $isSending = false;
    public bool $showDeleteModal = false;
    public bool $isDeleting = false;
    public int $numPerPage = 30;
    public $id = null;
    public string $name = "";
    public string $sigle = "";

    protected $queryString = [
        'term' => ['except' => ''],
        'situation' => ['except' => ''],
        'categorie' => ['except' => ''],
        'association' => ['except' => '']
    ];


    public function render()
    {
        $paroissiens = $this->transformExcell();
        $associations = Associations::all();

        $page = Paginator::resolveCurrentPage() ?: 1;
        $paroissiens = new LengthAwarePaginator(
            $paroissiens->forPage($page, $this->numPerPage)->all(),
            $paroissiens->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('paroissiens.livewire.paroissien-table', compact("paroissiens","associations"));
    }


    public function setOrderField(string $field){
        if ($field == $this->orderField) {
            $this->orderDirection = $this->orderDirection  == 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->orderField = $field;
            $this->reset('orderDirection');
        }

    }

    public function onShowDeleteModal($id=null){
        if ($this->isDeleting) {
            return;
        }
        $this->id = $id;
        $this->showDeleteModal = !$this->showDeleteModal;
    }


    public function paginationView()
    {
        return 'components.pagination';
    }

    public function onShowModal($id = null){
        if ($this->isSending) {
            return;
        }
        if ($id != null) {
            $data = Paroissien::find($id);
            $this->id = $id;
            $this->paroi_name = $data->firstname." ".$data->lastname;
        }else{
            $this->reset(['id','paroi_name']);
        }
        $this->showModal = !$this->showModal;
        $this->resetErrorBag();
    }

    public function onDelete(){
        if ($this->isSending) {
            return;
        }

        $this->isSending = true;
        $asso = Paroissien::find($this->id);
        $asso->delete();
        $this->isSending = false;
        $this->showModal = !$this->showModal;
        $this->reset(['id','paroi_name']);
        $this->showDeleteModal = !$this->showDeleteModal;
        $this->resetErrorBag();
        $this->render();
    }

    public function downloadXlsx(){
        return (new ParoissienCsvExport($this->transformExcell(true)->get()))->download("paroissiens.xlsx");
    }
    public function downloadPdf(){
        return (new ParoissienPdfExport(
            data: $this->transformPdf(),
            title: "Liste des paroissiens"
        ))->download();
    }

    public function transformPdf()
    {
        $paroissiens = $this->transformExcell(true)->get()->transform(function($row) {
            return [
                "nom" => $row['firstname']." ".$row['lastname'],
                "genre" => $row['genre'] == 'h' ? 'Homme' : 'Femme',
                "date & lieu de naissance" => $row['birthdate'].",".$row['birthplace'],
                "niveu d'etude" => $row['school_level'],
                "numero de téléphone" => $row['phone'],
            ];
        });
        return $paroissiens;
    }

    public function transformExcell($is_printing = false){
        $paroissiens = Paroissien::with(["association"]);

        if ($this->term != "") {
            $paroissiens = $paroissiens
                ->where("firstname", "LIKE", "%{$this->term}%")
                ->orWhere("lastname", "LIKE", "%{$this->term}%")
                ->orWhere("old_matricule", "LIKE", "%{$this->term}%")
                ->orWhere("new_matricule", "LIKE", "%{$this->term}%");
        }
        if ($this->categorie) {
            $paroissiens = $paroissiens->where("categorie",$this->categorie);
        }
        if ($this->situation) {
            $paroissiens = $paroissiens->where("situation",$this->situation);
        }
        if ($this->association) {
            $paroissiens = $paroissiens->where("association_id",$this->association);
        }
        $paroissiens = $paroissiens->orderBy($this->orderField,$this->orderDirection);

        if (!$is_printing) {
            $paroissiens = $paroissiens->get()->transform(function ($asso) {
                return new ParoissienResource($asso);
            });
        }

        return $paroissiens;
    }

    public function onShowImportModal(){
        if ($this->isSending) return;
        $this->showImportModal = !$this->showImportModal;
    }

}
