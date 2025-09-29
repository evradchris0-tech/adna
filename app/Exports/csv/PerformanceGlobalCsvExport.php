<?php

namespace App\Exports\csv;

class PerformanceGlobalCsvExport extends BaseExcellExport
{
    public function headings():array{
        return [
            "N°",
            "Nom",
            "Sigle",
            "Dime recu",
            "Dime total",
            "Offrande construction recu",
            "Offrande construction total",
            "Dette dime reçu",
            "Dette dime total",
            "Dette construction reçu",
            "Dette construction total",
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
                "Sigle" => $row->sigle,
                "Dime recu" => $row->performance["dimeR"] ." FCFA",
                "Dime total" => $row->performance["dime"]." FCFA",
                "Offrande construction recu" => $row->performance["cotisationR"] ." FCFA",
                "Offrande construction total" => $row->performance["cotisation"]." FCFA",
                "Dette dime reçu" => $row->performance["detteDimeR"] ." FCFA",
                "Dette dime total" => $row->performance["detteDime"]." FCFA",
                "Dette construction reçu" => $row->performance["detteCotisationR"] ." FCFA",
                "Dette construction total" => $row->performance["detteCotisation"]." FCFA",
                "Status" => $row->performance["taux"]."%",
            ];
        });
        return $perf;
    }
}
