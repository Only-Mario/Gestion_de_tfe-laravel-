<?php

namespace App\Http\Controllers;

use App\Models\Tfe;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request) {
        $kw = $request->input('search');
        $year = $request->input('year');
        $groupe_pedagogique = $request->input('field_of_study');
    
        $query = Tfe::query();
    
        // Filtrer par mot clé
        if (!empty($kw)) {
            $query->where(function($query) use ($kw) {
                $query->where('theme', 'LIKE', '%' . $kw . '%')
                      ->orWhere('email_maitre_memoire', 'LIKE', '%' . $kw . '%')
                      ->orWhere('maitre_memoire', 'LIKE', '%' . $kw . '%')
                      ->orWhere('email_tuteur', 'LIKE', '%' . $kw . '%')
                      ->orWhere('lieu_de_stage', 'LIKE', '%' . $kw . '%')
                      ->orWhere('tuteur_de_stage', 'LIKE', '%' . $kw . '%')
                      ->orWhere('groupe_pedagogique', 'LIKE', '%' . $kw . '%')
                      ->orWhere('annee_de_realisation', 'LIKE', '%' . $kw . '%')
                      ->orWhere('auteurs', 'LIKE', '%' . $kw . '%');
            });
        }
    
        // Filtrer par année
        if (!empty($year)) {
            $query->where('annee_de_realisation', $year);
        }
    
        // Filtrer par filière
        if (!empty($groupe_pedagogique)) {
            $query->where('groupe_pedagogique', trim($groupe_pedagogique, '@_'));
        }
    
        $tfes = $query->get();
    
        // Récupérer toutes les années disponibles pour l'affichage dans la vue
        $yrs = Tfe::years();
        $years = collect($yrs)->sortDesc();
    
        return view('search', compact('tfes', 'years', 'kw', 'year', 'groupe_pedagogique'));
    } 
}
