<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /** Un serveur d'envoi en panne ne doit pas afficher d'erreur technique. */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        try {
            $this->broker()->sendResetLink($this->credentials($request));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Lien de réinitialisation non envoyé : ' . get_class($e));
        }

        return $this->sendResetLinkResponse($request, null);
    }

    /** Même réponse que le compte existe ou non. */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return back()->with('status', 'Si un compte existe avec cette adresse, un lien vient de lui être envoyé.');
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return $this->sendResetLinkResponse($request, $response);
    }
}
