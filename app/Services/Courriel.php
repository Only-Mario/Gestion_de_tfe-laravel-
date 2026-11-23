<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Envoi qui ne bloque jamais l'action en cours : renvoie vrai si un serveur a accepté le message.
 * Uniquement le compte Gmail configuré : relais HTTPS, SMTP, puis messagerie de l'hébergeur.
 */
class Courriel
{
    /** Messageries à essayer, dans l'ordre. */
    public static function messageries()
    {
        $defaut = config('mail.default');
        if ($defaut !== 'failover') {
            return [$defaut];
        }
        $liste = config('mail.mailers.failover.mailers');

        return config('mail.mailers.relais.url') ? array_merge(['relais'], $liste) : $liste;
    }

    public static function envoyer($adresse, Mailable $message, array &$resultats = [])
    {
        foreach (self::messageries() as $messagerie) {
            try {
                Mail::mailer($messagerie)->to($adresse)->send(clone $message);
                $resultats[$messagerie] = 'accepté';

                return true;
            } catch (\Throwable $e) {
                $resultats[$messagerie] = self::nettoyer($e->getMessage()) ?: get_class($e);
                Log::error("Email non envoyé par « $messagerie » (" . get_class($message) . ') : ' . $resultats[$messagerie]);
            }
        }

        return false;
    }

    /** Retire adresses et identifiants d'un message d'erreur avant de l'afficher ou de le journaliser. */
    public static function nettoyer($texte)
    {
        foreach (['mail.mailers.smtp.password', 'mail.mailers.smtp.username', 'mail.mailers.relais.cle', 'mail.mailers.relais.url'] as $cle) {
            if ($secret = (string) config($cle)) {
                $texte = str_replace([$secret, base64_encode($secret)], '***', $texte);
            }
        }
        $texte = preg_replace('/[\w.+-]+@[\w.-]+/', '***', $texte);

        return mb_substr(trim(preg_replace('/\s+/', ' ', $texte)), 0, 250);
    }

    /** ma***@gmail.com */
    public static function masquer($adresse)
    {
        return preg_replace('/^(.{1,2})[^@]*/u', '$1***', (string) $adresse);
    }
}
