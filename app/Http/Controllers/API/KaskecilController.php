<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Kaskecil;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKaskecilRequest;
use App\Http\Requests\UpdateKaskecilRequest;

class KaskecilController extends Controller
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

        $kaskecilQuery = Kaskecil::with(['coadebit','coakredit']);

        //get single data
        if($id)
        {
           $kaskecil = $kaskecilQuery->find($id);

            if($kaskecil)
            {
                return ResponseFormatter::success($kaskecil, "Kaskecil found");
            }
            return ResponseFormatter::error('Kaskecil not found');
        }

        //get multiple data
        $kaskecil = $kaskecilQuery;
       
        if($name){
            $kaskecil->where('name','like','%'.$name . '%');
        }
        if($penerima){
            $kaskecil->where('penerima',$penerima);
        }
        if($nobuktikas){
            $kaskecil->where('nobuktikas','like','%'.$nobuktikas . '%');
        }
        if($tanggal){
            $kaskecil->where('tanggal', $tanggal);
        }
        if($coadebit_id){
            $kaskecil->where('coadebit_id',$coadebit_id);
        }
        if($coakredit_id){
            $kaskecil->where('coakredit_id',$coakredit_id);
        }
        if($from && $to){
            $kaskecil->whereBetween('tanggal', [$from, $to]);
        }
        

        return ResponseFormatter::success($kaskecil->paginate($limit),'Kaskecil Found');
    }

    public function create(CreateKaskecilRequest $request)
    {
        try {
            $kaskecil = Kaskecil::create([
                'name' => $request->name,
                'penerima' => $request->penerima,
                'nobuktikas' => $request->nobuktikas,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
            ]);
            
            if(!$kaskecil)
            {
                throw new Exception('Kaskecil not created');
            }
    
            return ResponseFormatter::success($kaskecil, 'Kaskecil created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        
    }

    public function update(UpdateKaskecilRequest $request, $id)
    {
        try {
            $kaskecil = Kaskecil::find($id);

            if(!$kaskecil)
            {
                throw new Exception('Kaskecil not created');
            }

            //update kaskecil
            $kaskecil->update([
                'name' => $request->name,
                'penerima' => $request->penerima,
                'nobuktikas' => $request->nobuktikas,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
            ]);

            return ResponseFormatter::success($kaskecil, 'Kaskecil updated');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            //Get Kaskecil
            $kaskecil = Kaskecil::find($id);

            //check if kaskecil exists
            if (!$kaskecil) { 
                throw new Exception('Kaskecil not Found');
            }

            //Delete Kaskecil
            $kaskecil->delete();

            return ResponseFormatter::success('Kaskecil deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500);
        }
    }
}
