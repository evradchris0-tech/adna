<?php

namespace App\Exports\csv;

class DetailPerformanceCsvExport extends BaseExcellExport
{
    public function headings():array{
        return [
            "N°",
            "Nom",
            "Dime",
            "Offrande construction",
            "Dette dime",
            "Dette construction",
            "Status",
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $perf = $this->data->transform(function($row) {
            return [
                "N°" => $row->id,
                "Nom" => $row->name,
                "Dime" => $row->performance["dimeR"] ." / ". $row->performance["dime"]." FCFA",
                "Offrande de construction" => $row->performance["cotisationR"] ." / ". $row->performance["cotisation"]." FCFA",
                "Dette dime" => $row->performance["detteDimeR"] ." / ". $row->performance["detteDime"]." FCFA",
                "Dette construction" => $row->performance["detteCotisationR"] ." / ". $row->performance["detteCotisation"]." FCFA",
                "Status" => $row->performance["taux"]."%",
            ];
        });

        return $perf;
    }
}
