
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
        <div id="homeA">
    <h1 class="text-center">Bienvenue dans L'administration des tfe</h1>
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

