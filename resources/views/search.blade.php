@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
                @include('layouts.sidebar')
        </div>
    <div class="row">
        <div class="col-md-8">
            <h1 class="text-dark">
                Résultats de recherche pour : {{ $kw }}
            </h1>
            @forelse($tfes as $tfe)
               @if($tfe->status==1)
                <p>
                    <h2><a href="{{ route('tfe.show', $tfe) }}">{{ $tfe->theme }}</a></h2>
                    <h5>
                        {{ $tfe->auteurs }} 
                        <em>{{ $tfe->groupe_pedagogique }}/ {{ $tfe->annee_de_realisation }}</em>
                    </h5>
                </p>
                @endif

                @empty
                <p>
                    Aucun résultat trouvé
                </p>
            @endforelse
        </div>
           
        </div>
    </div>
@stop