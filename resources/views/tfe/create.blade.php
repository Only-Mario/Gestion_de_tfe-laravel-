@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('tfe.store') }}" enctype="multipart/form-data">
            <h3 class="text-dark mb-4">Ajouter un tfe</h3>
            <div class="row mb-3">
                <div class="col" id="left">
                    <div class="card shadow">
                        <div class="card-header py-3" style="background-color: #4169e1;">
                            <p class=" m-0 font-weight-bold text-center" style="font-size: 20px;color: white;">Partie 1</p>
                        </div>
                        <div class="card-body">

                            @csrf
                            <div class="form-group">
                                <label for="theme"><strong>{{ __('Theme') }}</strong></label>
                                <input id="theme" type="text"
                                    class="form-control @error('theme') is-invalid @enderror" name="theme"
                                    value="{{ old('theme') }}" autofocus>
                                @error('theme')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="auteurs"><strong>{{ __('Auteur') }}</strong></label> <input id="auteurs"
                                    type="text" class="form-control @error('auteurs') is-invalid @enderror"
                                    name="auteurs" value="{{ old('auteurs') }}">
                                @error('auteurs')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="annee_de_realisation"><strong>{{ __('Année') }}</strong>
                                        </label>
                                        <select class="form-control @error('annee_de_realisation') is-invalid @enderror"
                                            name="annee_de_realisation" id="annee_de_realisation"
                                            value="{{ old('annee_de_realisation') }}">
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                        @error('annee_de_realisation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong> {{ $message }} </strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="groupe_pedagogique"><strong>Filière</strong></label>
                                        <select class="form-control @error('groupe_pedagogique') is-invalid @enderror"
                                            name="groupe_pedagogique" id="entity"
                                            value="{{ old('groupe_pedagogique') }}">
                                            @foreach ($filieres as $filiere)
                                                <option value="{{ $filiere->id }}">{{ $filiere->description }} ({{ $filiere->nom }}) </option>
                                            @endforeach
                                        </select>
                                        @error('groupe_pedagogique')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" id='right'>
                    <div class="card shadow mb-3">
                        <div class="card-header py-3" style="background-color: #4169e1;">
                            <p class="m-0 font-weight-bold text-center" style="font-size: 20px;color: white;">Parie 2</p>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <input type="hidden" name="id_user">
                                <div class="col">
                                    <div class="form-group"><label for="tuteur_de_stage">
                                            <strong>{{ __('Tuteur de Stage') }}</strong></label>
                                        <input id="tuteur_de_stage" type="text"
                                            class="form-control @error('tuteur_de_stage') is-invalid @enderror"
                                            name="tuteur_de_stage" value="{{ old('tuteur_de_stage') }}">

                                        @error('tuteur_de_stage')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="email"><strong>{{ __('Email Tuteur de stage') }}</strong></label>
                                        <input id="email_tuteur" type="text"
                                            class="form-control @error('email_tuteur') is-invalid @enderror"
                                            name="email_tuteur" value="{{ old('email_tuteur') }}">
                                        @error('email_tuteur')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="maitre_memoire"><strong>{{ __('Maitre memoire') }}</strong></label>
                                        <input id="maitre_memoire" type="text"
                                            class="form-control @error('maitre_memoire') is-invalid @enderror"
                                            name="maitre_memoire" value="{{ old('maitre_memoire') }}">

                                        @error('maitre_memoire')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group"><label
                                            for="last_name"><strong>{{ __('Email Maitre memoire') }}</strong>
                                        </label>
                                        <input id="email_maitre_memoire" type="text"
                                            class="form-control @error('email_maitre_memoire') is-invalid @enderror"
                                            name="email_maitre_memoire" value="{{ old('email_maitre_memoire') }}">

                                        @error('email_maitre_memoire')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lieu_de_stage"><strong>{{ __('Lieu de stage') }}</strong></label>
                                <input id="lieu_de_stage" type="text"
                                    class="form-control @error('tuteur_de_stage') is-invalid @enderror"
                                    name="lieu_de_stage" value="{{ old('lieu_de_stage') }}">

                                @error('lieu_de_stage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="address"><strong>Document</strong></label> <input id="document" type="file"
                        class="form-control @error('document') is-invalid @enderror" name="document"
                        value="{{ old('document') }}" autocomplete="document">

                    @error('document')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="text-center row mb-4">
                <button class=" form-control btn btn-primary" type="submit">
                    Soumettre</button>

            </div>
        </form>
    </div>
@endsection

@section('footer')
    <footer>
        <div class="container">
            Copyright {{ date('Y') }}
        </div>
    </footer>
    </div>
@stop

<style>
    @media(max-width: 45rem) {
        .row {
            display: flex;
            flex-direction: column;
            align-content: space-around;
        }

        .col {
            margin: 5px;
        }
    }
</style>
