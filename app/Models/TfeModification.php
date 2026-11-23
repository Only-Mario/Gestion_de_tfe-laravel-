<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Trace d'une modification faite par l'auteur : justificatif et version précédente. */
class TfeModification extends Model
{
    protected $table = 'tfe_modifications';

    protected $fillable = [
        'tfe_id',
        'user_id',
        'motif',
        'justificatif_path',
        'ancien_document_path',
        'anciennes_donnees',
    ];

    protected $casts = [
        'anciennes_donnees' => 'array',
    ];
}
