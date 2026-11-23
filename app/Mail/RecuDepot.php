<?php

namespace App\Mail;

use App\Models\Tfe;

/** Reçu de dépôt, joint en PDF. */
class RecuDepot extends MessageInsti
{
    private $pdf;
    private $fichier;

    public function __construct(Tfe $tfe, $pdf, $fichier)
    {
        parent::__construct('Votre reçu de dépôt', 'Document déposé', [
            "Bonjour {$tfe->user->name},",
            'Votre document de ' . mb_strtolower(optional($tfe->niveau)->nom ?: 'fin d’études') . " « {$tfe->theme} » est bien déposé.",
            "Votre reçu est joint à ce message, avec votre numéro matricule ({$tfe->user->matricule}). Conservez-le.",
        ], null, ['Voir mes documents', rtrim(config('app.url'), '/') . route('profil', [], false)]);
        $this->pdf = $pdf;
        $this->fichier = $fichier;
    }

    public function build()
    {
        return parent::build()->attachData($this->pdf, $this->fichier, ['mime' => 'application/pdf']);
    }
}
