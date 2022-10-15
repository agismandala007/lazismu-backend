<?php

namespace App\Http\Requests;

use App\Models\Coadebit;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class UpdateCoadebitRequest extends FormRequest
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
        $coadebit = Coadebit::find($request->id);
        return [
            'name' => 'required|string|max:255',
            'kode' => ['required', Rule::unique('coadebits', 'kode')->ignore($coadebit)],
            'laporan' => 'required|string|max:255',
            'cabang_id' => 'required|integer|exists:cabangs,id',
        ];
    }
}
