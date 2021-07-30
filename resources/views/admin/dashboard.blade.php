
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
    <nav class="navbar navbar-light navbar-expand-md navigation-clean shadow">
        <div class="container"><a class="navbar-brand" href="#">Administration</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
            id="navcol-1">
            <ul class="nav navbar-nav ml-auto">
               
                <li class="nav-item" role="presentation"><a class="nav-link active border-primary" data-bs-hover-animate="flash" href="{{route('dashboard')}}">Tableau de Bord</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" data-bs-hover-animate="flash" href="{{route('store')}}">Etudiant</a></li>
                
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
    <div class="col">
        <div class="row">
            <div class="col">
                <div class="card mt-4 mb-4 bg-dark">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <div class="row">
                            <div class="col align-content-center" style="border-right:2px solid white;"><label class="col-form-label text-light align-content-center">THEME</label></div>
                            <div class="col align-content-center" style="border-right:2px solid white;"><label class="col-form-label text-light align-content-center">AUTEURS</label></div>
                            <div class="col align-content-center" style="border-right:2px solid white;"><label class="col-form-label align-content-center text-light align-content-center">GROUPE PEDAGODIQUE</label></div>
                            <div class="col align-content-center" style="border-right:2px solid white;"><label class="col-form-label text-light align-content-center">ANNEE DE REALISATION</label></div>
                            <div class="col align-content-center" style="border-right:2px solid white;"><label class="col-form-label text-light align-content-center">LIEU DE STAGE</label></div>
                            <div class="col align-content-center" style="border-right:2px solid white;"><label class="col-form-label text-light align-content-center">MAITRE MEMOIRE<br /></label></div>
                            <div class="col align-content-center" style="border-right:2px solid white;"><label class="col-form-label text-light align-content-center">EMAIL MAITRE MEMOIRE</label></div>
                            <div class="col align-content-center" style="border-right:2px solid white;"><label class="col-form-label text-light align-content-center">DOCUMENT</label></div>
                            <div class="col align-content-center"><label class="col-form-label text-light align-content-center">STATUS</label></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                         <h3 class="text-dark text-center">Les tfe en attente</h3>
                    </div>   
                </div>
                @if(count($tfes0)>0)
                <div class="row">
                    <div class="col text-center">
                        <a href="{{route('status',['id'=>-1,'status'=>1])}}">
                       <button class="btn btn-success">
                        TOUT VALIDER
                       </button>
                       </a>
                       <a href="{{route('status',['id'=>-1,'status'=>2])}}">
                       <button class="btn btn-danger">
                        TOUT REFUSER
                       </button>
                       </a> 
                   </div>
                </div>
                 @endif 
                 
                @forelse($tfes0 as $tfe)
                <div class="card" style="border-bottom: 2px solid black;">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <div class="row">
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->theme}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->auteurs}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center align-content-center">{{$tfe->groupe_pedagogique}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->annee_de_realisation}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->lieu_de_stage}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->maitre_memoire}}<br /></label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->email_maitre_memoire}}</label></div>
                            <div class="col align-content-center"> <a class="col-form align-content-center" href="{{ route('tfe.show', $tfe) }}"><img src="{{asset('images/pdf.png')}}" width="60px"></a></div>
                            <div class="col">
                                <div class="row">
                                    <div class="col mb-4">
                                         <a href="{{route('status',['id'=>$tfe->id,'status'=>1])}}">
                                       <button class="btn btn-success">
                                        VALIDER
                                       </button>
                                       </a>
                                    </div>
                                    <div class="col">
                                          <a href="{{route('status',['id'=>$tfe->id,'status'=>2])}}">
                                       <button class="btn btn-danger">
                                       REFUSER
                                       </button>
                                       </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="text-center"><i>Aucun TFE n'a été soumit</i></div>
                @endforelse
                <h3 class="text-dark text-center">Les tfe validés</h3>
                @forelse($tfes1 as $tfe)
                <div class="card" style="border-bottom: 2px solid black;">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <div class="row">
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->theme}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->auteurs}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center align-content-center">{{$tfe->groupe_pedagogique}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->annee_de_realisation}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->lieu_de_stage}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->maitre_memoire}}<br /></label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->email_maitre_memoire}}</label></div>
                            <div class="col align-content-center"> <a class="col-form align-content-center" href="{{ route('tfe.show', $tfe) }}"><img src="{{asset('images/pdf.png')}}" width="60px"></a></div>
                            <div class="col">
                                <div class="row">
                                   <img src="{{asset('images/valider.jpg')}}" width="60px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                     <div class="text-center"><i>Aucun</i></div>
                @endforelse
                <div class="row">
                   <div class="col">
                       <h3 class="text-dark text-center mt-4">Les tfe refusés</h3>
                   </div> 
                </div>   
                 @if(count($tfes2)>0)
                <div class="row mb-3">
                    <div class="col text-center">
                        <a href="{{route('status',['id'=>-2,'status'=>-1])}}">
                       <button class="btn btn-success">
                        TOUT SUPPRIMER
                       </button>
                       </a>
                   </div>
                </div>
                 @endif 
                @forelse($tfes2 as $tfe)
                <div class="card mb-4" style="border-bottom: 2px solid black;">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <div class="row">
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->theme}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->auteurs}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center align-content-center">{{$tfe->groupe_pedagogique}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->annee_de_realisation}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->lieu_de_stage}}</label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->maitre_memoire}}<br /></label></div>
                            <div class="col align-content-center"><label class="col-form-label align-content-center">{{$tfe->email_maitre_memoire}}</label></div>
                            <div class="col align-content-center"> <a class="col-form align-content-center" href="{{ route('tfe.show', $tfe) }}"><img src="{{asset('images/pdf.png')}}" width="60px"></a></div>
                            <div class="col">
                                <div class="row">
                                   <img src="{{asset('images/refuser.png')}}" width="60px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                   <div class="text-center"><i>Vide</i></div>
                @endforelse
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
