@extends('layouts.app')

@section('content')

    <h3 class="text-dark mb-4 ml-3">Acceuil</h3>
    

        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ asset('images/img7.jpg') }}" alt="1er slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h2 style="color:white">Tous les Travaux de Fin d'Etude <br> à votre disposition.</h2>
                        <p><a href="#tfe" class="consult-btn">Consulter</a></p>
                    </div>
                </div>
            
                <div class="carousel-item ">
                    <img class="d-block w-100" src="{{ asset('images/img5.jpg') }}" alt="4e slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h2 style="color:white">Vous êtes déja connecté?<br> Déposé votre tfe.</h2>
                        <p><a class="btn" style="background-color:rgb(0, 68, 255); color:white"
                                href="{{ route('tfe.create') }}"><span>{{ __('Déposer') }}</span></a></p>
                    </div>
                </div>
            </div>
            
        </div>
 
    <div class="container" id="tfe">
        <div class="col">
            <h1 class="text-center text-dark mb-4" style="font-style: normal;font-family: bold;">Les derniers tfes publiés
            </h1>
            <div class="row">
                @if (count($tfes) > 0)
                    @include('layouts.sidebar')
                @endif
            </div>

            <div class="row ">
                @forelse($tfes as $tfe)
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-4">
                                        <div class="card-title text-dark"style="font-size: 20px"><span>
                                                {{ $tfe->theme }}</span></div>
                                        <div class="card-text text-dark"><span>Réalisé par {{ $tfe->auteurs }} en
                                                {{ $tfe->annee_de_realisation }}</span>
                                        </div>
                                    </div>
                                    <div class="col-auto mr-4">
                                        <a href="{{ route('tfe.show', $tfe) }}"><img src="images/pdf.png" width="60px">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="text-center">
                        <h1 style="font-size: 30px;color: black;">Aucun tfe disponibles pour le moment</h1>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@stop
@section('footer')
    <footer>
        <div class="container">
            <p class="text-dark text-center">
                INSTI &copy :Tous droits réservés {{ date('Y') }}
                &middot</p>
        </div>
    </footer>
@stop
