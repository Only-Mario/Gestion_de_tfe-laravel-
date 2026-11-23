<?php

namespace App\Http\Requests;

use App\Rules\AnneeDuNiveau;
use App\Rules\Inedit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'entity' => ['required', 'string', Rule::exists('filieres', 'nom')],
            'study_year' => ['required', 'integer', new AnneeDuNiveau($this->input('entity'))],
            'matricule' => ['required', 'string', 'regex:/^[0-9A-Za-z-]{6,30}$/', new Inedit('matricule', $id)],
            'email' => ['required', 'string', 'email:rfc,filter', 'max:255', new Inedit('email', $id)],
        ];
    }

    public function messages()
    {
        return [
            'matricule.unique' => 'Ce numéro matricule est déjà utilisé.',
        ];
    }
}
