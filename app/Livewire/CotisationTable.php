<?php

namespace App\Livewire;

use App\Exports\csv\CotisationCsvExport;
use App\Exports\pdf\CotisationPdfExport;
use App\Models\Associations;
use App\Models\Paroissien;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class CotisationTable extends Component
{
    use WithPagination;


    public string $term = "";
    public string $orderField = "id";
    public string $orderDirection = "ASC";
    public bool $showModal = false;
    public bool $isSending = false;
    public bool $isLoadingEngagement = false;
    public int $numPerPage = 30;
    public int $somme = 0;
    public string $type = "general";
    public bool $showImportModal = false;
    public $engagements = null;
    public  $association = '';
    public  $paroissien = '';
    public  $categorie = '';
    public  $situation = '';

    protected $queryString = [
        'term' => ['except' => ''],
        'situation' => ['except' => ''],
        'categorie' => ['except' => ''],
        'association' => ['except' => '']
    ];


    public function render()
    {

        $paroissiens = $this->transformExcell()->get();

        $page = Paginator::resolveCurrentPage() ?: 1;
        $paroissiens = new LengthAwarePaginator(
            $paroissiens->forPage($page, $this->numPerPage)->all(),
            $paroissiens->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
        $associations = Associations::all();

        return view('cotisations.livewire.cotisation-table', compact('paroissiens', 'associations',));
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

    public function downloadXlsx(){
        return (new CotisationCsvExport($this->transformExcell(true)->get()))->download("cotisations.xlsx");
    }
    public function downloadPdf(){
        return (new CotisationPdfExport(
            data: $this->transformPdf(),
            title: "Liste des cotisations"
        ))->download("cotisations.pdf");
    }

    public function transformPdf()
    {
        $associations = $this->transformExcell(true)->get()->transform(function($row) {
            return [
                "Matricule" => $row->old_matricule,
                "nom" => $row->firstname." ".$row->lastname,
                "association" =>  $row->association->name,
                "situation" =>  $row->situation,
                "categorie" =>  $row->categorie,
                "Total" =>  $row->cotisations["general"],
            ];
        });
        return $associations;
    }

    public function transformExcell($is_printing = false){
        $paroissiens = Paroissien::with(['cotisations','association']);


        if ($this->term != "") {
            $paroissiens = $paroissiens->where("firstname", "LIKE", "%{$this->term}%")
                    ->orWhere("lastname", "LIKE", "%{$this->term}%")
                    ->orWhere("old_matricule", "LIKE", "%{$this->term}%")
                    ->orWhere("new_matricule", "LIKE", "%{$this->term}%")
                    ->orWhereHas('association', function ($query) {
                        return $query->where("name", "LIKE", "%{$this->term}%");
                    })
            ;
        }

        if ($this->association != "") {
            $paroissiens = $paroissiens->where("association_id", $this->association);
        }
        if ($this->situation != "") {
            $paroissiens = $paroissiens->where("situation", $this->situation);
        }
        if ($this->categorie != "") {
            $paroissiens = $paroissiens->where("categorie", $this->categorie);
        }


        $paroissiens = $paroissiens
        ->orderBy($this->orderField,$this->orderDirection);

        return $paroissiens;
    }


    public function onShowImportModal(){
        if ($this->isSending) return;
        $this->showImportModal = !$this->showImportModal;
    }
}
