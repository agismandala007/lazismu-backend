<?php

namespace App\Http\Requests;

use App\Models\Jurnalumum;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJurnalumumRequest extends FormRequest
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
        $jurnalumum = Jurnalumum::find($request->id);
        return [
            'name' => 'required|string',
            'nobukti' => ['required', Rule::unique('jurnalumums', 'nobukti')->ignore($jurnalumum)->where(fn ($query) => $query->where('cabang_id', $this->cabang_id))],
            'tanggal' => 'required',
            'ref' => 'nullable',
            'coadebit_id' => 'required|integer|exists:coas,id',
            'coakredit_id' => 'required|integer|exists:coas,id',
            'cabang_id' => 'required|integer|exists:cabangs,id',
            'jumlah' => 'required|integer'
        ];
    }
}
