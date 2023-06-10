<?php

namespace App\Exports;

use App\Models\Jurnalumum;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JurnalumumExport implements FromCollection,ShouldAutoSize,WithMapping,WithHeadings
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
        $data = Jurnalumum::with(['coadebit','coakredit'])
            ->where('jurnalumums.cabang_id', $this->id)->whereBetween('tanggal', [$this->from, $this->to])->get();
        return $data;
    }
    public function map($jurnalumum): array
    {

        return [
           $jurnalumum->id,
           $jurnalumum->nobukti,
           $jurnalumum->tanggal,
           $jurnalumum->ref,
           $jurnalumum->name,
           $jurnalumum->coadebit->name,
           $jurnalumum->jumlah,
           $jurnalumum->coakredit->name,
           
        ];
    }
    public function headings(): array
    {
        return
        [
            'id',
            'no bukti',
            'tanggal',
            'ref',
            'Uraian Transaksi',
            'Nama Akun Debit',
            'jumlah',
            'Nama Akun Kredit',
        ];
    }
}
