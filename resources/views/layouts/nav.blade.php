@php($ici = function (...$routes) { return request()->routeIs(...$routes) ? 'aria-current=page' : ''; })
<nav class="drawer" id="nav" aria-label="Navigation principale">
    <a class="brand" href="{{ route('welcome') }}">
        <span class="brand-mark"><img src="{{ \App\Http\Controllers\RessourceController::url('logo') }}" alt="Logo de l’INSTI" width="56" height="56"></span>
        <span><strong>INSTI Lokossa</strong><span>Rapports de fin d’études</span></span>
    </a>

    @guest
        <a class="nav-item" {{ $ici('welcome', 'rapport') }} href="{{ route('welcome') }}"><i class="fas fa-book-open"></i>Rapports</a>
        <a class="nav-item" {{ $ici('login') }} href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i>Se connecter</a>
        <a class="nav-item" {{ $ici('register') }} href="{{ route('register') }}"><i class="fas fa-user-plus"></i>Créer un compte</a>
    @elseif (Auth::user()->is_admin)
        @php($attente = \App\Models\Tfe::where('status', 0)->count())
        <div class="nav-title">Administration</div>
        <a class="nav-item" {{ $ici('dashboard') }} href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i>Documents @if ($attente)<span class="count">{{ $attente }}<span class="sr"> à valider</span></span>@endif</a>
        <a class="nav-item" {{ $ici('filieres') }} href="{{ route('filieres') }}"><i class="fas fa-sitemap"></i>Filières</a>
        <a class="nav-item" {{ $ici('store') }} href="{{ route('store') }}"><i class="fas fa-users"></i>Utilisateurs</a>
        <a class="nav-item" {{ $ici('journal') }} href="{{ route('journal') }}"><i class="fas fa-history"></i>Journal</a>
        <div class="nav-title">Site</div>
        <a class="nav-item" {{ $ici('welcome', 'rapport') }} href="{{ route('welcome') }}"><i class="fas fa-book-open"></i>Rapports publiés</a>
    @else
        <a class="cta" href="{{ route('tfe.create') }}"><i class="fas fa-plus"></i>Ajouter un document</a>
        <a class="nav-item" {{ $ici('welcome', 'rapport') }} href="{{ route('welcome') }}"><i class="fas fa-book-open"></i>Rapports</a>
        <a class="nav-item" {{ $ici('profil', 'editTfe') }} href="{{ route('profil') }}"><i class="fas fa-folder-open"></i>Mes documents</a>
        <a class="nav-item" {{ $ici('profil.edit') }} href="{{ route('profil.edit') }}"><i class="fas fa-user-edit"></i>Mon profil</a>
    @endguest

    <div class="drawer-foot">
        @auth
            <a class="user" href="{{ route('profil') }}">
                <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
                <span><b>{{ Auth::user()->name }}</b><small class="muted">{{ Auth::user()->is_admin ? 'Administrateur' : trim(optional(Auth::user()->niveau)->nom . ' ' . Auth::user()->entity) }}</small></span>
            </a>
            <a class="nav-item" {{ $ici('changepasswordView') }} href="{{ route('changepasswordView') }}"><i class="fas fa-key"></i>Mot de passe</a>
            <a class="nav-item" {{ $ici('email.confirmer') }} href="{{ route('email.confirmer') }}"><i class="fas fa-envelope"></i>Adresse email @if (!Auth::user()->email_verified_at)<span class="count">!<span class="sr"> à confirmer</span></span>@endif</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="nav-item" type="submit"><i class="fas fa-sign-out-alt"></i>Se déconnecter</button></form>
        @endauth
    </div>
</nav>
