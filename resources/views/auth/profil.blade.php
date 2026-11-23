@extends('layouts.app')
@section('title', $user->is_admin ? 'Mon compte' : 'Mes documents')
@section('content')
    <div class="page-head"><div><h1>{{ $user->is_admin ? 'Mon compte' : 'Mes documents' }}</h1><p>{{ $user->name }}</p></div>
        @if (!$user->is_admin)<a class="btn btn-filled" href="{{ route('tfe.create') }}"><i class="fas fa-plus"></i>Ajouter un document</a>@endif
    </div>
    @if (session('recu'))
        <section class="card receipt"><div><h2>Votre reçu est prêt</h2><p>Il porte votre numéro matricule et un code de vérification.</p></div><a class="btn btn-filled" href="{{ route('tfe.attestation', session('recu')) }}"><i class="fas fa-receipt"></i>Télécharger le reçu</a></section>
    @endif
    <div class="stack">
        @foreach ($tfes as $tfe)
            <article class="card">
                <div class="chips"><x-niveau :tfe="$tfe" /><x-statut :tfe="$tfe" /><span class="tag">{{ $tfe->filiere ? $tfe->filiere->libelle : $tfe->groupe_pedagogique }}</span></div>
                <h2 style="margin:14px 0 6px">{{ $tfe->theme }}</h2>
                <p class="muted">{{ $tfe->auteurs }} · {{ $tfe->annee_de_realisation }} · déposé le {{ optional($tfe->created_at)->format('d/m/Y') }}</p>
                @if ((int) $tfe->status === 2)<div class="alert alert-bad"><i class="fas fa-times-circle"></i><span>{{ $tfe->motif_rejet ?: 'Document rejeté.' }} Contactez l’administration pour la suite.</span></div>@endif
                <div class="row">
                    <a class="btn btn-tonal btn-sm" href="{{ route('tfe.show', $tfe) }}"><i class="fas fa-file-pdf"></i>Ouvrir le PDF</a>
                    <a class="btn btn-outlined btn-sm" href="{{ route('tfe.attestation', $tfe->id) }}"><i class="fas fa-receipt"></i>Reçu</a>
                    <form class="inline" method="POST" action="{{ route('tfe.recu', $tfe->id) }}">@csrf<button class="btn btn-sm" type="submit"><i class="fas fa-envelope"></i>Reçu par email</button></form>
                    @if ($tfe->isEditableBy($user))<a class="btn btn-sm" href="{{ route('editTfe', $tfe->id) }}"><i class="fas fa-edit"></i>Modifier</a>@endif
                </div>
            </article>
        @endforeach
        @if ($tfes->isEmpty() && !$user->is_admin)
            <div class="card empty"><i class="fas fa-folder-open"></i><h2>Aucun document déposé</h2><p>Commencez par choisir votre niveau et votre filière.</p><a class="btn btn-filled" href="{{ route('tfe.create') }}">Ajouter un document</a></div>
        @endif
        <section class="card"><h2>Informations personnelles</h2>
            <dl class="info"><dt>Nom</dt><dd>{{ $user->name }}</dd><dt>Email</dt><dd>{{ $user->email }} <a class="tag {{ $user->email_verified_at ? 'tag-ok' : 'tag-wait' }}" href="{{ route('email.confirmer') }}">{{ $user->email_verified_at ? 'Confirmée' : 'À confirmer' }}</a></dd>
                @if (!$user->is_admin)<dt>Matricule</dt><dd>{{ $user->matricule }}</dd><dt>Niveau</dt><dd>{{ optional($user->niveau)->nom ?: '-' }}</dd><dt>Filière</dt><dd>{{ $user->entity }}</dd><dt>Année d’étude</dt><dd>{{ $user->study_year ? \App\Models\Niveau::libelleAnnee($user->study_year) : '-' }}</dd>@endif
            </dl>
            @if (!$user->is_admin)<div class="actions"><a class="btn btn-outlined" href="{{ route('profil.edit') }}"><i class="fas fa-edit"></i>Modifier mon profil</a></div>@endif
        </section>
    </div>
@endsection
