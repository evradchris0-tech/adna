<?php

namespace App\Livewire;

use App\Exports\csv\VersementsCsvExport;
use App\Exports\pdf\VersementPdfExport;
use App\Models\Associations;
use App\Models\Engagements;
use App\Models\Paroissien;
use App\Models\Versements;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class VersementTable extends Component
{
    use WithPagination;


    public string $term = "";
    public string $orderField = "id";
    public string $orderDirection = "ASC";
    public bool $showModal = false;
    public bool $isSending = false;
    public bool $isLoadingEngagement = false;
    public bool $showImportModal = false;
    public int $numPerPage = 30;
    public int $somme = 0;
    public string $type = "dime";
    public $id = null;
    public $engagements = null;
    public  $association = '';
    public  $paroissien = '';
    public  $categorie = '';
    public  $situation = '';
    protected $listeners = ['paroisienSelected'];

    protected $queryString = [
        'term' => ['except' => ''],
        'situation' => ['except' => ''],
        'categorie' => ['except' => ''],
        'association' => ['except' => '']
    ];

    public function paroisienSelected($id){
        $this->paroissien = $id;
    }


    public function render()
    {
        $paroissiens = $this->filter()->get();
        $page = Paginator::resolveCurrentPage() ?: 1;
        $paroissiens = new LengthAwarePaginator(
            $paroissiens->forPage($page, $this->numPerPage)->all(),
            $paroissiens->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
        $associations = Associations::all();
        $stats = $this->getStats();
        // dd($stats);
        return view('versements.livewire.versement-table', compact('paroissiens', 'associations','stats'));
    }

    public function getStats(){
        $q_eng = 'sum(available_dime) as dime,sum(available_cotisation) as cotisation,sum(available_dette_dime) as dette_dime,sum(available_dette_cotisation) as dette_cotisation';
        $statDataVersement = Engagements::selectRaw($q_eng)->get()[0];

        return $statDataVersement;
    }

    public function paginationView()
    {
        return 'components.pagination';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        if ($this->isSending) {
            return;
        }
        $this->isSending = true;
        $versement = Versements::find($this->id);
        $engagement = $versement->engagement;

        if ($versement->type == 'dime') {
            $engagement->available_dime -= $versement->somme;
        }else if($versement->type == 'dette_cotisation') {
            $engagement->available_dette_cotisation -= $versement->somme;
        }else if($versement->type == 'dette_dime') {
            $engagement->available_dette_dime -= $versement->somme;
        }else{
            $engagement->available_cotisation -= $versement->somme;
        }

        $engagement->save();
        $versement->delete();
        $this->isSending = false;
        $this->showModal = !$this->showModal;
        session()->flash('message',"versement supprimé avec success!");
        return redirect()->route('versement.index');
    }

    public function onSHowDeleteModal($id = ""){
        if ($this->isSending) {
            return;
        }
        if ($id != "") {
            $this->id = $id;
        }else{
            $this->reset("id");
        }
        $this->showModal = !$this->showModal;
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
        return (new VersementsCsvExport($this->filter(true)->get()))->download("global_versements.xlsx");
    }
    public function downloadPdf(){
        return (new VersementPdfExport(
            data: $this->transformPdf(),
            title: "Liste des versements"
        ))->download("global_versements.pdf");
    }

    public function transformPdf()
    {
        $paroissiens = $this->filter(true)->get()->transform(function($row) {
            return [
                "Matricule" => $row->old_matricule,
                "nom" => $row->firstname." ".$row->lastname,
                "association" =>  $row->association->name,
                "situation" =>  $row->situation,
                "categorie" =>  $row->categorie,
                "Total versé" =>  count($row->engagements) != 0 ? $row->engagements[0]->avg_versement['solde'] : 0,
                "Total restant" =>  count($row->engagements) != 0 ? $row->engagements[0]->avg_versement['reste'] : 0
            ];
        });
        return $paroissiens;
    }

    public function filter($is_printing = false){
        $paroissiens = Paroissien::with(['engagements','association']);


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

        $paroissiens = $paroissiens->whereHas( 'engagements', function ($query) {
            $year = session('year', Carbon::parse(now())->year);
            return $query->whereRAW('YEAR(periode_start) = ?', [$year]);
        });

        $paroissiens = $paroissiens->orderBy($this->orderField,$this->orderDirection);

        return $paroissiens;
    }

    public function onShowImportModal(){
        if ($this->isSending) return;
        $this->showImportModal = !$this->showImportModal;
    }
}
