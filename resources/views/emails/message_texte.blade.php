{!! $titre !!}

@foreach ($lignes as $ligne)
{!! $ligne !!}

@endforeach
@if (!empty($code))
Code : {!! $code !!}

@endif
@if (!empty($bouton))
{!! $bouton[0] !!} : {!! $bouton[1] !!}

@endif
INSTI de Lokossa - Rapports de fin d'études
