<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;

class FileImport implements ToModel
{

    public function model(array $model)
    {
        dd($model);
    }
}
