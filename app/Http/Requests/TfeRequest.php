<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TfeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'theme' => ['required', 'string', 'max:255'],
            'auteurs' => ['required', 'string', 'max:255'],
            'annee_de_realisation' => ['required', 'string'],
            'tuteur_de_stage' => ['required', 'string'],
            'lieu_de_stage' => ['required', 'string'],
            'email_tuteur' => ['required', 'string', 'email', 'max:255'],
            'email_maitre_memoire' => ['required', 'string', 'email', 'max:255'],
            'maitre_memoire' => ['required','string', 'max:255'],
            'resume' => ['required','text'],
            "document" => ['required','mimes:pdf', 'max:2048'],
            
        ];
    }
}
