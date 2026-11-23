<?php

namespace App\Http\Requests;

use App\Models\Filiere;
use App\Models\Tfe;
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
        if (!$this->isUpdate()) {
            return true;
        }

        return Tfe::findOrFail($this->input('tfe_id'))->isEditableBy($this->user());
    }

    /** Au dépôt : filière active ayant un niveau. À la modification : filière du même niveau. */
    private function filiereRule()
    {
        return function ($attribute, $value, $fail) {
            $filiere = Filiere::find($value);
            $tfe = $this->isUpdate() ? Tfe::find($this->input('tfe_id')) : null;

            if (!$filiere || !$filiere->niveau_id) {
                return $fail("La filière choisie n'existe pas.");
            }
            if ($tfe) {
                if ($tfe->niveau_id && (int) $tfe->niveau_id !== (int) $filiere->niveau_id) {
                    $fail('La filière doit rester du même niveau.');
                }
            } elseif (!$filiere->active) {
                $fail("Cette filière n'accepte plus de dépôts.");
            }
        };
    }

    private function isUpdate()
    {
        return $this->routeIs('updateTfe', 'tfe.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $justification = $this->isUpdate() && $this->user()->is_admin != 1;

        return [
            'theme' => ['required', 'string', 'max:255'],
            'auteurs' => ['required', 'string', 'max:255'],
            'annee_de_realisation' => ['required', 'integer', 'min:2000', 'max:' . date('Y')],
            'tuteur_de_stage' => ['required', 'string', 'max:255'],
            'lieu_de_stage' => ['required', 'string', 'max:255'],
            'email_tuteur' => ['required', 'string', 'email:rfc,filter', 'max:255'],
            'email_maitre_memoire' => ['required', 'string', 'email:rfc,filter', 'max:255'],
            'maitre_memoire' => ['required','string', 'max:255'],
            'filiere_id' => ['required', 'integer', $this->filiereRule()],
            'resume' => ['nullable', 'string', 'max:20000'],
            // Obligatoire au dépôt, facultatif à la modification
            'document' => [$this->isUpdate() ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:10240'],
            // L'auteur doit justifier toute modification ; les administrateurs en sont dispensés
            'motif' => $justification ? ['required', 'string', 'min:10', 'max:2000'] : ['exclude'],
            'justificatif' => $justification ? ['required', 'file', 'mimes:pdf', 'max:10240'] : ['exclude'],
        ];
    }
}
