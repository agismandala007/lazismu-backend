<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Kasbesar;
use Illuminate\Http\Request;
use App\Exports\KasbesarExport;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CreateKasbesarRequest;
use App\Http\Requests\UpdateKasbesarRequest;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Illuminate\Support\Facades\DB;

class KasbesarController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $penerima = $request->input('penerima');
        $nobuktikas = $request->input('nobuktikas');
        $tanggal = $request->input('tanggal');
        $from = $request->input('from');
        $to = $request->input('to');
        $coadebit_id = $request->input('coadebit_id');
        $coakredit_id = $request->input('coakredit_id');
        $limit = $request->input('limit',10);
        $cabang_id = $request->input('cabang_id');
        $search = $request->input('search');
        $jenis_data = $request->input('jenis_data');

        $kasbesarQuery = Kasbesar::with(['coadebit','coakredit'])->where('cabang_id', $cabang_id);

        //get single data
        if($id)
        {
           $kasbesar = $kasbesarQuery->find($id);

            if($kasbesar)
            {
                return ResponseFormatter::success($kasbesar, "Kasbesar found");
            }
            return ResponseFormatter::error('Kasbesar not found');
        }

        //get multiple data
        $kasbesars = $kasbesarQuery;

       
        
        if($search || ($from && $to)){
            $kasbesars->where(function ($query) use ($search, $from, $to) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nobuktikas', 'like', '%' . $search . '%');
        
                if ($from && $to) {
                    $query->whereBetween('tanggal', [$from, $to]);
                }
            });
            
        }

        if($penerima){
            $kasbesars->where('penerima',$penerima);
        }
        if($nobuktikas){
            $kasbesars->where('nobuktikas','like','%'.$nobuktikas . '%');
        }
        if($tanggal){
            $kasbesars->where('tanggal', $tanggal);
        }
        if($coadebit_id){
            $kasbesars->where('coadebit_id',$coadebit_id);
        }
        if($coakredit_id){
            $kasbesars->where('coakredit_id',$coakredit_id);
        }
        if($cabang_id){
            $kasbesars->where('cabang_id',$cabang_id);
        }
        if($from && $to){
            $kasbesars->whereBetween('tanggal', [$from, $to]);
        }
        

        return ResponseFormatter::success($kasbesars->orderBy('tanggal','desc')->paginate($limit),'Kasbesar Found');
    }

    public function fetchNo(){
            $noUrut = DB::table('kaskecils')->orderBy('nobuktikas', 'desc')->limit(1)->paginate(1);
    
            if (!$noUrut->isEmpty()) {
                return ResponseFormatter::success($noUrut, 'No Urut Found');
            }else{
                $noUrut = [
                    "data" => [[ "nobuktikas" => "0000" ]]
                    
                ];
    
                return ResponseFormatter::success($noUrut);
            }
    }

    public function create(CreateKasbesarRequest $request)
    {
        try {
            $kasbesar = Kasbesar::create([
                'name' => $request->name,
                'penerima' => $request->penerima,
                'nobuktikas' => $request->nobuktikas,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
                'cabang_id' => $request->cabang_id,
                'jenis_data' => $request->jenis_data

            ]);
            
            if(!$kasbesar)
            {
                throw new Exception('Kasbesar not created');
            }
    
            return ResponseFormatter::success($kasbesar, 'Kasbesar created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        
    }


    public function update(UpdateKasbesarRequest $request, $id)
    {
        try {
            $kasbesar = Kasbesar::find($id);

            if(!$kasbesar)
            {
                throw new Exception('Kasbesar not created');
            }

            //update kasbesar
            $kasbesar->update([
                'name' => $request->name,
                'penerima' => $request->penerima,
                'nobuktikas' => $request->nobuktikas,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
                'cabang_id' => $request->cabang_id,
                'jenis_data' => $request->jenis_data,
            ]);

            return ResponseFormatter::success($kasbesar, 'Kasbesar updated');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            //Get Kasbesar
            $kasbesar = Kasbesar::find($id);

            //check if kasbesar exists
            if (!$kasbesar) {
                throw new Exception('Kasbesar not Found');
            }

            //Delete Kasbesar
            $kasbesar->delete();

            return ResponseFormatter::success('Kasbesar deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500);
        }
    }

    public function export(Request $request)
    {
        $cabang_id = $request->input('cabang_id'); 
        $from = $request->input('from'); 
        $to = $request->input('to'); 
        return Excel::download(new KasbesarExport($cabang_id, $from, $to), 'kasbesar.xlsx');
    }
}
