<?php

namespace App\Services;

use App\Http\Controllers\RessourceController;
use Illuminate\Mail\Transport\Transport;
use Illuminate\Support\Facades\Http;
use Swift_Attachment;
use Swift_Image;
use Swift_Mime_SimpleMessage;

/**
 * Envoi depuis le compte Gmail lui-même, par HTTPS (script Google Apps Script, voir docs/gmail-relais.gs) :
 * utile quand l'hébergeur bloque les ports SMTP.
 */
class GmailRelaisTransport extends Transport
{
    private $url;
    private $cle;

    public function __construct($url, $cle)
    {
        $this->url = $url;
        $this->cle = $cle;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        if (!preg_match('#^https://script\.google\.com/macros/s/[\w-]+/exec$#', (string) $this->url) || strlen((string) $this->cle) < 32) {
            throw new \RuntimeException('GMAIL_RELAIS_URL ou GMAIL_RELAIS_CLE incorrect');
        }
        $expediteur = $message->getFrom();
        $donnees = [
            'cle' => $this->cle,
            'to' => implode(',', array_keys($message->getTo())),
            'nom' => (string) current($expediteur),
            'subject' => $message->getSubject(),
            'text' => '',
            'html' => preg_replace('/src="cid:[^"]*"/', 'src="' . e($this->logo()) . '"', $message->getBody()),
            'pieces' => [],
        ];
        foreach ($message->getChildren() as $partie) {
            if ($partie instanceof Swift_Attachment && !$partie instanceof Swift_Image && $partie->getDisposition() === 'attachment') {
                $donnees['pieces'][] = ['nom' => $partie->getFilename(), 'type' => $partie->getContentType(), 'contenu' => base64_encode($partie->getBody())];
            } elseif ($partie->getContentType() === 'text/plain') {
                $donnees['text'] = $partie->getBody();
            }
        }

        $reponse = Http::asJson()->timeout(25)->post($this->url, $donnees);
        if ($reponse->json('ok') !== true) {
            throw new \RuntimeException('Relais Gmail ' . $reponse->status() . ' : ' . mb_substr((string) $reponse->json('erreur', 'réponse inattendue (déploiement ou accès du script)'), 0, 150));
        }

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }

    private function logo()
    {
        $chemin = public_path(RessourceController::FICHIERS['logo'][0]);

        return rtrim(config('app.url'), '/') . route('ressource', ['version' => substr(md5_file($chemin), 0, 12), 'nom' => 'logo'], false);
    }
}
