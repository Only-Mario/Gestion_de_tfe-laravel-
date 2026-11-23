<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

/** Tous les emails de la plateforme partagent ce modèle. */
class MessageInsti extends Mailable
{
    use Queueable;

    public $titre;
    public $lignes;
    public $code;
    public $bouton;

    public function __construct($sujet, $titre, array $lignes, $code = null, ?array $bouton = null)
    {
        $this->subject($sujet);
        $this->titre = $titre;
        $this->lignes = $lignes;
        $this->code = $code;
        $this->bouton = $bouton;
    }

    public function build()
    {
        return $this->view('emails.message')->text('emails.message_texte');
    }
}
