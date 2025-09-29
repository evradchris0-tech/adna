<?php

namespace App\Livewire;

use App\Exports\csv\PerformanceCsvExport;
use App\Exports\pdf\PerformancePdfExport;
use App\Models\Paroissien;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class PerformanceGlobalTable extends Component
{
    use WithPagination;

    public string $term = '';
    public string $orderField = "old_matricule";
    public string $orderDirection = "ASC";
    public string $paroi_name = "";
    public  $situation = '';
    public  $categorie = '';
    public  $status = '';
    public string $type = 'taux';
    public int $numPerPage = 30;

    protected $queryString = [
        'term' => ['except' => ''],
        'situation' => ['except' => ''],
        'categorie' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount(){
    }


    public function render()
    {
        $performances = $this->filter();
        $page = Paginator::resolveCurrentPage() ?: 1;
        $performances = new LengthAwarePaginator(
            $this->status != "" ?
                $performances->forPage($page, $this->numPerPage)->all() :
                $performances->forPage($page, $this->numPerPage)->get()->all(),
            $performances->count(),
            $this->numPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $type = $this->type;
        return view('performance.livewire.performance-global-table', compact("performances","type"));
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
        return (new PerformanceCsvExport($this->status == "" ? $this->filter(true)->get() : $this->filter(true)))->download("performances.xlsx");
    }
    public function downloadPdf(){
        return (new PerformancePdfExport(
            data: $this->transformPdf(),
            title: "Liste des performances"
        ))->download("performances.pdf");
    }

    public function transformPdf()
    {
        $performances = ($this->status == "" ? $this->filter(true)->get() : $this->filter(true))->transform(function($row) {
            return [
                "N°" => $row->id,
                "Nom" => $row->name,
                "Sigle" => $row->association->sigle,
                "Dime" => $row->performance["dimeR"] ." / ". $row->performance["dime"]." FCFA",
                "Offrande construction" => $row->performance["cotisationR"] ." / ". $row->performance["cotisation"]." FCFA",
                "Dette dime" => $row->performance["detteDimeR"] ." / ". $row->performance["detteDime"]." FCFA",
                "Dette construction" => $row->performance["detteCotisationR"] ." / ". $row->performance["detteCotisation"]." FCFA",
                "Status" => $row->performance["taux"]."%",
            ];
        });
        return $performances;
    }

    public function filter($is_printing = false){
        $performances = Paroissien::select();

        if ($this->term != "") {
            $performances = $performances
                ->where("firstname", "LIKE", "%{$this->term}%")
                ->orWhere("lastname", "LIKE", "%{$this->term}%")
                ->orWhere("old_matricule", "LIKE", "%{$this->term}%")
                ->orWhere("new_matricule", "LIKE", "%{$this->term}%");
        }
        if ($this->categorie) {
            $performances = $performances->where("categorie",$this->categorie);
        }
        if ($this->situation) {
            $performances = $performances->where("situation",$this->situation);
        }
        if ($this->orderField == "name") {
            $performances = $performances->orderBy("firstname",$this->orderDirection)->orderBy("lastname",$this->orderDirection);
        }else{
            $performances = $performances->orderBy($this->orderField,$this->orderDirection);
        }
        if ($this->status && $this->status != "") {
            if ($this->status == 1) {
                $performances = $performances->get()->filter(function ($perf){
                    return $perf->performance["taux"] == 100;
                });
            } elseif ( $this->status == 2) {
                $performances = $performances->get()->filter(function ($perf){
                    return ($perf->performance["taux"] < 100 && $perf->performance["taux"] >= 75);
                });
            } elseif ($this->status == 3) {
                $performances = $performances->get()->filter(function ($perf){
                    return ($perf->performance["taux"] < 75 && $perf->performance["taux"] >= 50);
                });
            } elseif ( $this->status == 4) {
                $performances = $performances->get()->filter(function ($perf){
                    return ($perf->performance["taux"] < 50 && $perf->performance["taux"] >= 25);
                });
            } elseif ($this->status == 5) {
                $performances = $performances->get()->filter(function ($perf){
                    return ($perf->performance["taux"] < 25 && $perf->performance["taux"] > 0);
                });
            } elseif ( $this->status == 6) {
                $performances = $performances->get()->filter(function ($perf){
                    return $perf->performance["taux"] == 0;
                });
            }

        }

        return $performances;
    }

}
