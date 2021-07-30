
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ config('app.name', 'Gestion des tfe') }}</title>
    <link rel="stylesheet" href="{{asset('css/assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="{{asset('css/assets/css/Navigation-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/assets/css/styles.css')}}">
    <link rel="stylesheet" type="text/css" href="css/assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean">
        <div class="container"><a class="navbar-brand" href="#">Administration</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
                id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item" role="presentation"><a class="nav-link" data-bs-hover-animate="flash" href="{{route('dashboard')}}">Tableau de Bord</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link border-primary active" data-bs-hover-animate="flash" href="{{route('store')}}">Etudiant</a></li>
                   
                    <li class="nav-item" role="presentation"> <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('a1dmin-logout-form').submit();">
                    {{ __('Se Déconnecter') }}
                </a>
                <form id="a1dmin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                   @csrf
               </form>
              </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="card shadow mb-4 text-center">
    <div class="row">
        <button class="btn"><a href="#admin">Ajouter un administrateur</a></button>
        <button class="btn"><a href="{{route('changepasswordView')}}">Modifier votre mot de passe</a></button>
    </div>
    <div class="card-header py-3 bg-dark">
        <h1 class="text-light">Liste des etudiants inscrits sur la platforme</h1>
    </div>
    <ul class="list-group list-group-flush">
        @forelse($users as $user)
        @if($user->is_admin==false)
        <li class="list-group-item mb-4">
            <div class="row align-items-center no-gutters">
                <div class="col mr-2">
                    <h2 class="mb-0 text-black"><strong>{{$user->name}}</strong></h2><span><u>Numéro Matricule</u> : {{$user->matricule}}</span>
                   <span><u>Filière</u> : {{$user->entity}}</span>
                   
            </div>
        </li>
        @endif
        @empty
         <div class="center">Aucun etudiant n'est inscrit pour le moment</div>
        @endforelse
    </ul>
</div>
<div class="card shadow mb-4 text-center">
    <div class="card-header py-3 bg-dark">
        <h1 class="text-light">Liste des administrateurs de la platforme</h1>
    </div>
    <ul class="list-group list-group-flush">
        @forelse($users as $user)
        @if($user->is_admin==true)
        <li class="list-group-item mb-4">
            <div class="row align-items-center no-gutters">
                <div class="col mr-2">
                    <h2 class="mb-0 text-black"><strong>{{$user->name}}</strong></h2><span><u>Email</u> : {{$user->email}}</span>
                   
            </div>
        </li>
        @endif
        @empty
        @endforelse
    </ul>
</div>
<!-- formulaire--->
<div class="card" id="admin">
    <div class="card-header text-center bg-dark">{{ __('Ajouter un Administrateur') }}</div>

    <div class="card-body">
        <form method="POST" action="{{ route('addAdmin') }}">
            @csrf
            <div class="form-group row">
                <label for="username" class="col-md-4 col-form-label text-md-right text-black">{{ __('Email') }}</label>
                <div class="col-md-6">
                    <input id="username" type="email" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username">

                    @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
            </div>
            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right text-black">{{ __('Mot de passe') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="username" class="col-md-4 col-form-label text-md-right text-black">{{ __('Votre mot de passe') }}</label>
                <div class="col-md-6">
                    <input id="passwordV" type="password" class="form-control @error('passwordV') is-invalid @enderror" name="passwordV" value="{{ old('passwordV') }}" required autocomplete="passwordV">

                    @error('passwordV')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
            </div>
            <div class="form-group row mb-0">
                <div class="col text-center">
                    <button type="submit" class="btn btn-success">
                        {{ __('Ajouter') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ////////////////// -->
<script src="{{asset('css/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('css/assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('css/assets/js/bs-init.js')}}"></script>
<script src="{{asset('css/assets/js/index.js')}}"></script>
</body>

</html>
-->