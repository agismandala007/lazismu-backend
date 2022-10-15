<?php

namespace App\Http\Controllers\API;

use App\Models\Frontoffice;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HitungController extends Controller
{
    public function fo(Request $request)
    {
        $id = $request->input('id');
        $cabang_id = $request->input('cabang_id');
        
        $frontofficeQuery =  DB::table('frontoffices')->where('cabang_id', $cabang_id)->sum('jumlah');
        
        //get single data
        if($id)
        {
           $frontoffice = $frontofficeQuery->find($id);

            if($frontoffice)
            {
                return ResponseFormatter::success($frontoffice, "Frontoffice found");
            }
            return ResponseFormatter::error('Frontoffice not found');
        }

        //get multiple data
        $frontoffices = $frontofficeQuery;

        return ResponseFormatter::success($frontoffices,'Frontoffices Found');
    }
    public function kasbank(Request $request)
    {
        $id = $request->input('id');
        $cabang_id = $request->input('cabang_id');
        
        $kasbankQuery =  DB::table('kasbanks')->where('cabang_id', $cabang_id)->sum('jumlah');
        
        //get single data
        if($id)
        {
           $kasbank = $kasbankQuery->find($id);

            if($kasbank)
            {
                return ResponseFormatter::success($kasbank, "Kasbank found");
            }
            return ResponseFormatter::error('Kasbank not found');
        }

        //get multiple data
        $kasbanks = $kasbankQuery;

        return ResponseFormatter::success($kasbanks,'Kasbanks Found');
    }
}
 