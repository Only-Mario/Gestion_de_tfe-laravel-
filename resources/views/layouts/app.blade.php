<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Rapports de fin d’études') | INSTI Lokossa</title>
    <meta name="description" content="@yield('description', 'Plateforme des rapports et mémoires de fin d’études (licence, master, doctorat) de l’INSTI de Lokossa, Bénin.')">
    <meta name="robots" content="@yield('robots', 'noindex, nofollow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta property="og:site_name" content="INSTI - Rapports de fin d’études">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'Rapports de fin d’études')">
    <meta property="og:description" content="@yield('description', 'Rapports et mémoires de fin d’études de l’INSTI de Lokossa.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="{{ \App\Http\Controllers\RessourceController::url('logo') }}">
    <meta name="twitter:card" content="summary">
    <meta name="theme-color" content="#0d4293">
    <link rel="icon" href="{{ \App\Http\Controllers\RessourceController::url('logo') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/assets/fonts/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ \App\Http\Controllers\RessourceController::url('style') }}">
    @stack('head')
</head>
<body>
    <a class="skip" href="#contenu">Aller au contenu</a>
    <div class="shell">
        @include('layouts.nav')
        <div class="scrim" data-nav-close></div>
        <div class="main">
            <header class="topbar">
                <button class="icon-btn" type="button" data-nav-toggle aria-controls="nav" aria-expanded="false" aria-label="Menu"><i class="fas fa-bars"></i></button>
                <img src="{{ \App\Http\Controllers\RessourceController::url('logo') }}" alt="" width="36" height="36">
                <strong>@yield('title', 'INSTI')</strong>
            </header>
            <main class="content" id="contenu">
                @foreach (['success' => ['alert-ok', 'check-circle'], 'error' => ['alert-bad', 'exclamation-circle'], 'status' => ['alert-ok', 'check-circle']] as $cle => $style)
                    @if (session($cle))
                        <div class="alert {{ $style[0] }}" role="status"><i class="fas fa-{{ $style[1] }}" aria-hidden="true"></i><span>{{ session($cle) }}</span></div>
                    @endif
                @endforeach
                @yield('content')
            </main>
            <footer class="foot">
                © {{ date('Y') }} INSTI de Lokossa · <a href="https://insti.edu.bj/" rel="noopener">insti.edu.bj</a>
            </footer>
        </div>
    </div>
    <script src="{{ \App\Http\Controllers\RessourceController::url('script') }}" defer></script>
</body>
</html>
