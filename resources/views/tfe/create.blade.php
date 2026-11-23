@extends('layouts.app')
@section('title', 'Ajouter un document')
@section('content')
    @if (!$filiere)
        <div class="page-head"><div><h1>Ajouter un document</h1><p>Un seul document par niveau : licence, master, doctorat.</p></div></div>
        <form class="stack" method="GET" action="{{ route('tfe.create') }}" data-niveaux>
            <section class="card"><h2 id="n">1. Niveau</h2>
                <fieldset class="choices" aria-labelledby="n">
                    @foreach ($niveaux as $niveau)
                        @php($depose = $deposes->get($niveau->id))
                        @php($grade = $niveau->ordre > $ordre)
                        @php($ferme = $depose || $grade || $niveau->filieresActives->isEmpty())
                        <label class="choice">
                            <input type="radio" name="niveau" value="{{ $niveau->id }}" required @if ($ferme) disabled @elseif (Auth::user()->niveau_id == $niveau->id) checked @endif>
                            <b>{{ $niveau->nom }}</b>
                            <span class="small">
                                @if ($depose) Vous avez déjà un document de {{ mb_strtolower($niveau->nom) }} en {{ $depose->filiere ? $depose->filiere->libelle : $depose->groupe_pedagogique }}.
                                @elseif ($niveau->filieresActives->isEmpty()) Aucune filière ouverte pour le moment.
                                @elseif ($grade) Votre profil n’a pas encore ce grade. <a href="{{ route('profil.edit') }}">Mettre à jour mon profil</a>
                                @else Dépôt possible @endif
                            </span>
                        </label>
                    @endforeach
                </fieldset>
            </section>
            <section class="card"><h2>2. Filière</h2>
                <x-field name="filiere" label="Filière" type="select" required data-filiere>
                    <option value="">Choisir une filière</option>
                    @foreach ($niveaux as $niveau)
                        @if (!$deposes->has($niveau->id) && $niveau->ordre <= $ordre && $niveau->filieresActives->count())
                            <optgroup label="{{ $niveau->nom }}" data-niveau="{{ $niveau->id }}">
                                @foreach ($niveau->id == Auth::user()->niveau_id ? $niveau->filieresActives->where('nom', Auth::user()->entity) : $niveau->filieresActives as $f)<option value="{{ $f->id }}">{{ $f->libelle }}</option>@endforeach
                            </optgroup>
                        @endif
                    @endforeach
                </x-field>
                <div class="actions"><a class="btn" href="{{ route('profil') }}">Annuler</a><button class="btn btn-filled" type="submit">Continuer</button></div>
            </section>
        </form>
    @else
        <div class="page-head"><div><h1>Document de {{ mb_strtolower($filiere->niveau->nom) }}</h1><p>{{ $filiere->libelle }}</p></div><a class="btn btn-outlined" href="{{ route('tfe.create') }}">Changer</a></div>
        <form class="stack" method="POST" action="{{ route('tfe.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="filiere_id" value="{{ $filiere->id }}">
            @error('filiere_id')<div class="alert alert-bad">{{ $message }}</div>@enderror
            @include('tfe._form', ['tfe' => null])
            <section class="card"><h2>Fichier</h2><x-field name="document" label="Document PDF" type="file" accept="application/pdf" required hint="PDF, 10 Mo au maximum." /></section>
            <div class="actions"><a class="btn" href="{{ route('profil') }}">Annuler</a><button class="btn btn-filled" type="submit"><i class="fas fa-upload"></i>Déposer</button></div>
        </form>
    @endif
@endsection
