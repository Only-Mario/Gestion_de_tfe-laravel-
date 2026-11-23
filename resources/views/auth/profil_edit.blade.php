@extends('layouts.app')
@section('title', 'Modifier mon profil')
@section('content')
    <section class="card auth auth-wide">
        <h1>Modifier mon profil</h1>
        <p class="muted">Votre grade dépend de la filière : une filière de master vous fait passer en master. Vos documents déjà déposés ne changent pas.</p>
        <form class="stack" method="POST" action="{{ route('profil.update') }}" data-niveaux>
            @csrf
            <div class="fields">
                <x-field name="entity" label="Filière" type="select" full required data-filiere>
                    <option value="">Choisir une filière</option>
                    @foreach ($niveaux as $niveau)
                        @if ($niveau->filieresActives->count())<optgroup label="{{ $niveau->nom }}" data-niveau="{{ $niveau->id }}">@foreach ($niveau->filieresActives as $f)<option value="{{ $f->nom }}" @if (old('entity', $user->entity) === $f->nom) selected @endif>{{ $f->libelle }}</option>@endforeach</optgroup>@endif
                    @endforeach
                </x-field>
                <x-field name="study_year" label="Année d’étude" type="select" full required data-annee>
                    <option value="">Choisir une année</option>
                    @foreach ($niveaux as $niveau)<optgroup label="{{ $niveau->nom }}" data-niveau="{{ $niveau->id }}">@foreach ($niveau->annees as $v => $l)<option value="{{ $v }}" @if (old('study_year', $user->study_year) == $v) selected @endif>{{ $l }}</option>@endforeach</optgroup>@endforeach
                </x-field>
                @if (!$user->matricule)<x-field name="matricule" label="N° matricule" full required maxlength="30" />@endif
            </div>
            <div class="actions"><a class="btn" href="{{ route('profil') }}">Annuler</a><button class="btn btn-filled" type="submit">Enregistrer</button></div>
        </form>
    </section>
@endsection
