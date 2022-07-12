@extends('layouts.app')

@section('content')

 <h3 class="text-dark  ml-3">Acceuil</h3>

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