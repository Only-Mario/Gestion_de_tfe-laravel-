@extends('layouts.app')
@section('title', 'Créer un compte')
@section('content')
    <section class="card auth auth-wide">
        <h1>Créer un compte étudiant</h1>
        <form class="stack" method="POST" action="{{ route('register') }}" data-niveaux>
            @csrf
            <fieldset class="choices"><legend class="small muted" style="margin-bottom:10px">Votre niveau actuel</legend>
                @foreach ($niveaux as $niveau)
                    <label class="choice"><input type="radio" name="niveau" value="{{ $niveau->id }}" required @if (old('niveau') == $niveau->id) checked @endif><b>{{ $niveau->nom }}</b><span class="small">{{ $niveau->filieresActives->pluck('nom')->implode(', ') }}</span></label>
                @endforeach
            </fieldset>
            <div class="fields">
                <x-field name="entity" label="Filière" type="select" required data-filiere>
                    <option value="">Choisir une filière</option>
                    @foreach ($niveaux as $niveau)<optgroup label="{{ $niveau->nom }}" data-niveau="{{ $niveau->id }}">@foreach ($niveau->filieresActives as $f)<option value="{{ $f->nom }}" @if (old('entity') === $f->nom) selected @endif>{{ $f->libelle }}</option>@endforeach</optgroup>@endforeach
                </x-field>
                <x-field name="study_year" label="Année d’étude" type="select" required data-annee>
                    <option value="">Choisir une année</option>
                    @foreach ($niveaux as $niveau)<optgroup label="{{ $niveau->nom }}" data-niveau="{{ $niveau->id }}">@foreach ($niveau->annees as $v => $l)<option value="{{ $v }}" @if (old('study_year') == $v) selected @endif>{{ $l }}</option>@endforeach</optgroup>@endforeach
                </x-field>
                <x-field name="name" label="Nom et prénoms" required autocomplete="name" maxlength="255" />
                <x-field name="matricule" label="N° matricule" required maxlength="30" />
                <x-field name="email" label="Adresse email" type="email" full required autocomplete="email" />
                <x-field name="password" label="Mot de passe" type="password" required autocomplete="new-password" hint="8 caractères au moins, avec des lettres et des chiffres." />
                <x-field name="password_confirmation" label="Confirmer le mot de passe" type="password" required autocomplete="new-password" />
            </div>
            <button class="btn btn-filled btn-block" type="submit">Créer mon compte</button>
        </form>
        <p style="margin:16px 0 0">Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
    </section>
@endsection
