<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;

/** Traduit les anciennes adresses /search?... vers les filtres de l'accueil. */
class SearchController extends Controller
{
    public function search(Request $request)
    {
        $groupe = $request->query('groupe_pedagogique');
        $kw = $request->query('kw');

        return redirect()->route('welcome', array_filter([
            'kw' => is_string($kw) ? mb_substr($kw, 0, 100) : null,
            'annee' => (int) $request->query('annee_de_realisation') ?: null,
            'filiere' => is_string($groupe) ? Filiere::where('nom', trim($groupe, '@_'))->value('id') : null,
        ]), 301);
    }
}
