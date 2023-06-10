<?php

namespace App\Http\Controllers\API;

use App\Exports\JurnalumumExport;
use Exception;
use App\Models\Jurnalumum;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CreateJurnalumumRequest;
use App\Http\Requests\UpdateJurnalumumRequest;

class JurnalumumController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $nobukti = $request->input('nobukti');
        $tanggal = $request->input('tanggal');
        $from = $request->input('from');
        $to = $request->input('to');
        $coadebit_id = $request->input('coadebit_id');
        $coakredit_id = $request->input('coakredit_id');
        $limit = $request->input('limit',10);
        $cabang_id = $request->input('cabang_id');
        $search = $request->input('search');

        $jurnalumumQuery = Jurnalumum::with(['coadebit','coakredit'])->where('cabang_id', $cabang_id);

        //get single data
        if($id)
        {
           $jurnalumum = $jurnalumumQuery->find($id);

            if($jurnalumum)
            {
                return ResponseFormatter::success($jurnalumum, "Jurnalumum found");
            }
            return ResponseFormatter::error('Jurnalumum not found');
        }

        //get multiple data
        $jurnalumum = $jurnalumumQuery;
        if($search){
            $jurnalumum->where('name','like','%'.$search.'%')->orWhere('nobukti','like','%'.$search.'%');
        }
        if($name){
            $jurnalumum->where('name','like','%'.$name . '%');
        }
        if($nobukti){
            $jurnalumum->where('nobukti','like','%'.$nobukti . '%');
        }
        if($tanggal){
            $jurnalumum->where('tanggal', $tanggal);
        }
        if($coadebit_id){
            $jurnalumum->where('coadebit_id',$coadebit_id);
        }
        if($coakredit_id){
            $jurnalumum->where('coakredit_id',$coakredit_id);
        }
        if($from && $to){
            $jurnalumum->whereBetween('tanggal', [$from, $to]);
        }
        

        return ResponseFormatter::success($jurnalumum->orderBy('id','desc')->paginate($limit),'Jurnalumum Found');
    }

    public function create(CreateJurnalumumRequest $request)
    {
        try {
            $jurnalumum = Jurnalumum::create([
                'name' => $request->name,
                'nobukti' => $request->nobukti,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
                'cabang_id' => $request->cabang_id,
            ]);
            
            if(!$jurnalumum)
            {
                throw new Exception('Jurnalumum not created');
            }
    
            return ResponseFormatter::success($jurnalumum, 'Jurnalumum created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        
    }

    public function update(UpdateJurnalumumRequest $request, $id)
    {
        try {
            $jurnalumum = Jurnalumum::find($id);

            if(!$jurnalumum)
            {
                throw new Exception('Jurnalumum not created');
            }

            //update jurnalumum
            $jurnalumum->update([
                'name' => $request->name,
                'nobukti' => $request->nobukti,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
                'cabang_id' => $request->cabang_id,
            ]);

            return ResponseFormatter::success($jurnalumum, 'Jurnalumum updated');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            //Get Jurnalumum
            $jurnalumum = Jurnalumum::find($id);

            //check if jurnalumum exists
            if (!$jurnalumum) { 
                throw new Exception('Jurnalumum not Found');
            }

            //Delete Jurnalumum
            $jurnalumum->delete();

            return ResponseFormatter::success('Jurnalumum deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500);
        }
    }
    public function export(Request $request)
    {
        $cabang_id = $request->input('cabang_id'); 
        $from = $request->input('from'); 
        $to = $request->input('to'); 
        return Excel::download(new JurnalumumExport($cabang_id, $from, $to), 'ju.xlsx');
    }
}
