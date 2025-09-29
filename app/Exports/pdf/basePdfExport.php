<?php

namespace App\Exports\pdf;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BasePdfExport implements FromView,WithHeadings,ShouldAutoSize,Responsable
{
    use Exportable;

    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'default.pdf';

    /**
    * Optional Writer Type
    */
    private $writerType = \Maatwebsite\Excel\Excel::DOMPDF;

    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/pdf',
    ];

    public $data;
    public $title;
    public $view;

    public function __construct($data, $view = "exports.default", $title="default title") {
        $this->data = $data;
        $this->title = $title;
        $this->view = $view;
    }

    public function headings():array{
        return [];
    }

    public function view(): View
    {
        $dataToPass = [
            "title" => $this->title,
            "headers" =>  $this->headings(),
            "datas" => $this->data,
        ];

        return view($this->view , [
            'dataToPass' => $dataToPass,
        ]);
    }
}
