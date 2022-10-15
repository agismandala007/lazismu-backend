<?php

namespace App\Http\Requests;

use App\Models\Kaskecil;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKaskecilRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        $kaskecil = Kaskecil::find($request->id);
        return [
            'name' => 'required|string|min:4',
            'penerima' => 'required|string|min:4',
            'nobuktikas' => ['required', Rule::unique('kaskecils', 'nobuktikas')->ignore($kaskecil)],
            'tanggal' => 'required',
            'ref' => 'nullable',
            'coadebit_id' => 'required|integer|exists:coadebits,id',
            'coakredit_id' => 'required|integer|exists:coakredits,id',
            'cabang_id' => 'required|integer|exists:cabangs,id',
            'jumlah' => 'required|integer'
        ];
    }
}
