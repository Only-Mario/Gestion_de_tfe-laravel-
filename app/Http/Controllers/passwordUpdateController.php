<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordUpdate;
use App\Mail\MessageInsti;
use App\Services\CodeEmail;
use App\Services\Courriel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class passwordUpdateController extends Controller
{
    /** Chacun ne change que son propre mot de passe. Si l'email est confirmé, un code est exigé. */
    public function index(PasswordUpdate $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()->with('error', "L'ancien mot de passe est incorrect.");
        }
        $hash = Hash::make($request->input('new_password'));

        if (!$user->email_verified_at) {
            return $this->appliquer($request, $hash);
        }

        $resultat = CodeEmail::envoyer($user, 'motdepasse', $user->email, ['hash' => $hash, 'actuel' => $user->password]);

        return redirect()->route($resultat === CodeEmail::ENVOYE ? 'password.code' : 'changepasswordView')
            ->with(...CodeEmail::message($resultat, $user->email));
    }

    public function code()
    {
        $demande = CodeEmail::enCours(Auth::user(), 'motdepasse');

        return $demande ? view('auth.passwords.code', ['adresse' => Courriel::masquer($demande['adresse'])]) : redirect()->route('changepasswordView');
    }

    public function confirmer(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $user = Auth::user();
        $demande = CodeEmail::verifier($user, 'motdepasse', $request->input('code'));

        // La demande tombe si le mot de passe a changé entre-temps
        if (!$demande || !hash_equals($demande['donnees']['actuel'], $user->password)) {
            return back()->withErrors(['code' => 'Code incorrect ou expiré.']);
        }

        return $this->appliquer($request, $demande['donnees']['hash']);
    }

    private function appliquer(Request $request, $hash)
    {
        $user = Auth::user();
        $user->forceFill(['password' => $hash])->save();
        CodeEmail::oublier($user, 'motdepasse');
        $request->session()->regenerate();

        Courriel::envoyer($user->email, new MessageInsti('Votre mot de passe a changé', 'Mot de passe modifié', [
            "Bonjour {$user->name},",
            'Votre mot de passe a été modifié le ' . now('Africa/Porto-Novo')->format('d/m/Y à H\hi') . '. Vos autres appareils sont déconnectés.',
            'Si ce n’est pas vous, utilisez « Mot de passe oublié » puis contactez l’administration de l’INSTI.',
        ], null, ['Mot de passe oublié', rtrim(config('app.url'), '/') . route('password.request', [], false)]));

        return redirect()->route('changepasswordView')->with('success', 'Mot de passe modifié avec succès.');
    }
}
