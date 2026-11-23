@props(['tfe'])
@if ($tfe->niveau)<span class="tag tag-{{ $tfe->niveau->code }}">{{ $tfe->niveau->nom }}</span>@endif
