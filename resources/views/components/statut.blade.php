@props(['tfe'])
<span class="tag {{ [0 => 'tag-wait', 1 => 'tag-ok', 2 => 'tag-bad'][(int) $tfe->status] ?? '' }}">{{ $tfe->statut_libelle }}</span>
