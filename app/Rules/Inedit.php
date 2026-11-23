<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

/** Email ou matricule qu'aucun autre compte n'utilise, quelles que soient la casse et les espaces. */
class Inedit implements Rule
{
    private $colonne;
    private $ignorer;

    public function __construct($colonne, $ignorer = null)
    {
        $this->colonne = $colonne;
        $this->ignorer = $ignorer;
    }

    public function passes($attribute, $value)
    {
        $valeur = User::normaliser($this->colonne, $value);

        return !User::where(function ($q) use ($valeur) {
            $q->where($this->colonne, $valeur)->orWhere($this->colonne . '_cle', $valeur);
        })->when($this->ignorer, function ($q) {
            $q->where('id', '<>', $this->ignorer);
        })->exists();
    }

    public function message()
    {
        return $this->colonne === 'email'
            ? 'Un compte utilise déjà cette adresse email.'
            : 'Un compte utilise déjà ce numéro matricule.';
    }
}
