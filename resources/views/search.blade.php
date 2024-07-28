@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            @include('layouts.sidebar')
        </div>
        <div class="row">
            <div class="col-12">
                <h1 class="text-dark pb-4">
                    Résultats de recherche pour : {{ $kw }}
                </h1>
                @forelse($tfes as $tfe)
                    @if ($tfe->status == 1)
                        <!-- <p>
                        <h2><a href="{{ route('tfe.show', $tfe) }}">{{ $tfe->theme }}</a></h2>
                        <h5>
                            {{ $tfe->auteurs }}
                            <em>{{ $tfe->groupe_pedagogique }}/ {{ $tfe->annee_de_realisation }}</em>
                        </h5>
                        </p>-->

                        <div class="col-12  pb-3">
                        <div class="card shadow">
                         <input id="tfe{{$loop->index}}" type="text" hidden value="{{$tfe->resume}}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-4"  onclick="openModal({{$loop->index}})">
                                        <div class="card-title text-dark"style="font-size: 20px"><span>
                                                {{ $tfe->theme }}</span></div>
                                        <div class="card-text text-dark"><span>Réalisé par {{ $tfe->auteurs }} en
                                                {{ $tfe->annee_de_realisation }} / {{ $tfe->groupe_pedagogique }}</span>
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
