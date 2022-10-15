<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Coakredit;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCoakreditRequest;
use App\Http\Requests\UpdateCoakreditRequest;

class CoakreditController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit',10);

        //get single data
        if($id)
        {
           $coakredit = Coakredit::find($id);

            if($coakredit)
            {
                return ResponseFormatter::success($coakredit);
            }
            return ResponseFormatter::error('Coakredit not found');
        }
        //get multiple data
        $coakredits = Coakredit::query();
       
        if($name){
            $coakredits->where('name','like','%'.$name . '%');
        }
        return ResponseFormatter::success($coakredits->paginate($limit),'Coakredits Found');
    }

    public function create(CreateCoakreditRequest $request)
    {
        try {
            $coakredit = Coakredit::create([
                'name' => $request->name,
                'kode' => $request->kode,
                'laporan' => $request->laporan,
            ]);
            
            if(!$coakredit)
            {
                throw new Exception('Coakredit not created');
            }

            return ResponseFormatter::success($coakredit, 'Coakredit created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        
    }

    public function update(UpdateCoakreditRequest $request, $id)
    {
        try {
            $coakredit = Coakredit::find($id);

            if(!$coakredit)
            {
                throw new Exception('Coakredit not created');
            }

            //update coakredit
            $coakredit->update([
                'name' => $request->name,
                'kode' => $request->kode,
                'laporan' => $request->laporan,
                
            ]);

            return ResponseFormatter::success($coakredit, 'Coakredit updated');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            //Get Coakredit
            $coakredit = Coakredit::find($id);

            //check if coakredit exists
            if (!$coakredit) {
                throw new Exception('Coakredit not Found');
            }

            //Delete Coakredit
            $coakredit->delete();

            return ResponseFormatter::success('Coakredit deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500);
        }
    }

}
