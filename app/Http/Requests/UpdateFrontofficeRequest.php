<?php

namespace App\Http\Requests;

use App\Models\Coakredit;
use App\Models\Frontoffice;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class UpdateFrontofficeRequest extends FormRequest
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
            'penyetor' => 'required|string',
            'penerima' => 'required|string',
            'nobuktipenerima' => ['required', Rule::unique('frontoffices')->ignore($this->id)->where(fn ($query) => $query->where('cabang_id', $this->cabang_id))],
            'tanggal' => 'required',
            'ref' => 'nullable',
            'tempatbayar' => 'required|string|max:255',
            'coadebit_id' => 'required|integer|exists:coas,id',
            'coakredit_id' => 'required|integer|exists:coas,id',
            'cabang_id' => 'required|integer|exists:cabangs,id',
            'jumlah' => 'required|integer'

        ];
    }
}
