<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TfeController;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Filiere;
use App\Models\Journal;
use App\Models\Tfe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class StudentController extends Controller
{
    public function new(StoreStudentRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($this->avecNiveau($data));
        Journal::noter('Étudiant ajouté', "{$user->name} ({$user->matricule})");

        return redirect()->back()->with('success', 'Étudiant ajouté avec succès.');
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($this->avecNiveau($request->validated()));
        Journal::noter('Étudiant modifié', "{$user->name} ({$user->matricule})");

        return redirect()->back()->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function envoyerLien($id)
    {
        $user = User::findOrFail($id);
        try {
            $statut = Password::broker()->sendResetLink(['email' => $user->email]);
        } catch (\Throwable $e) {
            Log::error("Lien de réinitialisation non envoyé à l'utilisateur #{$user->id} : " . $e->getMessage());
            $statut = null;
        }
        if ($statut !== Password::RESET_LINK_SENT) {
            return redirect()->back()->with('error', "L'email n'a pas pu être envoyé. Réessayez dans une minute.");
        }
        Journal::noter('Lien de mot de passe envoyé', $user->name);

        return redirect()->back()->with('success', 'Lien de réinitialisation envoyé.');
    }

    public function delete($id)
    {
        if ((int) $id === (int) Auth::id()) {
            return redirect()->back()->with('error', "Vous ne pouvez pas supprimer votre propre compte.");
        }

        $user = User::findOrFail($id);

        foreach (Tfe::where('user_id', $user->id)->pluck('id') as $tfeId) {
            TfeController::destroy($tfeId, true);
        }

        $user->delete();
        Journal::noter('Utilisateur supprimé', "{$user->name} ({$user->email})");

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    private function avecNiveau(array $data)
    {
        $data['niveau_id'] = Filiere::where('nom', $data['entity'])->value('niveau_id');

        return $data;
    }
}
