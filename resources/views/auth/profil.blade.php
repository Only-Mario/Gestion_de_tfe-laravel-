@extends('layouts.app')

@section('content')
<div>
    <div class="container-fluid">
        <!-- **********- -->
        <h3 class="text-dark mb-4-m-8">Profile</h3>
        <div class="row">
           <div class="card-body text-center"><img class="rounded-circle mb-3 mt-4" src="{{ secure_asset('images/account.jpg')}}" width="160" height="160" />
             <div class="mb-3">
                <button class="btn btn-primary" type="button" onclick="event.preventDefult; alert('Vous ne pouvez éditer votre profil');">
                    <a href="" style="color: white;">Editer le Profil</a>
                </button>
                @if($tfe!=null and $tfe->status==0)
                <button class="btn btn-primary" type="button" >
                    <a href="{{ route('editTfe',$tfe->id)}}" style="color: white;">Editer le Tfe</a></button>
               @endif 
            </div>
        </div>
    </div>
    <!-- **********- -->

    <div class="card shadow mb-3">
        <div class="card-header" style="background-color: #4169e1;">
           <p class="m-0 font-weight-bold text-center" style="font-size: 20px;color: white;">Informations personnelles</p>
       </div>
       <div class="card-body">
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="mb-4">
                    <label>{{__('Nom complet')}}</label>
                    <input type="text" class="form-control" name="name" disabled="true" value="{{$user->name }}" disabled="true"/>
                </div>
                <div>
                    <label>{{__('Filière')}}</label>
                    <input class="form-control" value="{{$user->entity}}" disabled="true" id="entity">
                </div>

            </div>

            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="mb-4">
                    <label>{{__('N° Matricule')}}</label>
                    <input class="form-control" value="{{ $user->matricule}}" disabled="true">
                </div>
                <div>
                   <label>{{__('Année d\'étude')}}</label>
                   <input type="text" class="form-control" value="{{$user->study_year}}" disabled="true" />  

               </div>
           </div>
       </div>
       <div class="form-group">
        <label>{{__('N° d\'enrégistrement')}}</label>
        <input type="email" class="form-control" value="{{ $user==null? : $user->id}}"  disabled="true">
    </div>
</div>
</div>  
<!-- **********- -->
@if($tfe!=null)
<div class="row mb-3 text-center">
    <div class="col">

        <div class="card shadow">
            <div class="card-header" style="background-color: #4169e1;">
                <p class="m-0 font-weight-bold text-center" style="font-size: 20px;color: white;">Tfe infos 1</p>
            </div>
            <div class="card-body">
                <form>
                 <div class="form-group">
                  <label for="enregistrement"><strong>{{ __('Numéro d\'enrégistrement') }}</strong>
                  </label>
                  <input type="text" class="form-control" name="enregistrement" disabled="true" value="{{$tfe->id}}" />

              </div>
              <div class="form-group">
                  <label for="theme"><strong>Thème</strong></label><input type="text" disabled="true" class="form-control" name="theme" value="{{$tfe->theme}}" />
              </div>
              <div class="form-group">
                <label for="auteur"><strong>Auteur</strong></label>
                <input type="text" class="form-control" name="auteur" disabled="true" value="{{$tfe->auteurs}}"/>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group">
                        <label for="annee"><strong>Année</strong></label>
                        <input type="text" class="form-control" disabled="true" name="annee" value="{{$tfe->annee_de_realisation}}" />
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="filiere"><strong>Filière</strong></label>
                        <input type="text" name="filiere" disabled="true" value="{{$tfe->groupe_pedagogique}}">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
<!-- **********- -->
<div class="col">
    <div class="card shadow mb-3">
        <div class="card-header py-3" style="background-color: #4169e1;">
            <p class="m-0 font-weight-bold text-center" style="font-size: 20px;color: white;">Tfe infos 2</p>
        </div>
        <div class="card-body">
            <form>
                <div class="form-row">
                    <div class="col">
                        <div class="form-group"><label for="username">
                            <strong>Tuteur de Stage</strong></label><input type="text" class="form-control" name="username" value="{{$tfe->tuteur_de_stage}}" disabled="true"/>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="email"><strong>Email Tuteur de stage</strong></label><input type="email" class="form-control" name="email" value="{{$tfe->email_tuteur}}"  disabled="true"/>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <div class="form-group"><label for="first_name"><strong>Maitre memoire</strong></label><input type="text" class="form-control" name="first_name" value="{{$tfe->maitre_memoire}}"  disabled="true"/></div>
                    </div>
                    <div class="col">
                        <div class="form-group"><label for="last_name"><strong>Email Maitre memoire</strong>
                        </label><input type="text" class="form-control"  name="last_name" value="{{$tfe->email_maitre_memoire}}"  disabled="true"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="stage_lieu"><strong>Lieu de stage</strong></label><input type="text" class="form-control" name="stage_lieu" value="{{$tfe->lieu_de_stage}}"  disabled="true"/>
            </div>
            <div class="form-group">
                <label for="date"><strong>Status</strong></label><input type="text" class="form-control" name="date" disabled value="{{$status}}"  disabled="true"/>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endif
@if($tfe==null)
<div class="card shadow text-center mt-4">
    <div class="card-body">
        <h1 style="" class="text-info">Vous n'avez pas soumis votre tfe</h1>
    </div>
</div>
@endif
</div>
</div>
@endsection
<style>
   @media(max-width: 45rem){
    .col{
        margin: 5px;
    }
   }
</style>
