<?php

namespace App\Http\Controllers;

/**
 * Sert la feuille de style, le script et le logo depuis l'application.
 * Sur le serveur, le dossier web est distinct de « public » : ces fichiers n'y sont donc jamais périmés.
 */
class RessourceController extends Controller
{
    const FICHIERS = [
        'style' => ['css/m3.css', 'text/css; charset=UTF-8'],
        'script' => ['js/m3.js', 'application/javascript; charset=UTF-8'],
        'logo' => ['images/logo_insti.png', 'image/png'],
    ];

    /** Adresse versionnée : elle change dès que le contenu change. */
    public static function url($nom)
    {
        $chemin = public_path(self::FICHIERS[$nom][0]);

        return route('ressource', ['version' => substr(md5_file($chemin), 0, 12), 'nom' => $nom]);
    }

    public function show($version, $nom)
    {
        abort_unless(isset(self::FICHIERS[$nom]), 404);
        [$chemin, $type] = self::FICHIERS[$nom];

        return response()->file(public_path($chemin), [
            'Content-Type' => $type,
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
