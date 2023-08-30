<?php

namespace App\Http\Controllers\API;

use App\Exports\KaskecilExport;
use Exception;
use App\Models\Kaskecil;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CreateKaskecilRequest;
use App\Http\Requests\UpdateKaskecilRequest;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

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
        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $cabang_id = $request->input('cabang_id');

        $kaskecilQuery = Kaskecil::with(['coadebit', 'coakredit'])->where('cabang_id', $cabang_id);

        //get single data
        if ($id) {
            $kaskecil = $kaskecilQuery->find($id);

            if ($kaskecil) {
                return ResponseFormatter::success($kaskecil, "Kaskecil found");
            }
            return ResponseFormatter::error('Kaskecil not found');
        }

        //get multiple data
        $kaskecil = $kaskecilQuery;

        if ($search || ($from && $to)) {
            $kaskecil->where(function ($query) use ($search, $from, $to) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nobuktikas', 'like', '%' . $search . '%');

                if ($from && $to) {
                    $query->whereBetween('tanggal', [$from, $to]);
                }
            });
        }

        if ($penerima) {
            $kaskecil->where('penerima', $penerima);
        }
        if ($nobuktikas) {
            $kaskecil->where('nobuktikas', 'like', '%' . $nobuktikas . '%');
        }
        if ($tanggal) {
            $kaskecil->where('tanggal', $tanggal);
        }
        if ($coadebit_id) {
            $kaskecil->where('coadebit_id', $coadebit_id);
        }
        if ($coakredit_id) {
            $kaskecil->where('coakredit_id', $coakredit_id);
        }
        if ($from && $to) {
            $kaskecil->whereBetween('tanggal', [$from, $to]);
        }


        return ResponseFormatter::success($kaskecil->orderBy('tanggal', 'desc')->paginate($limit), 'Kaskecil Found');
    }

    public function fetchNo()
    {
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
                'cabang_id' => $request->cabang_id,
                'jenis_data' => $request->jenis_data
            ]);

            if (!$kaskecil) {
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

            if (!$kaskecil) {
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
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    public function export(Request $request)
    {
        $cabang_id = $request->input('cabang_id');
        $from = $request->input('from');
        $to = $request->input('to');
        return Excel::download(new KaskecilExport($cabang_id, $from, $to), 'kaskecil.xlsx');
    }
}
