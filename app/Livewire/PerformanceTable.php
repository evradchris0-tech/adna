<?php

namespace App\Livewire;

use App\Exports\csv\PerformanceGlobalCsvExport;
use App\Exports\pdf\PerformancePdfExport;
use App\Models\Associations;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class PerformanceTable extends Component
{
    use WithPagination;

    public string $term = '';
    public string $type = 'taux';
    public string $orderField = "id";
    public string $orderDirection = "ASC";
    public  $status = '';
    public int $numPerPage = 30;

    protected $queryString = [
        'term' => ['except' => ''],
        'situation' => ['except' => ''],
        'categorie' => ['except' => ''],
        'association' => ['except' => ''],
        'status' => ['except' => ''],
    ];


    public function render()
    {
        $performances = $this->filter();
        $page = Paginator::resolveCurrentPage() ?: 1;
        // dd($performances->forPage($page, $this->numPerPage)->all());
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
        return view('performance.livewire.performance-table', compact("performances","type"));
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
        return (new PerformanceGlobalCsvExport($this->status == "" ? $this->filter(true)->get() : $this->filter(true)))->download("performances.xlsx");
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
                "Sigle" => $row->sigle,
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
        $performances = Associations::with(["paroissiens"]);

        if ($this->term != "") {
            $performances = $performances
                ->where("name", "LIKE", "%{$this->term}%")
                ->orWhere("sigle", "LIKE", "%{$this->term}%");
        }
        $performances = $performances->orderBy($this->orderField,$this->orderDirection);
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
