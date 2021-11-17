
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ config('app.name', 'Gestion des tfe') }}</title>

    <link rel="stylesheet" href="{{asset('css/assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="{{asset('css/assets/css/Navigation-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/assets/fonts/fontawesome-all.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean fixed-top shadow">
        <div class="container"><a class="navbar-brand" href="#">Administration</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
                id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item" role="presentation"><a class="nav-link" data-bs-hover-animate="flash" href="{{route('dashboard')}}">Tableau de Bord</a></li>
                    <li class="nav-item"><a href="#admin" class="nav-link" data-bs-hover-animate="flash">Ajouter un administrateur</a></li>
                    <li class="nav-item"><a href="#admin" class="nav-link" data-bs-hover-animate="flash">Ajouter un étudiant</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-bs-hover-animate="flash" href="{{route('store')}}">Utilisateurs</a></li>
                   
                    <li class="nav-item " role="presentation"> <a class="dropdown-item text-danger" href="{{ route('logout') }}"
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

<div class="card shadow mb-4 text-center container " style="margin-top: 100px;">
<div class="container m-3">
        @include('flash::message')
      </div>   
    <h1 class="text-dark mt-3">Liste des étudiants inscrits sur la platforme</h1>
    <input class="m-3 myInput1" type="text" id="myInput" onkeyup="myFunction()" placeholder="Rechercher par noms.." title="Type in a name">
    <table class="table table-bordered table-sm m-3 myTable1" id="myTable">
      
    <thead>
        <tr class="header">
        <th>Nom</th>
        <th>N° Matricule</th>
        <th>Filière</th>
        <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($students as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->matricule}}</td>
                <td>{{$user->entity}}</td>
                <td>
              <a class="btn btn-circle btn-lg" title="Voir"><span class="fa fa-eye text-info" aria-hidden="true"></span></a>
                    <a class="btn btn-circle btn-lg" title="Modifier"><span class="fa fa-edit text-warning" aria-hidden="true"></span></a>
                    <a class="btn btn-circle btn-lg" title="Supprimer" onclick="return confirm('Cette action est irréversible. Voulez vous poursuivre ?');" href="{{route('delete_student',['id'=>$user->id])}}"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>
                </td>
            </tr>
    @empty
         <tr class="center">
          <th></th>
          <th></th>
          <th></th>
          <th>Aucun étudiant </th>
          <th></th>
          <th></th>
         </tr>
    @endforelse   
    </tbody>
  </table>


  <h1 class="text-dark mt-3">Liste des administrateurs de la plateforme</h1>
    <input class="m-3 myInput2" type="text" id="myInput" onkeyup="myFunction2()" placeholder="Rechercher par noms.." title="Type in a name">
    <table class="table table-bordered table-sm m-3 myTable2" id="myTable">
      
    <thead>
        <tr class="header">
        <th>Nom</th>
        <th>E-mail</th>
        <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($admins as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>
                    <button class="btn btn-circle btn-lg" title="Supprimer"><i class="fa fa-trash text-danger" aria-hidden="true"></i></button>
                </td>
            </tr>
    @empty
    @endforelse   
    </tbody>
  </table





<div class="container">
<div class="card" id="admin">
    <div class="card-header text-center bg-dark text-light">{{ __('Ajouter un Administrateur') }}</div>

    <div class="card-body">
        <form method="POST" action="{{ route('addAdmin') }}">
            @csrf
            <div class="form-group row">
                <label for="username" class="col-md-4 col-form-label text-md-right text-black">{{ __('Email') }}</label>
                <div class="col-md-6">
                    <input id="username" type="email" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username">

                    @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
            </div>
            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right text-black">{{ __('Mot de passe') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="username" class="col-md-4 col-form-label text-md-right text-black">{{ __('Votre mot de passe') }}</label>
                <div class="col-md-6">
                    <input id="passwordV" type="password" class="form-control @error('passwordV') is-invalid @enderror" name="passwordV" value="{{ old('passwordV') }}" required autocomplete="passwordV">

                    @error('passwordV')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
            </div>
            <div class="form-group row mb-0">
                <div class="col text-center">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Ajouter') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

   <div class="card mb-3">
       <div class="card-header text-light text-center bg-dark">
       {{ __('Ajouter un Etudiant') }}
       </div>
       <div class="card-body">   
        <form class="user myform"method="POST">
         
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="mb-4">
                    <input type="text" class="name form-control @error('name') is-invalid @enderror " name="name" value="{{ old('name') }}" required autocomplete="name" id="exampleFirstName" placeholder="{{ __('Nom complet') }}" name="first_name" />
                    
                        <span class="text-danger" role="alert">
                            <strong class="name_"></strong>
                        </span>
                </div>
                <div>
                     <select class="form-control @error('entity') is-invalid @enderror entity" name="entity" value="{{old('entity')}}" id="entity" onautocomplete="entity">
                        filiere
                        <option value="GEI"> GEI</option>
                        <option value="GME"> GME</option>
                        <option value="GC"> GC</option>
                        <option value="GC"> GE</option>
                    </select>

          
                </div>
               
            </div>

            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="mb-4">
                      <input class="form-control @error('matricule') is-invalid @enderror matricule" name="matricule" value="{{ old('matricule') }}" required autocomplete="matricule" placeholder="{{__('N° Matricule')}}"/>
                      
                        <span class="text-danger invalid" role="alert">
                                <strong class="matricule_"></strong>
                            </span>
                </div>
                <div>
                     <select class="form-control @error('Année d\'etude') is-invalid @enderror study_year" name="study_year" name="study_year" id="study_year" value="{{ old('study_year') }}" autocomplete="study_year">
                        <option value="1"> 1ère Année</option>
                        <option value="2"> 2ème Année</option>
                        <option value="3"> 3ème Année</option>
                        </select>

                 
                </div>
            </div>
        </div>
       
        <div class="form-group row">
            <div class="col-sm-6">
                <input type="email" class="form-control @error('email') is-invalid @enderror email" name="email" value="{{ old('email') }}" required autocomplete="email" aria-describedby="emailHelp" placeholder="{{ __('E-Mail Address') }}"/>
                
                    <span class="invalid text-danger" role="alert">
                            <strong class="email_"></strong>
                        </span>
            </div>
            <div class="col-sm-6 mb-3 mb-sm-0"> 
               <input type="password" class="form-control @error('password') is-invalid @enderror password" name="password" required autocomplete="new-password" placeholder="{{ __('Mot de passe') }}"/> 
               
                <span class="invalid text-danger" role="alert">
                    <strong class="password_"></strong>
                </span>
         
            </div>
           
        </div>
        
    </form>
  
    <button  style="background-color: #4169e1; font-size: 20px" class="btn btn-primary text-white" id="submit" onclick="addStudent();">Ajouter
        </button>
   </div>

   </div>

    
</div>
<!-- ////////////////// -->
<script src="{{asset('css/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('css/assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('css/assets/js/bs-init.js')}}"></script>
<script src="{{asset('css/assets/js/index.js')}}"></script>
</body>

</html>


<style>
    #myInput {
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myTable {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd;
  font-size: 18px;
}

#myTable th, #myTable td {
  text-align: left;
  padding: 12px;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}
</style>
<script>
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = $(".myInput1");
  filter = input.value.toUpperCase();
  table = $(".myTable1");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = $(".myInput2");
  filter = input.value.toUpperCase();
  table = $(".myTable2");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
function validateEmail(email) {
  const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function addStudent(e) {
    var valid= true;
    if($('.name').val().length<3){
       $('.name_').text('veuillez entrer un vrai nom.');
       valid= false;
    }

    if(!validateEmail($('.email').val())){
        valid= false;
        $('.email_').text('entrez une bonne addresse E-mail');
    }

    if($('.password').val().length<8){
        valid= false;
        $('.password_').text("au moins 8 caracteres");
    }

    if($('.matricule').val().length<=0){
        valid= false;
        $(".matricule_").text("Veuillez entrer le N° matricule");
    }
  if(valid){
  var data={nom: $('.name').val(),
            email: $('.email').val(),
            study_year: $('.study_year').val(),
            password: $('.password').val(),
            matricule: $('.matricule').val(),
            entity: $('.entity').val()
        };
    
    $.ajax({
        type:'POST',
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        url: "{{route('addStudent')}}",
        data: {
            _token: "{{csrf_token()}}",
            data: data      
        },
        success:function(response){
            alert(response);
          console.log(response);
          if(response) {
            $(".myform")[0].reset();
          }
        },
        error: function(error) {
            console.log(error);
        }
    });
  }
  alert("invalid")

}
</script>