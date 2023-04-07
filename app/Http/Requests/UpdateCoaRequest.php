<?php

namespace App\Http\Requests;

use App\Models\Coa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class UpdateCoaRequest extends FormRequest
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
        $coa = Coa::find($request->id);
        return [
            'name' => 'required|string|max:255',
            'kode' => ['required', 'unique:coas,kode,' . $coa->id],
            'cabang_id' => 'required|integer|exists:cabangs,id',
            'laporan' => 'required|string|max:255',
            'tipe' => 'required'
        ];
    }
}
