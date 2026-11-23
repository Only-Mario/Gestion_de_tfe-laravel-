<?php

namespace App\Services;

use Illuminate\Mail\Transport\Transport;
use Swift_Mime_SimpleMessage;

/**
 * Messagerie locale de l'hébergeur, comme dans XWE Speed (Symfony) : le code de retour est contrôlé.
 * Le transport d'origine (Swift, mode -t) annonce un succès même quand l'envoi échoue.
 */
class HebergeurTransport extends Transport
{
    private $commande;

    public function __construct($commande)
    {
        $this->commande = $commande;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $commande = $this->commande;
        $retour = $message->getReturnPath() ?: key($message->getSender() ?: $message->getFrom() ?: []);
        if ($retour && !preg_match('/\s-f/', $commande)) {
            $commande .= ' -f' . escapeshellarg($retour);
        }

        $texte = str_replace("\r\n", "\n", $message->toString());
        if (!preg_match('/\s-o?i(\s|$)/', $commande)) {
            $texte = str_replace("\n.", "\n..", $texte);
        }
        $this->executer($commande, $texte);

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }

    private function executer($commande, $texte)
    {
        if (!function_exists('proc_open')) {
            throw new \RuntimeException('proc_open est désactivé par l’hébergeur');
        }
        $processus = proc_open($commande, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $flux);
        if (!is_resource($processus)) {
            throw new \RuntimeException('sendmail n’a pas pu être lancé');
        }
        // Si sendmail s'arrête avant de tout lire, c'est son code de retour qui compte
        @fwrite($flux[0], $texte);
        fclose($flux[0]);
        $sortie = trim(stream_get_contents($flux[1]) . ' ' . stream_get_contents($flux[2]));
        fclose($flux[1]);
        fclose($flux[2]);
        $code = proc_close($processus);

        if ($code !== 0) {
            throw new \RuntimeException("sendmail a échoué (code $code) $sortie");
        }
    }
}
