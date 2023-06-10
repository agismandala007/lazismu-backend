<?php

namespace App\Exports;

use App\Models\Kaskecil;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KaskecilExport implements FromCollection,ShouldAutoSize,WithMapping,WithHeadings
{
    
    protected $id, $from, $to;
    function __construct($id, $from, $to) {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }
    public function collection()
    {
        $data = Kaskecil::with(['coadebit','coakredit'])
            ->where('kaskecils.cabang_id', $this->id)->whereBetween('tanggal', [$this->from, $this->to])->get();
        return $data;
    }
    public function map($kaskecil): array
    {
        return [
           $kaskecil->id,
           $kaskecil->name,
           $kaskecil->penerima,
           $kaskecil->nobuktikas,
           $kaskecil->tanggal,
           $kaskecil->ref,
           $kaskecil->jumlah,
           $kaskecil->coadebit->name,
           $kaskecil->coakredit->name,
           $kaskecil->cabang_id,
           
        ];
    }
    public function headings(): array
    {
        return
        [
            '#',
            'name',
            'penerima',
            'no bukti kas',
            'tanggal',
            'ref',
            'jumlah',
            'Nama Akun Debit',
            'Nama Akun Kredit',
            'Cabang_id'
        ];
    }
}
