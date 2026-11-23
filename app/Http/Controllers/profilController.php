<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Tfe;
use App\Rules\AnneeDuNiveau;
use App\Rules\Inedit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class profilController extends Controller
{
    public function index($id = null)
    {
        $tfe = $id ? Tfe::find($id) : null;
        abort_unless($tfe === null || $tfe->isOwnedOrAdministeredBy(Auth::user()), 403);

        return view('auth.profil', [
            'user' => Auth::user()->load('niveau'),
            'tfes' => Auth::user()->tfes()->with(['niveau', 'filiere'])->orderBy('niveau_id')->get(),
        ]);
    }

    public function edit()
    {
        abort_if(Auth::user()->is_admin, 403);

        return view('auth.profil_edit', [
            'user' => Auth::user()->load('niveau'),
            'niveaux' => Niveau::ordonnes()->with('filieresActives')->get(),
        ]);
    }

    /** Le grade (niveau) découle de la filière choisie. */
    public function update(Request $request)
    {
        $user = Auth::user();
        abort_if($user->is_admin, 403);

        $data = $request->validate([
            'entity' => ['required', 'string', Rule::exists('filieres', 'nom')->where('active', true)->whereNotNull('niveau_id')],
            'study_year' => ['required', 'integer', new AnneeDuNiveau($request->input('entity'))],
            // Le matricule ne peut être saisi que s'il manque.
            'matricule' => $user->matricule ? ['exclude'] : ['required', 'string', 'regex:/^[0-9A-Za-z-]{6,30}$/', new Inedit('matricule')],
        ]);
        $data['niveau_id'] = Filiere::where('nom', $data['entity'])->value('niveau_id');
        $user->update($data);

        return redirect()->route('profil')->with('success', 'Profil mis à jour : ' . $user->fresh()->niveau->nom . ', ' . $data['entity'] . '.');
    }
}
