<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateKasbesarRequest extends FormRequest
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
            'name' => 'required|string',
            'penerima' => 'required|string',
            'nobuktikas' => 'required|string|max:255|',
            'nobuktikas' => Rule::unique('kasbesars')->where(fn ($query) => $query->where('cabang_id', $this->cabang_id)),
            'tanggal' => 'required',
            'ref' => 'nullable',
            'coadebit_id' => 'required|integer|exists:coas,id',
            'coakredit_id' => 'required|integer|exists:coas,id',
            'cabang_id' => 'required|integer|exists:cabangs,id',
            'jumlah' => 'required|integer'
        ];
    }
}
