<?php

namespace App\Livewire;

use App\Exports\csv\DetailCotisationCsvExport;
use App\Exports\pdf\DetailVersementPdfExport;
use App\Models\Engagements;
use App\Models\Paroissien;
use App\Models\Versements;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class VersementShowTable extends Component
{
    use WithPagination;


    public $periode_start = null;
    public $periode_end = null;
    public string $type = "";
    public string $orderField = "id";
    public string $orderDirection = "ASC";
    public bool $showModal = false;
    public bool $isSending = false;
    public int $numPerPage = 30;
    public $id = null;
    public $delete_id = null;
    public $paroissien = null;

    protected $queryString = [
        'term' => ['except' => ''],
    ];

    public function mount(int $id){
        $this->id = $id;
        $this->paroissien = Paroissien::with(['engagements','association'])->find($id);
    }


    public function render()
    {
        $versements = $this->filter()->get();

        $page = Paginator::resolveCurrentPage() ?: 1;
        $versements = new LengthAwarePaginator(
            $versements->forPage($page, $this->numPerPage)->all(),
            $versements->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
        $paroissien = $this->paroissien;

        return view('versements.livewire.versement-show-table', compact('paroissien','versements'));
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
        $versement = Versements::find($this->delete_id);
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
            $this->delete_id = $id;
        }else{
            $this->reset("delete_id");
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
        return (new DetailCotisationCsvExport($this->filter(true)->get()))->download($this->paroissien->firstname."_detail_versements.xlsx");
    }
    public function downloadPdf(){
        return (new DetailVersementPdfExport(
            data: $this->transformPdf(),
            title: "Detail des versement de ".$this->paroissien->firstname
        ))->download($this->paroissien->firstname."_cotisations.pdf");
    }

    public function transformPdf()
    {
        $paroissiens = $this->filter(true)->get()->transform(function($row) {
            return [
                "N°" => $row->id,
                "Type" => $row->type,
                "Somme" =>  $row->somme,
                "Date versement" =>  $row->created_at,
            ];
        });
        return $paroissiens;
    }

    public function filter($is_printing = false){
        $year = session('year', Carbon::parse(now())->year);
        $engagement = Engagements::where('paroissiens_id', $this->id)->get();
        $versements = Versements::where('paroissiens_id', $this->id);

        if (count($engagement) > 0) {
            $versements = $versements->where('engagement_id', $engagement[0]->id);
        }
        if ($this->type != "") {
            $versements = $versements->where("type", $this->type);
        }
        if ($this->periode_end != null) {
            $versements = $versements->where("created_at","<", Carbon::parse($this->periode_end)->toDateTimeString());
        }
        if ($this->periode_start != null) {
            $versements = $versements->where("created_at",">", Carbon::parse($this->periode_start)->toDateTimeString());
        }


        $versements = $versements->orderBy($this->orderField,$this->orderDirection);


        return $versements;
    }


}
