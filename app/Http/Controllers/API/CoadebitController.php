<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Coadebit;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCoadebitRequest;
use App\Http\Requests\UpdateCoadebitRequest;

class CoadebitController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $cabang_id = $request->input('cabang_id');
        $limit = $request->input('limit',10);


        $coadebitQuery = Coadebit::with(['cabang'])->where('cabang_id',$request->cabang_id);
        //get single data
        if($id)
        {
           $coadebit = $coadebitQuery->find($id);

            if($coadebit)
            {
                return ResponseFormatter::success($coadebit);
            }
            return ResponseFormatter::error('Coadebit not found');
        }
        //get multiple data
        $coadebits = $coadebitQuery;
       
        if($name){
            $coadebits->where('name','like','%'.$name . '%');
        }
        return ResponseFormatter::success($coadebits->paginate($limit),'Coadebits Found');
    }

    public function create(CreateCoadebitRequest $request)
    {
        try {
            $coadebit = Coadebit::create([
                'name' => $request->name,
                'kode' => $request->kode,
                'laporan' => $request->laporan,
                'cabang_id' => $request->cabang_id,
            ]);
            
            if(!$coadebit)
            {
                throw new Exception('Coadebit not created');
            }

            return ResponseFormatter::success($coadebit, 'Coadebit created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        
    }

    public function update(UpdateCoadebitRequest $request, $id)
    {
        try {
            $coadebit = Coadebit::find($id);

            if(!$coadebit)
            {
                throw new Exception('Coadebit not created');
            }

            //update coadebit
            $coadebit->update([
                'name' => $request->name,
                'kode' => $request->kode,
                'laporan' => $request->laporan,
                'cabang_id' => $request->cabang_id,
                
            ]);

            return ResponseFormatter::success($coadebit, 'Coadebit updated');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            //Get Coadebit
            $coadebit = Coadebit::find($id);

            //check if coadebit exists
            if (!$coadebit) {
                throw new Exception('Coadebit not Found');
            }

            //Delete Coadebit
            $coadebit->delete();

            return ResponseFormatter::success('Coadebit deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500);
        }
    }

}
