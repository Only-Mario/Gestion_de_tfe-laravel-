@extends('layouts.app')
@section('title', 'Vérification d’attestation')
@section('content')
    <section class="card auth auth-wide">
        @if ($valid)
            <div class="alert alert-ok"><i class="fas fa-check-circle"></i><span>Attestation authentique, délivrée par la plateforme de l’INSTI.</span></div>
            <dl class="info"><dt>Référence</dt><dd>{{ $reference }}</dd><dt>Étudiant(e)</dt><dd>{{ optional($tfe->user)->name }}</dd><dt>Matricule</dt><dd>{{ optional($tfe->user)->matricule }}</dd><dt>Niveau</dt><dd>{{ optional($tfe->niveau)->nom }}</dd><dt>Filière</dt><dd>{{ $tfe->filiere ? $tfe->filiere->libelle : $tfe->groupe_pedagogique }}</dd><dt>Thème</dt><dd>{{ $tfe->theme }}</dd><dt>Déposé le</dt><dd>{{ $tfe->created_at->format('d/m/Y à H\hi') }}</dd></dl>
        @else
            <div class="alert alert-bad"><i class="fas fa-times-circle"></i><span>Ce lien ne correspond à aucune attestation délivrée par la plateforme.</span></div>
        @endif
    </section>
@endsection
