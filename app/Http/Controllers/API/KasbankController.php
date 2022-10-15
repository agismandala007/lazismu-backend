<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Kasbank;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKasbankRequest;
use App\Http\Requests\UpdateKasbankRequest;

class KasbankController extends Controller
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

        $kasbankQuery = Kasbank::with(['coadebit','coakredit']);

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
        $kasbank = $kasbankQuery;
       
        if($name){
            $kasbank->where('name','like','%'.$name . '%');
        }
        if($penerima){
            $kasbank->where('penerima',$penerima);
        }
        if($nobuktikas){
            $kasbank->where('nobuktikas','like','%'.$nobuktikas . '%');
        }
        if($tanggal){
            $kasbank->where('tanggal', $tanggal);
        }
        if($coadebit_id){
            $kasbank->where('coadebit_id',$coadebit_id);
        }
        if($coakredit_id){
            $kasbank->where('coakredit_id',$coakredit_id);
        }
        if($from && $to){
            $kasbank->whereBetween('tanggal', [$from, $to]);
        }
        

        return ResponseFormatter::success($kasbank->paginate($limit),'Kasbank Found');
    }

    public function create(CreateKasbankRequest $request)
    {
        try {
            $kasbank = Kasbank::create([
                'name' => $request->name,
                'penerima' => $request->penerima,
                'nobuktikas' => $request->nobuktikas,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
            ]);
            
            if(!$kasbank)
            {
                throw new Exception('Kasbank not created');
            }
    
            return ResponseFormatter::success($kasbank, 'Kasbank created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        
    }

    public function update(UpdateKasbankRequest $request, $id)
    {
        try {
            $kasbank = Kasbank::find($id);

            if(!$kasbank)
            {
                throw new Exception('Kasbank not created');
            }

            //update kasbank
            $kasbank->update([
                'name' => $request->name,
                'penerima' => $request->penerima,
                'nobuktikas' => $request->nobuktikas,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
            ]);

            return ResponseFormatter::success($kasbank, 'Kasbank updated');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            //Get Kasbank
            $kasbank = Kasbank::find($id);

            //check if kasbank exists
            if (!$kasbank) {
                throw new Exception('Kasbank not Found');
            }

            //Delete Kasbank
            $kasbank->delete();

            return ResponseFormatter::success('Kasbank deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500);
        }
    }
}
