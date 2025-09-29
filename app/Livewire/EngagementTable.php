<?php

namespace App\Livewire;

use App\Exports\csv\EngagementsCsvExport;
use App\Exports\pdf\EngagementPdfExport;
use App\Models\Associations;
use App\Models\Engagements;
use App\Models\Paroissien;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class EngagementTable extends Component
{
    use WithPagination;

    public string $term = '';
    public string $orderField = "paroissiens_id";
    public string $orderDirection = "ASC";
    public  $association = '';
    public  $situation = '';
    public  $categorie = '';
    public bool $isSending = false;
    public bool $showImportModal = false;
    public bool $showDeleteModal = false;
    public bool $showMigrateModal = false;
    public bool $isDeleting = false;
    public int $numPerPage = 30;
    public $id = null;

    protected $queryString = [
        'term' => ['except' => ''],
        'situation' => ['except' => ''],
        'categorie' => ['except' => ''],
        'association' => ['except' => '']
    ];


    public function render()
    {
        $engagements = $this->transformExcell()->get();

        $associations = Associations::all();
        $paroissiens = $this->association == "" ? Paroissien::all() : Paroissien::where('association_id',$this->association)->get();

        $page = Paginator::resolveCurrentPage() ?: 1;
        $engagements = new LengthAwarePaginator(
            $engagements->forPage($page, $this->numPerPage)->all(),
            $engagements->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $year = session('year', Carbon::parse(now())->year);

        $statsData = DB::table("engagements")
            ->selectRaw('sum(dime) as somme_dime')
            ->selectRaw('sum(cotisation) as somme_construction')
            ->selectRaw('sum(dette_dime) as somme_dette_dime')
            ->selectRaw('sum(dette_cotisation) as somme_dette_cotisation')
            ->whereRaw('year(periode_start) = ?', $year)
            ->get()
            ->toArray();
        $statsData = $statsData[0];

        $isCurrentYear = Carbon::parse(now())->year == +session("year");

        return view('engagements.livewire.engagement-table',
            compact("engagements","associations","paroissiens","statsData", "isCurrentYear")
        );
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

    public function downloadXlsx(){
        return (new EngagementsCsvExport($this->transformExcell(true)->get()))->download("engagements.xlsx");
    }
    public function downloadPdf(){
        return (new EngagementPdfExport(
            data: $this->transformPdf(),
            title: "Liste des engagement"
        ))->download("engagements.pdf");
    }

    public function transformPdf()
    {
        $engagements = $this->transformExcell(true)->get()->transform(function($row) {
            return [
                "id" => $row['id'],
                "nom" => $row['paroissien']->firstname." ".$row['paroissien']->lastname,
                "montant(dime/construction)" => $row['dime']."/".$row['cotisation'],
                "dette(dime/construction)" => $row['dette_dime']."/".$row['dette_cotisation'],
                "periode(debut/fin)" => $row['periode_start']."/".$row['periode_end'],
            ];
        });
        return $engagements;
    }

    public function transformExcell($is_printing = false){
        $engagements = Engagements::with(["association","paroissien"]);

        if ($this->association != "") {
            $engagements = $engagements
                ->where("associations_id",$this->association);
        }
        if ($this->situation != "") {
            $engagements = $engagements->whereHas("paroissien", function($query){
                return $query->where("situation", $this->situation);
            });
        }
        if ($this->categorie != "") {
            $engagements = $engagements->whereHas("paroissien", function($query){
                return $query->where("categorie", $this->categorie);
            });
        }

        if ($this->term != "") {
            $engagements = $engagements->whereHas("paroissien", function($query){
                    return $query->where("firstname", "LIKE", "%{$this->term}%")
                    ->orWhere("lastname", "LIKE", "%{$this->term}%")
                    ->orWhere("old_matricule", "LIKE", "%{$this->term}%")
                    ->orWhere("situation", "LIKE", "%{$this->term}%")
                    ->orWhere("categorie", "LIKE", "%{$this->term}%")
                    ->orWhere("new_matricule", "LIKE", "%{$this->term}%");
            })->orWhereHas("association", function($query){
                return $query->where("name", "LIKE", "%{$this->term}%");
            });
        }

        $engagements = $engagements->orderBy($this->orderField,$this->orderDirection);

        return $engagements;
    }
        /**
     * Remove the specified resource from storage.
     */
    public function delete()
    {
        $enga = Engagements::with('versements')->find($this->id);
        if (count($enga->versements) > 0) {
            session()->flash('error',"impossible de supprimer cette engagement car il est associé à des versement !");
            $this->reset(['id']);

        }else{
            $enga->delete();
            session()->flash('message',"engagement supprimé avec success !");
        }
        $this->showDeleteModal = !$this->showDeleteModal;
        return redirect()->route('engagement.index');
    }
    public function onShowDeleteModal($id=null){
        if ($this->isDeleting) {
            return;
        }
        $this->id = $id;
        $this->showDeleteModal = !$this->showDeleteModal;
    }

    public function onShowMigrateModal(){
        $this->showMigrateModal = !$this->showMigrateModal;
    }

    public function onShowImportModal(){
        if ($this->isSending) return;
        $this->showImportModal = !$this->showImportModal;
    }
}
