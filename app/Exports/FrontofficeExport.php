<?php

namespace App\Exports;

use App\Models\Frontoffice;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FrontofficeExport implements FromCollection,ShouldAutoSize,WithMapping,WithHeadings
{

    protected $id, $from, $to;
    function __construct($id, $from, $to) {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $data = Frontoffice::with(['coadebit','coakredit'])
            ->where('frontoffices.cabang_id', $this->id)->whereBetween('tanggal', [$this->from, $this->to])->get();
        return $data;
    }
    public function map($frontoffice): array
    {
        return [
           $frontoffice->id,
           $frontoffice->name,
           $frontoffice->penyetor,
           $frontoffice->penerima,
           $frontoffice->nobuktipenerima,
           $frontoffice->tanggal,
           $frontoffice->ref,
           $frontoffice->jumlah,
           $frontoffice->tempatbayar,
           $frontoffice->coadebit->name,
           $frontoffice->coakredit->name,
           $frontoffice->cabang_id,
           
        ];
    }
    public function headings(): array
    {
        return
        [
            '#',
            'name',
            'penyetor',
            'penerima',
            'nobukti',
            'tanggal',
            'ref',
            'jumlah',
            'tempatbayar',
            'debit',
            'kredit',
            'cabangid'
        ];
    }
    // use Exportable;
    // public function forCabang(int $cabang)
    // {
    //     $this->cabang = $cabang;
        
    //     return $this;
    // }
    // public function query()
    // {
    //     return DB::table('frontoffices')
    //     ->leftJoin('coadebits','coadebits.id','=','frontoffices.coadebit_id')
    //     ->leftJoin('coakredits','coakredits.id','=','frontoffices.coakredit_id')
    //     ->select('frontoffices.name','frontoffices.penyetor','frontoffices.penerima','frontoffices.nobuktipenerima','frontoffices.tanggal','frontoffices.ref','frontoffices.jumlah','frontoffices.tempatbayar','coakredits.name','coadebits.name','frontoffices.cabang_id')
    //     ->orderBy('tanggal','DESC')->where('frontoffices.cabang_id', $this->cabang);
    // }
}
