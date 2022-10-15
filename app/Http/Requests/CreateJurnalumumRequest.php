<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateJurnalumumRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|min:4',
            'nobukti' => 'required|string|max:255|unique:jurnalumums',
            'tanggal' => 'required',
            'ref' => 'nullable',
            'coadebit_id' => 'required|integer|exists:coadebits,id',
            'coakredit_id' => 'required|integer|exists:coakredits,id',
            'cabang_id' => 'required|integer|exists:cabangs,id',
            'jumlah' => 'required|integer'
        ];
    }
}
