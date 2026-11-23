@extends('layouts.app')
@php($actifs = array_filter(\Illuminate\Support\Arr::only($filtres, ['kw', 'niveau', 'filiere', 'annee', 'tri'])))
@section('title', 'Rapports et mémoires de fin d’études')
@section('robots', count($actifs) || $tfes->currentPage() > 1 ? 'noindex, follow' : 'index, follow')
@section('canonical', route('welcome'))
@push('head')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => 'INSTI - Rapports de fin d’études', 'url' => route('welcome'), 'inLanguage' => 'fr', 'publisher' => ['@type' => 'CollegeOrUniversity', 'name' => 'Institut National Supérieur de Technologie Industrielle de Lokossa', 'url' => 'https://insti.edu.bj/']], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
@section('content')
    <section class="hero">
        <h1>Rapports et mémoires de fin d’études</h1>
        <p>Consultez les travaux des étudiants de l’INSTI de Lokossa, par niveau, filière et année.</p>
        <form class="search" method="GET" action="{{ route('welcome') }}" role="search">
            @foreach (['niveau', 'filiere', 'annee', 'tri'] as $cle)@if (!empty($filtres[$cle]))<input type="hidden" name="{{ $cle }}" value="{{ $filtres[$cle] }}">@endif @endforeach
            <i class="fas fa-search" aria-hidden="true"></i>
            <input type="search" name="kw" value="{{ $filtres['kw'] ?? '' }}" maxlength="100" placeholder="Thème, auteur, encadrant, lieu de stage" aria-label="Rechercher">
            <button class="btn btn-filled" type="submit">Rechercher</button>
        </form>
    </section>

    <div class="stack">
        <nav class="chips" aria-label="Niveau">
            <a class="chip" @if (empty($filtres['niveau'])) aria-current="true" @endif href="{{ route('welcome', \Illuminate\Support\Arr::except($actifs, ['niveau', 'filiere'])) }}">Tous <span>{{ $parNiveau->sum() }}</span></a>
            @foreach ($niveaux as $niveau)
                <a class="chip" @if (($filtres['niveau'] ?? null) == $niveau->id) aria-current="true" @endif
                    href="{{ route('welcome', ['niveau' => $niveau->id] + \Illuminate\Support\Arr::except($actifs, ['niveau', 'filiere'])) }}">{{ $niveau->nom }} <span>{{ $parNiveau[$niveau->id] ?? 0 }}</span></a>
            @endforeach
        </nav>

        <form class="fields filters" method="GET" action="{{ route('welcome') }}" data-autosubmit>
            @foreach (['kw', 'niveau'] as $cle)@if (!empty($filtres[$cle]))<input type="hidden" name="{{ $cle }}" value="{{ $filtres[$cle] }}">@endif @endforeach
            <x-field name="filiere" label="Filière" type="select">
                <option value="">Toutes les filières</option>
                @foreach ($niveaux as $niveau)
                    @if ((empty($filtres['niveau']) || $filtres['niveau'] == $niveau->id) && $niveau->filieres->count())
                        <optgroup label="{{ $niveau->nom }}">
                            @foreach ($niveau->filieres as $f)<option value="{{ $f->id }}" @if (($filtres['filiere'] ?? null) == $f->id) selected @endif>{{ $f->libelle }}</option>@endforeach
                        </optgroup>
                    @endif
                @endforeach
            </x-field>
            <x-field name="annee" label="Année" type="select">
                <option value="">Toutes les années</option>
                @foreach ($annees as $annee)<option @if (($filtres['annee'] ?? null) == $annee) selected @endif>{{ $annee }}</option>@endforeach
            </x-field>
            <x-field name="tri" label="Trier par" type="select">
                @foreach (\App\Models\Tfe::TRIS as $cle => $libelle)<option value="{{ $cle }}" @if (($filtres['tri'] ?? 'recent') === $cle) selected @endif>{{ $libelle }}</option>@endforeach
            </x-field>
            <button class="btn btn-filled" type="submit"><i class="fas fa-filter"></i>Appliquer</button>
        </form>

        <div class="row between">
            <span class="muted">{{ $tfes->total() }} {{ $tfes->total() > 1 ? 'rapports' : 'rapport' }}</span>
            @if (count($actifs))<a class="btn btn-sm" href="{{ route('welcome') }}"><i class="fas fa-times"></i>Effacer les filtres</a>@endif
        </div>

        @if ($tfes->count())
            <div class="grid">
                @foreach ($tfes as $tfe)
                    <article class="card report">
                        <div class="chips"><x-niveau :tfe="$tfe" /><span class="tag">{{ $tfe->groupe_pedagogique }}</span></div>
                        <h2><a href="{{ $tfe->url_publique }}">{{ $tfe->theme }}</a></h2>
                        <div class="meta">{{ $tfe->auteurs }} · {{ $tfe->annee_de_realisation }}</div>
                    </article>
                @endforeach
            </div>
            {{ $tfes->links('vendor.pagination.m3') }}
        @else
            <div class="card empty"><i class="fas fa-search"></i><h2>Aucun rapport trouvé</h2><p>Essayez d’autres mots ou effacez les filtres.</p></div>
        @endif
    </div>
@endsection
