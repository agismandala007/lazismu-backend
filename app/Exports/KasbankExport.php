<?php

namespace App\Exports;

use App\Models\Kasbank;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KasbankExport implements FromCollection,ShouldAutoSize,WithMapping,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id, $from, $to;
    function __construct($id, $from, $to) {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }
    public function collection()
    {
        $data = Kasbank::with(['coadebit','coakredit'])
            ->where('kasbanks.cabang_id', $this->id)->whereBetween('tanggal', [$this->from, $this->to])->get();
        return $data;
    }
    public function map($kasbank): array
    {
        return [
           $kasbank->id,
           $kasbank->nobuktikas,
           $kasbank->tanggal,
           $kasbank->ref,
           $kasbank->name,
           $kasbank->coadebit->name,
           $kasbank->jumlah,
           $kasbank->coakredit->name,
           $kasbank->penerima,
           $kasbank->cabang_id,
           
        ];
    }
    public function headings(): array
    {
        return
        [
            '#',
            'no bukti kas',
            'tanggal',
            'ref',
            'Uraian Transaksi',
            'Nama Akun Debit',
            'jumlah',
            'Nama Akun Kredit',
            'Nama Kasir',
            'Cabang_id'
        ];
    }

}
