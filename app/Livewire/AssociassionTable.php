<?php

namespace App\Livewire;

use App\Exports\AssociationsExport;
use App\Exports\csv\AssociationsCsvExport;
use App\Exports\pdf\AssociationsPdfExport;
use App\Http\Resources\AssociationsResource;
use App\Models\Associations;
use App\Models\PdfModel;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AssociassionTable extends Component
{
    use WithPagination;

    public string $term = '';
    public string $orderField = "name";
    public string $orderDirection = "ASC";
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $isSending = false;
    public bool $isDeleting = false;
    public int $numPerPage = 30;
    public bool $showImportModal = false;
    public $id = null;
    public string $name = "";
    public string $sigle = "";

    protected $queryString = [
        'term' => ['except' => '']
    ];

    public function render()
    {
        $associations = $this->transformExcell();
        $page = Paginator::resolveCurrentPage() ?: 1;
        $associations = new LengthAwarePaginator(
            $associations->forPage($page, $this->numPerPage)->all(),
            $associations->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
        return view('associations.livewire.associassion-table', compact("associations"));
    }


    public function setOrderField(string $field){
        if ($field == $this->orderField) {
            $this->orderDirection = $this->orderDirection  == 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->orderField = $field;
            $this->reset('orderDirection');
        }

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
            $asso = Associations::find($id);
            $this->name = $asso->name;
            $this->sigle = $asso->sigle;
            $this->id = $id;
        }else{
            $this->reset(['name','sigle','id']);
        }
        $this->showModal = !$this->showModal;
        $this->resetErrorBag();
    }

    public function onSubmit(){
        if ($this->isSending) {
            return;
        }
        $this->validate([
            'name' => 'required|min:5|unique:associations,name,' . $this->id,
            'sigle' => 'required|min:2|unique:associations,sigle,' . $this->id,
        ]);

        $this->isSending = true;
        if ($this->id != null) {
            $asso = Associations::find($this->id);
            $asso->name = $this->name;
            $asso->sigle = $this->sigle;
            $asso->save();
        }else{
            Associations::create([
                'name' => $this->name,
                'sigle' => $this->sigle,
            ]);
        }
        $this->isSending = false;
        $this->showModal = !$this->showModal;
        $this->reset(['name','sigle','id']);
        $this->resetErrorBag();
        $this->render();
    }

    public function onSHowDeleteModal($id=null){
        if ($this->isDeleting) {
            return;
        }
        $this->id = $id;
        $this->showDeleteModal = !$this->showDeleteModal;
    }
    public function delete(){
        $this->isDeleting = true;
        $association = Associations::find($this->id);
        $association->delete();
        session()->flash('message',"Association suprimé!");
        $this->reset(['id','showDeleteModal',"isDeleting"]);
        $this->render();
    }

    public function downloadXlsx(){
        return (new AssociationsCsvExport($this->transformExcell(true)->get()))->download("associations.xlsx");
    }
    public function downloadPdf(){
        return (new AssociationsPdfExport(
            data: $this->transformPdf(),
            title: "Liste des associations"
        ))->download("associations.pdf");
    }

    public function transformPdf()
    {
        $associations = $this->transformExcell(true)->get()->transform(function($row) {
            return [
                "id" => $row['id'],
                "nom" => $row['name'],
                "sigle" => $row['sigle'],
                "date creation" => $row['created_at'],
            ];
        });
        return $associations;
    }

    public function transformExcell($is_printing = false){
        $associations = Associations::query();
        if ($this->term != "") {
            $associations = $associations
            ->where("name", "LIKE", "%{$this->term}%")
            ->orWhere("sigle", "LIKE", "%{$this->term}%");
        }

        if (!$is_printing) {
            $associations = $associations
                ->orderBy($this->orderField,$this->orderDirection)
                ->get()
                ->transform(function ($asso) {
                    return new AssociationsResource($asso);
                });
        }


        return $associations;
    }

    public function onShowImportModal(){
        if ($this->isSending) return;
        $this->showImportModal = !$this->showImportModal;
    }

}
