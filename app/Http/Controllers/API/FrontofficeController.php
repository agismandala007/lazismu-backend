<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Frontoffice;
use Illuminate\Http\Request;
use App\Exports\FrontofficeExport;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
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
        $cabang_id = $request->input('cabang_id');
        $limit = $request->input('limit');
        $search = $request->input('search');

        $frontofficeQuery = Frontoffice::with(['coadebit','coakredit'])->where('cabang_id',$cabang_id);

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
       
        if($search){
            $frontoffices->where('name','like','%'.$search.'%')->orWhere('nobuktipenerima','like','%'.$search.'%');
        }

        if($penyetor){
            $frontoffices->where('penyetor',$penyetor);
        }

        if($penerima){
            $frontoffices->where('penerima',$penerima);
        }
        // if($nobuktipenerima){
        //     $frontoffices->where('nobuktipenerima','like','%'.$nobuktipenerima . '%');
        // }
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
                'cabang_id' => $request->cabang_id,

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

    public function export(Request $request)
    {
        $cabang_id = $request->input('cabang_id');
     
        // $arrays =  Frontoffice::leftJoin('coadebits','coadebits.id','=','frontoffices.coadebit_id')
        // ->leftJoin('coakredits','coakredits.id','=','frontoffices.coakredit_id')
        // ->select('frontoffices.name','frontoffices.penyetor','frontoffices.penerima','frontoffices.nobuktipenerima','frontoffices.tanggal','frontoffices.ref','frontoffices.jumlah','frontoffices.tempatbayar','coakredits.name','coadebits.name')->where('frontoffices.cabang_id',$cabang_id)->get()->toArray();
        
        return Excel::download(new FrontofficeExport($cabang_id), 'clients.xlsx');
        // return Excel::download(new PengeluaranExport,'pengeluaran.xlsx');
        // return (new FrontofficeExport)->forCabang($cabang_id)->download('invoices.xlsx');
        // (new KasbesarExport(auth('sanctum')->user()))->store('transactions-exports/' . now()->format('d:m:Y') . '.csv', 's3', \Maatwebsite\Excel\Excel::CSV);
        // return response()->json('Export started');
    }
}
