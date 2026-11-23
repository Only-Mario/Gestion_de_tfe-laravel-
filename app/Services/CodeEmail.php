<?php

namespace App\Services;

use App\Mail\MessageInsti;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;

/** Codes à 6 chiffres : 15 minutes, usage unique, 5 essais, 5 envois par quart d'heure. */
class CodeEmail
{
    const ENVOYE = 'envoye';
    const TROP = 'trop';
    const ECHEC = 'echec';

    private static function cle(User $user, $usage)
    {
        return "code-email:$usage:{$user->id}";
    }

    private static function empreinte(User $user, $usage, $code)
    {
        return hash_hmac('sha256', "$usage|{$user->id}|$code", config('app.key'));
    }

    public static function envoyer(User $user, $usage, $adresse, array $donnees = [])
    {
        $cle = self::cle($user, $usage);
        // Le quota est commun à tous les usages du compte
        $quota = "code-email-envois:{$user->id}";
        if (RateLimiter::tooManyAttempts($quota, 5)) {
            return self::TROP;
        }
        RateLimiter::hit($quota, 900);

        $code = (string) random_int(100000, 999999);
        $textes = [
            'confirmation' => ['Votre code de confirmation', 'Confirmez votre adresse email', 'Saisissez ce code sur la plateforme pour confirmer que cette adresse est bien la vôtre.'],
            'motdepasse' => ['Votre code pour changer de mot de passe', 'Changement de mot de passe', 'Saisissez ce code pour valider votre nouveau mot de passe.'],
        ][$usage];

        $envoye = Courriel::envoyer($adresse, new MessageInsti($textes[0], $textes[1], [
            "Bonjour {$user->name},",
            $textes[2],
            'Ce code est valable 15 minutes. Ne le communiquez à personne. Si vous n’êtes pas à l’origine de cette demande, ignorez ce message.',
        ], $code));
        if (!$envoye) {
            return self::ECHEC;
        }

        RateLimiter::clear("$cle:essais");
        Cache::put($cle, Crypt::encrypt([
            'empreinte' => self::empreinte($user, $usage, $code),
            'adresse' => $adresse,
            'donnees' => $donnees,
        ]), now()->addMinutes(15));

        return self::ENVOYE;
    }

    /** Code en cours : ['adresse' => ..., 'donnees' => [...]] ou null. */
    public static function enCours(User $user, $usage)
    {
        $contenu = Cache::get(self::cle($user, $usage));

        return $contenu ? Crypt::decrypt($contenu) : null;
    }

    /** Renvoie la demande si le code est juste (et le consomme), sinon null. */
    public static function verifier(User $user, $usage, $code)
    {
        $cle = self::cle($user, $usage);
        if (RateLimiter::tooManyAttempts("$cle:essais", 5)) {
            Cache::forget($cle);

            return null;
        }
        RateLimiter::hit("$cle:essais", 900);

        $demande = self::enCours($user, $usage);
        if (!$demande || !hash_equals($demande['empreinte'], self::empreinte($user, $usage, (string) $code))) {
            return null;
        }
        Cache::forget($cle);
        RateLimiter::clear("$cle:essais");

        return $demande;
    }

    public static function oublier(User $user, $usage)
    {
        Cache::forget(self::cle($user, $usage));
    }

    public static function message($resultat, $adresse)
    {
        return [
            self::ENVOYE => ['success', 'Un code vient d’être envoyé à ' . Courriel::masquer($adresse) . '. Pensez à regarder les indésirables.'],
            self::TROP => ['error', 'Trop de codes demandés. Réessayez dans 15 minutes.'],
            self::ECHEC => ['error', 'L’email n’a pas pu être envoyé. Réessayez plus tard.'],
        ][$resultat];
    }
}
