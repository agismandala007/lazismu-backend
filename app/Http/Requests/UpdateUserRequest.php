<?php

namespace App\Http\Requests;

use App\Models\Coa;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class UpdateUserRequest extends FormRequest
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

        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255', Rule::unique('users', 'email')->ignore($request->id)],
            'password' => ['string'],
            'role' => ['required',],
            'cabang_id' => ['required'],
        ];
    }
}
