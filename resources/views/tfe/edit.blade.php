@extends('layouts.app')

@section('content')
    <div class="fluid-container">
        <h3 class="text-dark px-3">Edit TFE</h3>
        <div class="row justify-content-center">
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header" style="background-color: #4169e1;">
                        <p class="m-0 font-weight-bold text-center" style="font-size: 20px;color: white;">Modifier votre
                            dossier</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updateTfe', $tfe) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-group row">
                                <label for="theme"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Thème') }}</label>

                                <div class="col-md-6">
                                    <input id="theme" type="text"
                                        class="form-control @error('theme') is-invalid @enderror" name="theme"
                                        value="{{ $tfe->theme }}" autofocus>

                                    @error('theme')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="auteurs"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Auteur(s)') }}</label>

                                <div class="col-md-6">
                                    <input id="auteurs" type="text"
                                        class="form-control @error('auteurs') is-invalid @enderror" name="auteurs"
                                        value="{{ $tfe->auteurs }}">

                                    @error('auteurs')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="annee_de_realisation"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Année de réalisation') }}</label>

                                <div class="col-md-6">

                                    <select class="form-control @error('annee_de_realisation') is-invalid @enderror"
                                        name="annee_de_realisation" id="annee_de_realisation"
                                        value="{{ $tfe->annee_de_realisation }}">
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    @error('annee_de_realisation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for=" groupe_pedagogique"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Groupe pédagogique') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control @error('groupe_pedagogique') is-invalid @enderror"
                                        name="groupe_pedagogique" id="entity" value="{{ $tfe->groupe_pedagogique }}">
                                        <option value="GEI"> Génie Electrique et Informatique (GEI)</option>
                                        <option value="GME"> Génie Electrique et Informatique (GEI)</option>
                                        <option value="GC"> Génie Civil (GC)</option>
                                    </select>

                                    @error('groupe_pedagogique')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tuteur_de_stage"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Tuteur de stage') }}</label>

                                <div class="col-md-6">
                                    <input id="tuteur_de_stage" type="text"
                                        class="form-control @error('tuteur_de_stage') is-invalid @enderror"
                                        name="tuteur_de_stage" value="{{ $tfe->tuteur_de_stage }}">

                                    @error('tuteur_de_stage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lieu_de_stage"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Lieu de stage') }}</label>

                                <div class="col-md-6">
                                    <input id="lieu_de_stage" type="text"
                                        class="form-control @error('tuteur_de_stage') is-invalid @enderror"
                                        name="lieu_de_stage" value="{{ $tfe->lieu_de_stage }}">

                                    @error('lieu_de_stage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email_tuteur"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Email du tuteur de stage') }}</label>

                                <div class="col-md-6">
                                    <input id="email_tuteur" type="text"
                                        class="form-control @error('email_tuteur') is-invalid @enderror" name="email_tuteur"
                                        value="{{ $tfe->email_tuteur }}">

                                    @error('email_tuteur')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="maitre_memoire"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Maitre mémoire') }}</label>

                                <div class="col-md-6">
                                    <input id="maitre_memoire" type="text"
                                        class="form-control @error('maitre_memoire') is-invalid @enderror"
                                        name="maitre_memoire" value="{{ $tfe->maitre_memoire }}">

                                    @error('maitre_memoire')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email_maitre_memoire"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Email du maitre mémoire') }}</label>

                                <div class="col-md-6">
                                    <input id="email_maitre_memoire" type="text"
                                        class="form-control @error('email_maitre_memoire') is-invalid @enderror"
                                        name="email_maitre_memoire" value="{{ $tfe->email_maitre_memoire }}">

                                    @error('email_maitre_memoire')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="document"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Document') }}</label>

                                <div class="col-md-6">
                                    <input id="document" type="file"
                                        class="form-control @error('document') is-invalid @enderror" name="document"
                                        value="{{ old('document') }}">

                                    @error('document')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-footer" style="background-color: #4169e1;">
                                <div class="row text-center">
                                    <div class="col">
                                        <button class="btn btn-success" type="button">
                                            <a style="color: white;"
                                                href="{{ route('updateTfe', $tfe->id) }}">Sauvegarder</a>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-danger" type="button">
                                            <a style="color: white;"
                                                href="{{ route('tfeDelete', $tfe->id) }}">Supprimer</a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
