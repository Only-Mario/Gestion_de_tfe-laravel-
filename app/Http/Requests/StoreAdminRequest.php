<?php

namespace App\Http\Requests;

use App\Rules\Inedit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'email:rfc,filter', 'max:255', new Inedit('email')],
            'password' => ['required', 'string', Password::min(10)->letters()->numbers()],
            'passwordV' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'username.unique' => 'Un compte existe déjà avec cet email.',
            'passwordV.required' => 'Veuillez confirmer votre propre mot de passe pour continuer.',
        ];
    }
}
