@extends('layouts.app')

@section('content')
    <div class="p-5">
        <div>
            <progress id="progressBar" hidden="true"></progress>
        </div>
        <div class="card">
            <div class="card-header py-3" style="background-color: #4169e1;">
                <p class="m-0 font-weight-bold text-center" style="font-size: 20px; color: white;">Créer un compte</p>
            </div>
            <div class="card-body">
                <form class="user" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="mb-4">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                    placeholder="{{ __('Nom complet') }}" />
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <select class="form-control @error('entity') is-invalid @enderror" name="entity"
                                    value="{{ old('entity') }}" required autocomplete="entity">
                                    @foreach ($filieres as $filiere)
                                        <option value="{{ $filiere->nom }}">{{ $filiere->description }}
                                            ({{ $filiere->nom }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('entity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="mb-4">
                                <input class="form-control @error('matricule') is-invalid @enderror" name="matricule"
                                    value="{{ old('matricule') }}" required autocomplete="matricule" autofocus
                                    placeholder="{{ __('N° Matricule') }}" />
                                @error('matricule')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <select class="form-control @error('study_year') is-invalid @enderror" name="study_year"
                                    value="{{ old('study_year') }}" required autocomplete="study_year">
                                    <option value="3">3ème Année</option>
                                    <option value="2">2ème Année</option>
                                    <option value="1">1ère Année</option>
                                </select>
                                @error('study_year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" aria-describedby="emailHelp"
                            placeholder="{{ __('E-Mail Address') }}" />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password"
                                placeholder="{{ __('Mots de passe') }}" />
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" id="password-confirm"
                                placeholder="Mots de passe (confirmer)" name="password_confirmation"
                                autocomplete="new-password" />
                        </div>
                    </div>
                    <button style="background-color: #4169e1; font-size: 20px" class="btn btn-primary text-white"
                        id="submit-btn" type="submit"> S'inscrire
                    </button>
                </form>
            </div>
            <div class="text-center mb-3">
                <a class="small btn" href="{{ route('login') }}" style="font-size: 15px; text-decoration: underline;">Déjà
                    inscrit? Se connecter!
                </a>
            </div>
        </div>
    </div>
@endsection
