<?php

namespace App\Http\Controllers\API;

use App\Exports\CoaExport;
use Exception;
use App\Models\Coa;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCoaRequest;
use App\Http\Requests\UpdateCoaRequest;
use App\Imports\CoaImport;
use Maatwebsite\Excel\Facades\Excel;

class CoaController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit',10);
        $cabang_id = $request->input('cabang_id');
        $tipe = $request->input('tipe');

        //get single data
        if($id)
        {
           $coa = Coa::where('cabang_id',$request->cabang_id)->find($id);

            if($coa)
            {
                return ResponseFormatter::success($coa);
            }
            return ResponseFormatter::error('Coa not found');
        }
        //get multiple data
        $coas = Coa::query()->where('cabang_id',$request->cabang_id);
       
        if($name){
            $coas->where('name','like','%'.$name . '%');
        }
        if($tipe){
            $coas->where('tipe', intval($request->tipe));
        }


        
        return ResponseFormatter::success($coas->paginate($limit),'Coas Found');
    }

    public function create(CreateCoaRequest $request)
    {
        try {
            $coa = Coa::create([
                'name' => $request->name,
                'kode' => $request->kode,
                'laporan' => $request->laporan,
                'cabang_id' => $request->cabang_id,
                'tipe' => $request->tipe,
            ]);
            
            if(!$coa)
            {
                throw new Exception('Coa not created');
            }

            return ResponseFormatter::success($coa, 'Coa created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        
    }

    public function update(UpdateCoaRequest $request, $id)
    {
        try {
            $coa = Coa::find($id);

            if(!$coa)
            {
                throw new Exception('Coa not created');
            }

            //update coa
            $coa->update([
                'name' => $request->name,
                'kode' => $request->kode,
                'laporan' => $request->laporan,
                'cabang_id' => $request->cabang_id,
                'tipe' => $request->tipe,
                
            ]);

            return ResponseFormatter::success($coa, 'Coa updated');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            //Get Coa
            $coa = Coa::find($id);

            //check if coa exists
            if (!$coa) {
                throw new Exception('Coa not Found');
            }

            //Delete Coa
            $coa->delete();

            return ResponseFormatter::success('Coa deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500);
        }
    }
    public function importcoa(Request $request) 
	{
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);
		// import data
		Excel::import(new CoaImport, $request->file('file')->store('temp'));

        return ResponseFormatter::success('Import Success');    
	}
    public function export(Request $request)
    {
        $cabang_id = $request->input('cabang_id'); 
        return Excel::download(new CoaExport($cabang_id), 'clients.xlsx');
    }

}
