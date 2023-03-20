<?php

namespace App\Imports;

use App\Models\Coakredit;
use Maatwebsite\Excel\Concerns\ToModel;

class CoakreditImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Coakredit([
            'name' => $row[1],
            'kode' => $row[2], 
            'laporan' => $row[3],
            'cabang_id' => $row[4], 
        ]);
    }
}
