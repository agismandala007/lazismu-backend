<?php

namespace App\Http\Requests;

use App\Models\Kasbesar;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKasbesarRequest extends FormRequest
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
        $kasbesar = Kasbesar::find($request->id);
        return [
            'name' => 'required|string|min:4',
            'penerima' => 'required|string|min:4',
            'nobuktikas' => ['required', Rule::unique('kasbesars', 'nobuktikas')->ignore($kasbesar)],
            'tanggal' => 'required',
            'ref' => 'nullable',
            'coadebit_id' => 'required|integer|exists:coas,id',
            'coakredit_id' => 'required|integer|exists:coas,id',
            'cabang_id' => 'required|integer|exists:cabangs,id',
            'jenis_data' => 'required',
            'jumlah' => 'required|integer'
        ];
    }
}
