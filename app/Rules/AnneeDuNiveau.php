<?php

namespace App\Rules;

use App\Models\Filiere;
use Illuminate\Contracts\Validation\Rule;

/** L'année d'étude doit exister pour le niveau de la filière choisie. */
class AnneeDuNiveau implements Rule
{
    private $filiere;

    public function __construct($filiere)
    {
        $this->filiere = is_string($filiere) ? $filiere : '';
    }

    public function passes($attribute, $value)
    {
        $filiere = Filiere::with('niveau')->where('nom', $this->filiere)->first();

        return $filiere && $filiere->niveau && array_key_exists((int) $value, $filiere->niveau->annees);
    }

    public function message()
    {
        return "Cette année d'étude ne correspond pas au niveau de la filière.";
    }
}
