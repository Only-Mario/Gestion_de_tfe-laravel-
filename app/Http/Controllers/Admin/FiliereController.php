<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Journal;
use App\Models\Niveau;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FiliereController extends Controller
{
    public function index()
    {
        return view('admin.filieres', [
            'niveaux' => Niveau::ordonnes()->with(['filieres' => function ($q) {
                $q->withCount('tfes');
            }])->get(),
            'sansNiveau' => Filiere::whereNull('niveau_id')->withCount('tfes')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $filiere = Filiere::create($this->valider($request) + ['active' => true]);
        Journal::noter('Filière ajoutée', $filiere->libelle . ' - ' . $filiere->niveau->nom);

        return redirect()->route('filieres')->with('success', 'Filière ajoutée.');
    }

    public function update(Request $request, $id)
    {
        $filiere = Filiere::findOrFail($id);
        $data = $this->valider($request, $filiere);

        // Le niveau et le sigle sont liés aux documents et aux étudiants existants.
        $changeNiveau = $filiere->niveau_id !== null && (int) $data['niveau_id'] !== (int) $filiere->niveau_id;
        if (($changeNiveau || $data['nom'] !== $filiere->nom) && $this->estUtilisee($filiere)) {
            return back()->with('error', 'Cette filière est utilisée : seule sa description peut changer.');
        }

        $filiere->update($data);
        Journal::noter('Filière modifiée', $filiere->libelle);

        return redirect()->route('filieres')->with('success', 'Filière mise à jour.');
    }

    public function basculer($id)
    {
        $filiere = Filiere::findOrFail($id);
        $filiere->update(['active' => !$filiere->active]);
        Journal::noter($filiere->active ? 'Filière activée' : 'Filière désactivée', $filiere->libelle);

        return redirect()->route('filieres')->with('success', $filiere->active ? 'Filière activée.' : 'Filière désactivée.');
    }

    public function destroy($id)
    {
        $filiere = Filiere::findOrFail($id);
        if ($this->estUtilisee($filiere)) {
            return back()->with('error', 'Cette filière est utilisée : désactivez-la plutôt.');
        }
        $filiere->delete();
        Journal::noter('Filière supprimée', $filiere->libelle);

        return redirect()->route('filieres')->with('success', 'Filière supprimée.');
    }

    private function estUtilisee(Filiere $filiere)
    {
        return $filiere->tfes()->exists() || User::where('entity', $filiere->nom)->exists();
    }

    private function valider(Request $request, ?Filiere $filiere = null)
    {
        $nom = $request->input('nom');
        $request->merge(['nom' => is_string($nom) ? mb_strtoupper(trim($nom)) : null]);

        return $request->validate([
            // Le sigle est unique : il identifie la filière des étudiants.
            'nom' => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9-]+$/', Rule::unique('filieres', 'nom')->ignore($filiere ? $filiere->id : null)],
            'description' => ['required', 'string', 'max:255'],
            'niveau_id' => ['required', 'integer', 'exists:niveaux,id'],
        ], [
            'nom.regex' => 'Le sigle ne contient que des lettres sans accent, des chiffres et des tirets.',
            'nom.unique' => 'Ce sigle est déjà utilisé.',
        ]);
    }
}
