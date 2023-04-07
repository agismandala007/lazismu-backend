<?php

namespace App\Imports;

use App\Models\Coa;
use Maatwebsite\Excel\Concerns\ToModel;

class CoaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!isset($row[0])) {
            return null;
        }
    
        return new Coa([
            'name' => $row[1],
            'kode' => $row[2],
            'laporan' => $row[3],
            'cabang_id' => $row[4],
            'tipe' => $row[5],
            
        ]);
    }
}
