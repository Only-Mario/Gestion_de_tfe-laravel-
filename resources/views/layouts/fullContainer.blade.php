<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
    <div class="col-lg-4">
        <div class="container-fluid d-flex flex-column p-0">
            <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
                <div class="sidebar-brand-text mx-3"><span>My TFE</span></div>
            </a>
            <hr class="sidebar-divider my-0">
            <ul class="nav navbar-nav text-light" id="accordionSidebar">
                @if (Route::has('welcome'))
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('welcome') }}"><i class="fas fa-user-circle"></i><span>{{ __("Accueil") }}</span></a>
                </li>
                @endif                            
                @guest
                @if (Route::has('login'))
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('login') }}"><i class="far fa-user-circle"></i><span>{{ __("S'identifier") }}</span></a>
                </li>
                @endif
                @if (Route::has('register'))
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-circle"></i><span>{{ __("S'inscrire") }}</span></a>
                </li>
                @endif
                @else
                @if(Auth::user()->name!='Admin')
                @if (Route::has('tfe.create'))
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('tfe.create') }}"><i class="fas fa-user-circle"></i><span>{{ __("Ajouter un tfe") }}</span></a>
                </li>
                @endif
                <li class="nav-item">
                    <div class="dropdown nav-link"><i class="fas fa-user-circle"></i>
                        <span>
                            <div class="dropdown-content">
                            <a class="dropdown-item" href="{{ route('profil',Auth::user()) }}">{{ Auth::user()->name}}</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                {{ __('Se Déconnecter') }}
                            </a>
                        </div>
                        </span>Mon profil
                        
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>    
                @endguest
                @endif
            </ul>
            <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
        </div>
    </div> 
</nav>