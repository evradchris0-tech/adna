<?php

namespace App\Livewire;

use App\Exports\CotisationExport;
use App\Exports\csv\DetailCotisationCsvExport;
use App\Exports\pdf\DetailCotisationPdfExport;
use App\Models\Cotisation;
use App\Models\Paroissien;
use App\Models\Versements;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class CotisationShowTable extends Component
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
        $this->paroissien = Paroissien::with(['association'])->find($id);
    }


    public function render()
    {

        $cotisations = $this->filter()->get();

        $page = Paginator::resolveCurrentPage() ?: 1;
        $cotisations = new LengthAwarePaginator(
            $cotisations->forPage($page, $this->numPerPage)->all(),
            $cotisations->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
        $paroissien = $this->paroissien;

        return view('cotisations.livewire.cotisation-show-table', compact('paroissien','cotisations'));
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
        $versement = Cotisation::find($this->id);
        $versement->delete();
        $this->isSending = false;
        $this->showModal = !$this->showModal;
        session()->flash('message',"cotisation supprimée avec success!");
        return redirect()->route('cotisations.index');
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
        return (new DetailCotisationCsvExport($this->filter(true)->get()))->download($this->paroissien->firstname.'_cotisation_stat.xlsx');
    }
    public function downloadPdf(){
        return (new DetailCotisationPdfExport(
            data: $this->transformPdf(),
            title: "Detail des cotisations de ".$this->paroissien->firstname
        ))->download($this->paroissien->firstname."_cotisations.pdf");
    }

    public function transformPdf()
    {
        $cotisations = $this->filter(true)->get()->transform(function($row) {
            return [
                "id" => $row->id,
                "Type" => $row->type,
                "somme" =>  $row->somme,
                "date versement" =>  $row->created_at,
            ];
        });
        return $cotisations;
    }

    public function filter($is_printing = false){
        $cotisations = Cotisation::where('paroissiens_id', $this->id);

        if ($this->type != "") {
            $cotisations = $cotisations->where("type", $this->type);
        }
        if ($this->periode_end != null) {
            $cotisations = $cotisations->where("updated_at","<", Carbon::parse($this->periode_end)->toDateTimeString());
        }
        if ($this->periode_start != null) {
            $cotisations = $cotisations->where("updated_at",">", Carbon::parse($this->periode_start)->toDateTimeString());
        }


        $cotisations = $cotisations->orderBy($this->orderField,$this->orderDirection);

        return $cotisations;
    }
}
