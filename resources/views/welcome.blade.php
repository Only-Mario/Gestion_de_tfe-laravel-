@extends('layouts.app')

@section('content')

 <h3 class="text-dark mb-4 ml-3">Acceuil</h3>
<header id="" class="text-center">

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
  </ol>
  <div class="carousel-inner">

 
        <div class="carousel-item active">
        <img class="d-block w-100" src="{{asset('images/img7.jpg')}}" alt="First slide">
                <div class="carousel-caption d-none d-md-block">
                <h2 style="color:white">Tous les travaux de fin d'étude <br> à votre disposition.</h2>
               <p><a href="#tfe" class="consult-btn">Consulter</a></p>
                </div>
        </div>
        <div class="carousel-item ">
        <img class="d-block w-100" src="{{asset('images/img3.jpg')}}" alt="First slide">
                <div class="carousel-caption d-none d-md-block">
                <h2 style="color:white">Vous êtes étudiant ? <br> Inscrivez vous pour le depôt de votre tfe</h2>
                @if(Auth::user()!=null)

                <p><a class="btn" style="background-color:  rgb(0, 68, 255);color:white" href="/"><span>{{ __("S'inscrire") }}</span></a></p>
                @else
                <p><a class="btn" style="background-color:  rgb(0, 68, 255);color:white" href="{{ route('register') }}"><span>{{ __("S'inscrire") }}</span></a></p>

                @endif
                </div>
        </div>
        <div class="carousel-item ">
        <img class="d-block w-100" src="{{asset('images/img6.jpg')}}" alt="First slide">
                <div class="carousel-caption d-none d-md-block">
                <h2 style="color:white">Vous êtes étudiant, <br> Et vous avez déjà un compte, Connectez vous.</h2>
                @if(Auth::user()!=null)
                  <p><a class="btn" style="background-color:  rgb(0, 68, 255);color:white" href="/"><span>{{ __("S'identifier") }}</span></a></p>
                  @else
                  <p><a class="btn" style="background-color:  rgb(0, 68, 255);color:white" href="{{ route('login') }}"><span>{{ __("S'identifier") }}</span></a></p>

                  @endif
                </div>
        </div>
        <div class="carousel-item ">
        <img class="d-block w-100" src="{{asset('images/img5.jpg')}}" alt="First slide">
                <div class="carousel-caption d-none d-md-block">
                <h2 style="color:white">Vous êtes déja connecté?<br> Déposé votre tfe.</h2>
                  <p><a class="btn" style="background-color:  rgb(0, 68, 255);color:white" href="{{ route('tfe.create') }}"><span>{{ __("Déposer") }}</span></a></p>
                </div>
        </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

   <div>
        <p>
           
        </p>
        
    </div>


</header>
<div class="container" id="tfe">
    <div class="col">
        <h1 class="text-center text-dark mb-4" style="font-style: normal;font-family: bold;">Les derniers tfes publiés</h1>
         <div class="row">
             @if(count($tfes)>0) 
                @include('layouts.sidebar')
             @endif                  
         </div>

          <div class="row row-cols-md-2 row-col-sm-2"> 
        @forelse($tfes as $tfe)
            <div class="col">
                <div class="card mb-4 shadow">    
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-4">
                                 <div class="card-title text-dark"style="font-size: 20px"><span> {{ $tfe->theme }}</span></div>
                                <div class="card-text text-dark"><span>Réalisé par {{ $tfe->auteurs }} en {{ $tfe->annee_de_realisation }}</span>
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
            Copyright {{date('Y')}}
        </div>
</footer>


@stop