<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Frontoffice;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFrontofficeRequest;
use App\Http\Requests\UpdateFrontofficeRequest;

class FrontofficeController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $penyetor = $request->input('penyetor');
        $penerima = $request->input('penerima');
        $nobuktipenerima = $request->input('nobuktipenerima');
        $tanggal = $request->input('tanggal');
        $from = $request->input('from');
        $to = $request->input('to');
        $tempatbayar = $request->input('tempatbayar');
        $coadebit_id = $request->input('coadebit_id');
        $coakredit_id = $request->input('coakredit_id');
        $limit = $request->input('limit');

        $frontofficeQuery = Frontoffice::with(['coadebit','coakredit']);

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
       
        if($name){
            $frontoffices->where('name','like','%'.$name . '%');
        }

        if($penyetor){
            $frontoffices->where('penyetor',$penyetor);
        }

        if($penerima){
            $frontoffices->where('penerima',$penerima);
        }
        if($nobuktipenerima){
            $frontoffices->where('nobuktipenerima','like','%'.$nobuktipenerima . '%');
        }
        if($tanggal){
            $frontoffices->where('tanggal', $tanggal);
        }
        if($tempatbayar){
            $frontoffices->where('tempatbayar',$tempatbayar);
        }
        if($coadebit_id){
            $frontoffices->where('coadebit_id',$coadebit_id);
        }
        if($coakredit_id){
            $frontoffices->where('coakredit_id',$coakredit_id);
        }
        if($from && $to){
            $frontoffices->whereBetween('tanggal', [$from, $to]);
        }
        if($limit)
        {
            return ResponseFormatter::success($frontoffices->paginate($limit),'Frontoffices Found');
        }

        return ResponseFormatter::success($frontoffices->get(),'Frontoffices Found');
    }

    public function create(CreateFrontofficeRequest $request)
    {
        try {
            $frontoffice = Frontoffice::create([
                'name' => $request->name,
                'penyetor' => $request->penyetor,
                'penerima' => $request->penerima,
                'nobuktipenerima' => $request->nobuktipenerima,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'tempatbayar' => $request->tempatbayar,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
            ]);
            
            if(!$frontoffice)
            {
                throw new Exception('Frontoffice not created');
            }
    
            return ResponseFormatter::success($frontoffice, 'Frontoffice created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        
    }

    public function update(UpdateFrontofficeRequest $request, $id)
    {
        try {
            $frontoffice = Frontoffice::find($id);

            if(!$frontoffice)
            {
                throw new Exception('Frontoffice not created');
            }

            //update frontoffice
            $frontoffice->update([
                'name' => $request->name,
                'penyetor' => $request->penyetor,
                'penerima' => $request->penerima,
                'nobuktipenerima' => $request->nobuktipenerima,
                'tanggal' => $request->tanggal,
                'ref' => $request->ref,
                'jumlah' => $request->jumlah,
                'tempatbayar' => $request->tempatbayar,
                'coadebit_id' => $request->coadebit_id,
                'coakredit_id' => $request->coakredit_id,
            ]);

            return ResponseFormatter::success($frontoffice, 'Frontoffice updated');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            //Get Frontoffice
            $frontoffice = Frontoffice::find($id);

            //check if frontoffice exists
            if (!$frontoffice) {
                throw new Exception('Frontoffice not Found');
            }

            //Delete Frontoffice
            $frontoffice->delete();

            return ResponseFormatter::success('Frontoffice deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500);
        }
    }
}
