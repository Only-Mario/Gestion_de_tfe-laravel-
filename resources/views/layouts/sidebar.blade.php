<div class="card mb-4 mt-4 p-3" id="search-bar">
    <div class="row row-cols-md-6 row-col-sm-2">
        <div class="col">
            <form id="form2" action="{{ route('search') }}" method="get">
                <input type="text" class="form-control" id="kw" name="kw" placeholder="Entre un Mot clé...">
            </form>
        </div>
        <div class="col">
            <button class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('form2').submit();">Rechercher</button>
        </div>
        <div class="col">
            <form id="form3" action="{{ route('search') }}" method="get">
                <select class="form-control" name="annee_de_realisation" id="annee_de_realisation">
                    @foreach($annee_de_realisations as $annee_de_realisation)
                    <option value="{{ $annee_de_realisation }}">{{ $annee_de_realisation }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="col">
            <button class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('form3').submit();">Filtrer par année</button>
        </div>
        <div class="col">
            <form id="form4" action="{{ route('search') }}" method="get">
                <select class="form-control" name="groupe_pedagogique" id="groupe_pedagogique">
                    <option value="@_GEI">Génie Electrique et Informatique (GEI)</option>
                    <option value="@_GC">Génie Civil (GC)</option>
                    <option value="@_MS">Maintenance des Systèmes (MS)</option>
                    <option value="@_GE">Génie Energétique (GE)</option>
                    <option value="@_GMP">Génie Mécanique et Productique (GMP)</option>
                </select>
            </form>
        </div>
        <div class="col">
            <button class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('form4').submit();">Filtrer par filière</button>
        </div>
    </div>
</div>

<div class="card m-3" id="search-bar-small">
    <div class="input-group">
        <div class="form-outline">
            <form id="form6" class="form" action="{{ route('search') }}" method="get">
                <input type="text" class="form-control" id="kw" name="kw" placeholder="par mot clé...">
            </form>
        </div>
        <button class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('form6').submit();">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

<style>
    #search-bar-small {
        display: none;
    }
    @media(max-width: 45rem) {
        #search-bar {
            display: none;
        }
        #search-bar-small {
            display: block;
        }
    }
</style>
