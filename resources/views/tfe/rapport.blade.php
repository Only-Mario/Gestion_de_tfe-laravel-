@extends('layouts.app')
@section('title', \Illuminate\Support\Str::limit($tfe->theme, 110))
@section('description', \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', strip_tags($tfe->resume ?: $tfe->theme . ', par ' . $tfe->auteurs)), 155))
@section('robots', 'index, follow')
@section('canonical', $tfe->url_publique)
@section('og_type', 'article')
@push('head')
<script type="application/ld+json">{!! json_encode(array_filter(['@context' => 'https://schema.org', '@type' => 'Thesis', 'name' => $tfe->theme, 'author' => ['@type' => 'Person', 'name' => $tfe->auteurs], 'datePublished' => $tfe->annee_de_realisation, 'inSupportOf' => optional($tfe->niveau)->nom, 'abstract' => $tfe->resume ?: null, 'inLanguage' => 'fr', 'url' => $tfe->url_publique, 'sourceOrganization' => ['@type' => 'CollegeOrUniversity', 'name' => 'INSTI de Lokossa', 'url' => 'https://insti.edu.bj/']]), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
@section('content')
    <article class="stack">
        <a class="btn btn-sm" href="{{ route('welcome') }}"><i class="fas fa-arrow-left"></i>Tous les rapports</a>
        <header class="stack">
            <div class="chips"><x-niveau :tfe="$tfe" /><span class="tag">{{ $tfe->filiere ? $tfe->filiere->libelle : $tfe->groupe_pedagogique }}</span><span class="tag">{{ $tfe->annee_de_realisation }}</span></div>
            <h1>{{ $tfe->theme }}</h1>
            <p class="muted">{{ $tfe->auteurs }}</p>
            <a class="btn btn-filled" href="{{ route('tfe.show', $tfe) }}" rel="nofollow"><i class="fas fa-file-pdf"></i>{{ Auth::check() ? 'Ouvrir le PDF' : 'Se connecter pour lire le PDF' }}</a>
        </header>
        <section class="card"><h2>Résumé</h2><div class="resume">{{ $tfe->resume ?: 'Aucun résumé n’a été ajouté à ce rapport.' }}</div></section>
        <section class="card"><h2>Encadrement</h2>
            <dl class="info"><dt>Maître de mémoire</dt><dd>{{ $tfe->maitre_memoire }}</dd><dt>Tuteur de stage</dt><dd>{{ $tfe->tuteur_de_stage }}</dd><dt>Lieu de stage</dt><dd>{{ $tfe->lieu_de_stage }}</dd></dl>
        </section>
    </article>
@endsection
