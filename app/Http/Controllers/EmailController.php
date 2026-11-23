<?php

namespace App\Http\Controllers;

use App\Mail\MessageInsti;
use App\Rules\Inedit;
use App\Services\CodeEmail;
use App\Services\Courriel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/** Confirmation et changement de l'adresse email, par code. */
class EmailController extends Controller
{
    public function show()
    {
        return view('auth.email', ['user' => Auth::user(), 'demande' => CodeEmail::enCours(Auth::user(), 'confirmation')]);
    }

    public function envoyer()
    {
        $user = Auth::user();
        $demande = CodeEmail::enCours($user, 'confirmation');
        // Renvoi vers la nouvelle adresse si un changement est en cours
        $adresse = $demande['adresse'] ?? $user->email;

        return redirect()->route('email.confirmer')->with(...CodeEmail::message(CodeEmail::envoyer($user, 'confirmation', $adresse), $adresse));
    }

    /** La nouvelle adresse n'est enregistrée qu'une fois son code saisi. */
    public function changer(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'email' => ['required', 'string', 'email:rfc,filter', 'max:255', new Inedit('email', $user->id)],
            'mot_de_passe' => ['required', 'string'],
        ]);
        if (!Hash::check($data['mot_de_passe'], $user->password)) {
            return back()->withInput($request->only('email'))->withErrors(['mot_de_passe' => 'Mot de passe incorrect.']);
        }
        $adresse = mb_strtolower(trim($data['email']));

        return redirect()->route('email.confirmer')->with(...CodeEmail::message(CodeEmail::envoyer($user, 'confirmation', $adresse), $adresse));
    }

    public function confirmer(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $user = Auth::user();
        $demande = CodeEmail::verifier($user, 'confirmation', $request->input('code'));
        if (!$demande) {
            return back()->withErrors(['code' => 'Code incorrect ou expiré.']);
        }

        $ancienne = $user->email;
        if ($demande['adresse'] !== $ancienne && !(new Inedit('email', $user->id))->passes('email', $demande['adresse'])) {
            return back()->withErrors(['email' => 'Un compte utilise déjà cette adresse email.']);
        }
        $user->forceFill(['email' => $demande['adresse'], 'email_verified_at' => now()])->save();
        CodeEmail::oublier($user, 'motdepasse');

        if (mb_strtolower($ancienne) !== mb_strtolower($demande['adresse'])) {
            Courriel::envoyer($ancienne, new MessageInsti('Votre adresse email a changé', 'Adresse email modifiée', [
                "Bonjour {$user->name},",
                'L’adresse de votre compte est maintenant ' . Courriel::masquer($demande['adresse']) . '.',
                'Si ce n’est pas vous, contactez immédiatement l’administration de l’INSTI.',
            ]));
        }

        return redirect()->route('profil')->with('success', 'Adresse email confirmée.');
    }
}
