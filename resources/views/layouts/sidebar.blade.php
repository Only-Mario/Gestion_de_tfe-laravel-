<div class="card mb-4 mt-4 p-3" id="search-bar">
<div class="row row-cols-md-6 row-col-sm-2 "> 
    <div class="col">
        <button class="btn btn-primary" onclick="event.preventDefault;
        document.getElementById('form2').submit();">Rechercher</button>
    </div> 
    <div class="col">
        <form id="form2" action="{{ route('search') }}" method="get">     
             <input type="text" class="form-control" id="search" name="search" placeholder="par mot clé...">
        </form>  
    </div>
<div class="col">
    <form action="{{ route('search') }}" id="form3" method="get">
        <select class="form-control" name="search" id="search">
            @foreach($years as $year)
            <option value="{{ $year }}" >{{$year}}</option>
            @endforeach
        </select>
    </form>                 
</div>
<div class="col">
    <button class="btn btn-primary" onclick="event.preventDefault;
    document.getElementById('form3').submit();">Filtrer par année</button>
</div>
<div class="col">
    <form action="{{ route('search') }}" id="form4" method="get">
        <select class="form-control" name="search" id="search" >
            <option value="@_GEI"> Génie Electrique et Informatique (GEI)</option>
            <option value="@_GC"> Génie Civi (GC)</option>
            <option value="@_MS"> Maintenance des Systèmes (MS)</option>
            <option value="@_GE"> Génie Energétique (GE)</option>
            <option value="@_GMP"> Génie Mécanique et Productique (GMP)</option>
        </select>            
    </form>                 
</div>
<div class="col">
    <button class="btn btn-primary" onclick="event.preventDefault;
    document.getElementById('form4').submit();">Filtrer par filière</button>
</div>
</div>
</div>


<div class="card m-3 " id="search-bar-small">
    <div class="input-group">
        <div class="form-outline">
            <form id="form6" class="form" action="{{ route('search') }}" method="get"> 
                <input type="text" class="form-control" id="search" name="search" placeholder="par mot clé...">
            </form> 
        </div>
        <button class="btn btn-primary" onclick="event.preventDefault;
        document.getElementById('form6').submit();">
            <i class="fas fa-search"></i>
        </button>
    </div>


</div>

<style>
    #search-bar-small{
        display: none;
    }
    @media(max-width: 45rem){
        #search-bar{
            display: none;
        }
        #search-bar-small{
            display: block;
        }
    }
</style>