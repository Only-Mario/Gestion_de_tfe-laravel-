<?php

namespace App\Http\Middleware;

use Closure;

/** Un étudiant confirme son adresse avant de déposer ou de modifier un document. */
class EmailConfirme
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if (config('app.email_confirmation') && !$user->is_admin && !$user->email_verified_at) {
            return redirect()->route('email.confirmer')->with('error', 'Confirmez votre adresse email pour continuer : c’est là que votre reçu sera envoyé.');
        }

        return $next($request);
    }
}
