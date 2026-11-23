@extends('layouts.app')
@section('title', 'Modifier le document')
@section('content')
    <div class="page-head"><div><h1>Modifier le document</h1><p>{{ $tfe->theme }}</p></div></div>
    @if (!Auth::user()->is_admin)
        <div class="alert"><i class="fas fa-info-circle" aria-hidden="true"></i><span>Toute modification doit être justifiée. Votre document ne sera plus public jusqu’à sa validation par un administrateur.</span></div>
    @endif
    <form class="stack" method="POST" action="{{ route('updateTfe') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="tfe_id" value="{{ $tfe->id }}">
        <section class="card"><h2>{{ optional($tfe->niveau)->nom ?: 'Filière' }}</h2>
            <x-field name="filiere_id" label="Filière" type="select" required>
                @foreach ($filieres as $f)<option value="{{ $f->id }}" @if (old('filiere_id', $tfe->filiere_id) == $f->id) selected @endif>{{ $f->libelle }}</option>@endforeach
            </x-field>
        </section>
        @include('tfe._form')
        <section class="card"><h2>Fichier</h2><x-field name="document" label="Nouveau PDF (facultatif)" type="file" accept="application/pdf" hint="PDF, 10 Mo au maximum. Sans nouveau fichier, l’actuel est conservé." /></section>
        @if (!Auth::user()->is_admin)
            <section class="card"><h2>Justification</h2><div class="fields">
                <x-field name="motif" label="Motif de la modification" type="textarea" full required minlength="10" maxlength="2000" />
                <x-field name="justificatif" label="Justificatif (PDF)" type="file" accept="application/pdf" full required />
            </div></section>
        @endif
        <div class="actions">
            @if ($tfe->isDeletableBy(Auth::user()))<button class="btn btn-danger" type="submit" form="suppression">Supprimer</button>@endif
            <button class="btn btn-filled" type="submit">Enregistrer</button>
        </div>
    </form>
    @if ($tfe->isDeletableBy(Auth::user()))
        <form id="suppression" method="POST" action="{{ route('tfeDelete', $tfe->id) }}" data-confirm="Supprimer définitivement ce document ?">@csrf @method('DELETE')</form>
    @endif
@endsection
