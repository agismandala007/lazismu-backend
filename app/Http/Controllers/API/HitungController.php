<?php

namespace App\Http\Controllers\API;

use App\Models\Coa;
use App\Models\Kasbesar;
use App\Models\Frontoffice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Jurnalumum;
use App\Models\Kasbank;
use App\Models\Kaskecil;

class HitungController extends Controller
{

    public function statisAll(Request $request)
    {
        $cabang_id = $request->input('cabang_id');
        $menu = $request->input('menu');

        $month = Carbon::now()->addMonth(0)->format('m');
        $year = Carbon::now()->format('Y');

        

        switch ($menu) {
            case 'kasbesar':
                $pemasukan = Kasbesar::where('cabang_id', $cabang_id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('jenis_data', 0)->orderBy('tanggal', 'ASC')->get();
                $pengeluaran = Kasbesar::where('cabang_id', $cabang_id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('jenis_data', 1)->orderBy('tanggal', 'ASC')->get();
                break;
            case 'frontoffice':
                $pemasukan = Frontoffice::where('cabang_id', $cabang_id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->orderBy('tanggal', 'ASC')->get();
                $pengeluaran = [];
                break;
            case 'kaskecil':
                $pemasukan = Kaskecil::where('cabang_id', $cabang_id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->orderBy('tanggal', 'ASC')->get();
                $pengeluaran = Kaskecil::where('cabang_id', $cabang_id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('jenis_data', 1)->orderBy('tanggal', 'ASC')->get();
                break;
            case 'kasbank':
                $pemasukan = Kasbank::where('cabang_id', $cabang_id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->orderBy('tanggal', 'ASC')->get();
                $pengeluaran = Kasbank::where('cabang_id', $cabang_id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('jenis_data', 1)->orderBy('tanggal', 'ASC')->get();
                break;
            case 'jurnalumum':
                $pemasukan = Jurnalumum::where('cabang_id', $cabang_id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->orderBy('tanggal', 'ASC')->get();
                $pengeluaran = [];
                break;
            

            default:
                # code...
                break;
        }


        // $pemasukan = $statis->where('jenis_data', 0)->get();
        // $pengeluaran = $statis->where('jenis_data', 1)->get();

        return [
            'kasbesarIn' => $pemasukan,
            'kasbesarOut' => $pengeluaran,
        ];
    }
    public function coa(Request $request)
    {
        $cabang_id = $request->input('cabang_id');

        return [
            'coakredit' => Coa::where('tipe', 1)->where('cabang_id', $cabang_id)->count(),
            'coadebit' => Coa::where('tipe', false)->where('cabang_id', $cabang_id)->count()
        ];
    }

    public function fo(Request $request)
    {
        $cabang_id = $request->input('cabang_id');

        $frontoffices =  DB::table('frontoffices')->where('cabang_id', $cabang_id)->sum('jumlah');


        return ResponseFormatter::success($frontoffices, 'Frontoffices Found');
    }
    public function kasbesar(Request $request)
    {
        $id = $request->input('id');
        $cabang_id = $request->input('cabang_id');
        $kasbesarpemasukan =  DB::table('kasbesars')->where('cabang_id', $cabang_id)->where('jenis_data', 0)->sum('jumlah');
        $kasbesarpengeluaran =  DB::table('kasbesars')->where('cabang_id', $cabang_id)->where('jenis_data', 1)->sum('jumlah');

        $total = $kasbesarpemasukan - $kasbesarpengeluaran;

        return [
            'pemasukan' => $kasbesarpemasukan,
            'pengeluaran' => $kasbesarpengeluaran,
            'total' => $total,
        ];
    }
    public function kaskecil(Request $request)
    {
        $id = $request->input('id');
        $cabang_id = $request->input('cabang_id');
        $kaskecilpemasukan =  DB::table('kaskecils')->where('cabang_id', $cabang_id)->where('jenis_data', 0)->sum('jumlah');
        $kaskecilpengeluaran =  DB::table('kaskecils')->where('cabang_id', $cabang_id)->where('jenis_data', 1)->sum('jumlah');

        $total = $kaskecilpemasukan - $kaskecilpengeluaran;

        return [
            'pemasukan' => $kaskecilpemasukan,
            'pengeluaran' => $kaskecilpengeluaran,
            'total' => $total,
        ];
    }

    public function kasbank(Request $request)
    {
        $id = $request->input('id');
        $cabang_id = $request->input('cabang_id');
        $kasbankpemasukan =  DB::table('kasbanks')->where('cabang_id', $cabang_id)->where('jenis_data', 0)->sum('jumlah');
        $kasbankpengeluaran =  DB::table('kasbanks')->where('cabang_id', $cabang_id)->where('jenis_data', 1)->sum('jumlah');

        $total = $kasbankpemasukan - $kasbankpengeluaran;

        return [
            'pemasukan' => $kasbankpemasukan,
            'pengeluaran' => $kasbankpengeluaran,
            'total' => $total,
        ];
    }
    public function jurnalumum(Request $request)
    {
        $cabang_id = $request->input('cabang_id');

        $jurnalumums =  DB::table('jurnalumums')->where('cabang_id', $cabang_id)->sum('jumlah');
        return ResponseFormatter::success($jurnalumums, 'Hitung Jurnal Umum Found');
    }
}
