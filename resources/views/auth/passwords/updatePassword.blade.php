
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
     <link rel="stylesheet" href="{{asset('css/assets/css/Simple-Slider.css')}}">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean">
        <div class="container">
            <a class="navbar-brand" href="{{route('admin.home')}}">Administration</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1">
                <span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
                id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-hover-animate="flash" href="{{route('dashboard')}}">Tableau de Bord</a></li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-hover-animate="flash" href="{{route('store')}}">Etudiant</a></li>
                   
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


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modifier votre mot de passe') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('passwordUpdate',Auth::user()->id)}}">
                        @csrf

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Ancien mot depasse') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}" autofocus>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new_password" class="col-md-4 col-form-label text-md-right">{{ __('Nouveau mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required autocomplete="new_password">

                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="comfirm_new_password" class="col-md-4 col-form-label text-md-right">{{ __('Confirmez votre mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="comfirm_new_password" type="password" class="form-control" name="comfirm_new_password" required autocomplete="comfirm_new_password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Valider') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<footer>
        
    </footer>
     <script src="{{asset('css/assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('css/assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('css/assets/js/bs-init.js')}}"></script>
    <script src="{{asset('css/assets/js/index.js')}}"></script>
</body>

</html>


