<?php

namespace App\Exports;

use App\Models\Coa;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class CoaExport implements FromCollection,ShouldAutoSize,WithMapping,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;
    function __construct($id) {
        $this->id = $id;
    }
    public function collection()
    {
        $data = Coa::where('coas.cabang_id', $this->id)->get();
    return $data;
    }
    public function map($coa): array
    {
        if ($coa->tipe == null) {
            $coa->tipe = "Debit";
           }
        if ($coa->tipe == 1) {
            $coa->tipe = "Kredit";
           }
        

        return [
           $coa->id,
           $coa->name,
           $coa->kode,
           $coa->laporan,
           $coa->cabang_id,
           $coa->tipe
           
        ];
    }
    public function headings(): array
    {
        return
        [
            '#',
            'name',
            'Kode',
            'Laporan',
            'cabangid',
            'Tipe'
        ];
    }
}
