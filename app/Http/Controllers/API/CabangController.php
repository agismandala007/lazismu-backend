<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateCabangRequest;
use App\Http\Requests\UpdateCabangRequest;

class CabangController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit',10);

        // $cabangQuery = Cabang::with(['users'])->whereHas('users', function ($query) {
        //     $query->where('user_id', Auth::id());
        // });
        $cabangQuery = Cabang::with(['users']);


        //get single data
        if($id)
        {
           $cabang = $cabangQuery->find($id);

            if($cabang)
            {
                return ResponseFormatter::success($cabang);
            }
            return ResponseFormatter::error('Cabang not found');
        }

        //get multiple data
        $cabangs = $cabangQuery;
       
        if($name){
            $cabangs->where('name','like','%'.$name . '%');
        }
        return ResponseFormatter::success($cabangs->paginate($limit),'cabangs Found');
    }

    public function create(CreateCabangRequest $request)
    {
        try {
            $cabang = Cabang::create([
                'name' => $request->name,
            ]);
            
            if(!$cabang)
            {
                throw new Exception('Cabang not created');
            }
    
            return ResponseFormatter::success($cabang, 'Cabang created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        
    }
    public function update(UpdateCabangRequest $request, $id)
    {
        try {
            $cabang = Cabang::find($id);

            if(!$cabang)
            {
                throw new Exception('Cabang not created');
            }

            //update cabang
            $cabang->update([
                'name' => $request->name,
            ]);

            return ResponseFormatter::success($cabang, 'Cabang updated');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            //Get Cabang
            $cabang = Cabang::find($id);

            //check if cabang exists
            if (!$cabang) {
                throw new Exception('Cabang not Found');
            }

            //Delete Cabang
            $cabang->delete();

            return ResponseFormatter::success('Cabang deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500);
        }
    }
}
