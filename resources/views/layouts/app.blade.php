<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <link rel="stylesheet" href="{{asset('css/assets/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
  <link rel="stylesheet" href="{{asset('css/assets/fonts/fontawesome-all.min.css')}}">
  <link rel="stylesheet" type="text/css" href="css/assets/css/style.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'INSTI | TFE') }}</title>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <style>
  .search-by-entity-items, .search-by-year-items{
    display: none;
}
</style>
<!--     //********************-****-->

</head>

<body id="page-top" style="background-color: #f5f5f5;">

    <div id="wrapper">
        <!-- /*****************************-// -->
        
        @include('layouts.fullContainer')

        <div class="d-flex flex-column" id="content-wrapper"><div id="content">
            <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button><label class="justify-content-center">Gestion des tfe</label>

                </div>
            </nav>
            <main class="justify-content">
            <div class="container">
        @include('flash::message')
      </div>   
                @yield('content')
            </main> 
        </div>

        <div>
            <footer>
                <div class="container">
                    <p class="text-dark text-center">
                    INSTI &copy :Tous droits réservés {{ date('Y') }}
                    &middot</p>
                </div>
            </footer>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.1.11.1.js') }}" defer></script>
    <script src="{{ asset('js/index.js') }}" defer></script>
    <script src="css/assets/js/jquery.min.js"></script>
    <script src="css/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="css/assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="{{asset('css/assets/js/theme.js')}}"></script>

</body>
</html>
