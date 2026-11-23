<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Reçu de dépôt {{ $reference }}</title>
    <style>
        @page { margin: 42px 52px 50px; }
        body { font-family: "DejaVu Sans", sans-serif; font-size: 11.5px; color: #191c21; }
        table { width: 100%; border-collapse: collapse; }
        .head td { vertical-align: middle; } .head img { width: 84px; height: 84px; }
        .head p { margin: 1px 0; text-align: center; } .head .s { font-size: 9.5px; }
        .band { height: 4px; background: #0d4293; border-bottom: 3px solid #83bd45; margin: 10px 0 18px; }
        h1 { margin: 0; text-align: center; font-size: 19px; color: #0d4293; text-transform: uppercase; }
        .ref { text-align: center; margin: 4px 0 14px; }
        .key { margin-bottom: 12px; } .key td { width: 33.3%; padding: 9px 12px; background: #eef2fb; border: 2px solid #fff; }
        .key span { display: block; font-size: 8.5px; color: #44474f; } .key b { font-size: 14px; color: #0d4293; }
        h2 { margin: 14px 0 4px; font-size: 11.5px; color: #0d4293; border-bottom: 1px solid #c4c6d0; padding-bottom: 3px; }
        .i td { padding: 4px 6px; vertical-align: top; } .i td.l { width: 32%; color: #44474f; }
        .foot { margin-top: 22px; } .foot td { vertical-align: top; } .foot img { width: 96px; height: 96px; }
        .tiny { font-size: 8px; color: #44474f; word-wrap: break-word; }
    </style>
</head>
<body>
    <table class="head"><tr>
        <td style="width:92px">@if ($logoDataUri)<img src="{{ $logoDataUri }}" alt="INSTI">@endif</td>
        <td><p><b>RÉPUBLIQUE DU BÉNIN</b></p><p class="s">Université Nationale des Sciences, Technologies, Ingénierie et Mathématiques (UNSTIM)</p><p><b>Institut National Supérieur de Technologie Industrielle (INSTI) de Lokossa</b></p></td>
        <td style="width:92px"></td>
    </tr></table>
    <div class="band"></div>
    <h1>Reçu de dépôt</h1>
    <div class="ref">N° {{ $reference }}</div>

    <table class="key"><tr>
        <td><span>N° MATRICULE</span><b>{{ $tfe->user->matricule ?: 'Non renseigné' }}</b></td>
        <td><span>NIVEAU</span><b>{{ $tfe->niveau->nom ?? '-' }}</b></td>
        <td><span>FILIÈRE</span><b>{{ $tfe->groupe_pedagogique }}</b></td>
    </tr></table>

    <p>L’INSTI atteste que l’étudiant(e) désigné(e) ci-dessous a déposé en ligne son document de fin d’études.</p>

    <h2>Étudiant(e)</h2>
    <table class="i">
        <tr><td class="l">Nom et prénoms</td><td><b>{{ $tfe->user->name }}</b></td></tr>
        <tr><td class="l">N° matricule</td><td><b>{{ $tfe->user->matricule ?: 'Non renseigné' }}</b></td></tr>
        <tr><td class="l">Email</td><td>{{ $tfe->user->email }}</td></tr>
        <tr><td class="l">Filière du document</td><td>{{ $tfe->filiere ? $tfe->filiere->libelle : $tfe->groupe_pedagogique }}</td></tr>
    </table>

    <h2>Document déposé</h2>
    <table class="i">
        <tr><td class="l">Thème</td><td><b>{{ $tfe->theme }}</b></td></tr>
        <tr><td class="l">Auteur(s)</td><td>{{ $tfe->auteurs }}</td></tr>
        <tr><td class="l">Année de réalisation</td><td>{{ $tfe->annee_de_realisation }}</td></tr>
        <tr><td class="l">Maître de mémoire</td><td>{{ $tfe->maitre_memoire }}</td></tr>
        <tr><td class="l">Tuteur de stage</td><td>{{ $tfe->tuteur_de_stage }}</td></tr>
        <tr><td class="l">Lieu de stage</td><td>{{ $tfe->lieu_de_stage }}</td></tr>
        <tr><td class="l">Déposé le</td><td>{{ $tfe->created_at->format('d/m/Y à H\hi') }}</td></tr>
        <tr><td class="l">Statut</td><td>{{ $tfe->statut_libelle }}</td></tr>
    </table>

    <table class="foot"><tr>
        <td style="width:112px"><img src="{{ $qrDataUri }}" alt="QR code"></td>
        <td><b>Vérification</b><br>Scannez le code pour vérifier ce reçu en ligne.<div class="tiny">{{ $verifyUrl }}</div></td>
        <td style="width:34%; text-align:center">Le Chef Service Informatique<br><br><span class="tiny">Généré électroniquement le {{ now()->format('d/m/Y à H\hi') }}.<br>Ne nécessite pas de signature manuscrite.</span></td>
    </tr></table>
</body>
</html>
