<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Kasbesar;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKasbesarRequest;
use App\Http\Requests\UpdateKasbesarRequest;

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

        $kasbesarQuery = Kasbesar::with(['coadebit','coakredit']);

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
        $kasbesar = $kasbesarQuery;
       
        if($name){
            $kasbesar->where('name','like','%'.$name . '%');
        }
        if($penerima){
            $kasbesar->where('penerima',$penerima);
        }
        if($nobuktikas){
            $kasbesar->where('nobuktikas','like','%'.$nobuktikas . '%');
        }
        if($tanggal){
            $kasbesar->where('tanggal', $tanggal);
        }
        if($coadebit_id){
            $kasbesar->where('coadebit_id',$coadebit_id);
        }
        if($coakredit_id){
            $kasbesar->where('coakredit_id',$coakredit_id);
        }
        if($from && $to){
            $kasbesar->whereBetween('tanggal', [$from, $to]);
        }
        

        return ResponseFormatter::success($kasbesar->paginate($limit),'Kasbesar Found');
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
}
