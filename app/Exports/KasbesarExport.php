<?php

namespace App\Exports;

use App\Models\kasbesar;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class KasbesarExport implements FromQuery
{
    use Exportable;
    /**
    * @return \Illuminate\Support\query
    */
    public function query()
    {
        return kasbesar::query()->where('cabang_id', 1);
    }
}
