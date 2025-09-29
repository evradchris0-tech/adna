<?php

namespace App\Exports\csv;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BaseExcellExport implements FromCollection,WithHeadings,ShouldAutoSize,Responsable
{
    use Exportable;

    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    public $fileName = 'default.xlsx';

    /**
    * Optional Writer Type
    */
    private $writerType = \Maatwebsite\Excel\Excel::XLSX;

    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/xlsx',
    ];

    public Collection $data;

    public function __construct(Collection $data) {
        $this->data = $data;
    }

    public function headings():array{
        return [];
    }
    public function collection(){
        return $this->data;
    }
}
