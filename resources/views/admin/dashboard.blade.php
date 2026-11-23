@extends('layouts.admin')
@section('title', 'Documents')
@php($params = array_filter(['q' => $q] + $filtres))
@section('content')
    <div class="page-head"><div><h1>Documents</h1><p>Validation, recherche et suivi des dépôts.</p></div>
        <a class="btn btn-outlined" href="{{ route('export', ['status' => $status] + $params) }}"><i class="fas fa-file-excel"></i>Exporter (CSV)</a></div>
    <div class="stack">
        <div class="grid-2">
            @foreach ([['file-alt', 'Documents', $stats['total_tfe']], ['hourglass-half', 'À valider', $counts[0]], ['calendar-alt', 'Déposés ce mois', $stats['mois']], ['user-graduate', 'Étudiants', $stats['students']]] as $s)
                <div class="card stat"><i class="fas fa-{{ $s[0] }}" aria-hidden="true"></i><span><b>{{ number_format($s[2], 0, ',', ' ') }}</b><span class="muted small">{{ $s[1] }}</span></span></div>
            @endforeach
        </div>
        <div class="grid-2">
            <section class="card"><h2>Par niveau</h2>@foreach ($niveaux as $n)<div class="bar"><span>{{ $n->nom }}</span><progress max="{{ max(1, $stats['total_tfe']) }}" value="{{ $parNiveau[$n->id] ?? 0 }}"></progress><span>{{ $parNiveau[$n->id] ?? 0 }}</span></div>@endforeach</section>
            <section class="card"><h2>Par filière</h2>@foreach ($parFiliere as $nom => $total)<div class="bar"><span>{{ $nom }}</span><progress max="{{ max(1, $parFiliere->max()) }}" value="{{ $total }}"></progress><span>{{ $total }}</span></div>@endforeach</section>
        </div>
        <section class="card stack">
            <nav class="chips" aria-label="Statut">
                @foreach ([0 => 'À valider', 1 => 'Publiés', 2 => 'Rejetés'] as $v => $l)<a class="chip" @if ($status === $v) aria-current="true" @endif href="{{ route('dashboard', ['status' => $v] + $params) }}">{{ $l }} <span>{{ $counts[$v] }}</span></a>@endforeach
            </nav>
            <form class="fields" method="GET" action="{{ route('dashboard') }}" data-autosubmit>
                <input type="hidden" name="status" value="{{ $status }}">
                <x-field name="q" label="Recherche" type="search" :value="$q" placeholder="Étudiant, matricule, thème" />
                <x-field name="niveau" label="Niveau" type="select"><option value="">Tous</option>@foreach ($niveaux as $n)<option value="{{ $n->id }}" @if ($filtres['niveau'] == $n->id) selected @endif>{{ $n->nom }}</option>@endforeach</x-field>
                <x-field name="filiere" label="Filière" type="select"><option value="">Toutes</option>@foreach ($niveaux as $n)<optgroup label="{{ $n->nom }}">@foreach ($n->filieres as $f)<option value="{{ $f->id }}" @if ($filtres['filiere'] == $f->id) selected @endif>{{ $f->libelle }}</option>@endforeach</optgroup>@endforeach</x-field>
            </form>
            @if ($status === 0 && $counts[0])
                <div class="row"><form class="inline" method="POST" action="{{ route('status', ['id' => -1, 'status' => 1]) }}" data-confirm="Valider les {{ $counts[0] }} documents en attente ?">@csrf<button class="btn btn-tonal btn-sm" type="submit">Tout valider</button></form></div>
            @elseif ($status === 2 && $counts[2])
                <form class="inline" method="POST" action="{{ route('status', ['id' => 0, 'status' => -2]) }}" data-confirm="Supprimer définitivement les {{ $counts[2] }} documents rejetés, leurs fichiers et leurs anciennes versions ?">@csrf<button class="btn btn-danger btn-sm" type="submit">Supprimer tous les rejetés</button></form>
            @endif
            <div class="table-wrap"><table>
                <thead><tr><th>Étudiant</th><th>Niveau</th><th>Thème</th><th>Déposé le</th><th><span class="sr">Actions</span></th></tr></thead>
                <tbody>
                @forelse ($tfes as $tfe)
                    <tr>
                        <td data-l="Étudiant">{{ optional($tfe->user)->name ?: '-' }}<br><span class="muted small">{{ optional($tfe->user)->matricule }}</span></td>
                        <td data-l="Niveau"><x-niveau :tfe="$tfe" /><br><span class="muted small">{{ $tfe->groupe_pedagogique }}</span></td>
                        <td class="wide" data-l="Thème">{{ $tfe->theme }}
                            @if ($tfe->motif_rejet)<p class="small" style="color:var(--error)">Rejet : {{ $tfe->motif_rejet }}</p>@endif
                            @foreach ($tfe->modifications as $m)
                                <div class="small muted">Modifié le {{ $m->created_at->format('d/m/Y H:i') }} : {{ $m->motif }} · <a href="{{ route('justificatif', $m->id) }}" target="_blank" rel="noopener">Justificatif</a>
                                    <form class="inline" method="POST" action="{{ route('restaurer', $m->id) }}" data-confirm="Rétablir la version d'avant cette modification ?">@csrf<button class="btn btn-sm" type="submit">Rétablir la version d’avant</button></form></div>
                            @endforeach
                        </td>
                        <td data-l="Déposé le">{{ optional($tfe->created_at)->format('d/m/Y') }}</td>
                        <td class="acts">
                            <a class="icon-btn" href="{{ route('tfe.show', $tfe) }}" target="_blank" rel="noopener" title="Voir le PDF" aria-label="Voir le PDF"><i class="fas fa-file-pdf"></i></a>
                            <a class="icon-btn" href="{{ route('editTfe', $tfe->id) }}" title="Modifier" aria-label="Modifier"><i class="fas fa-edit"></i></a>
                            @if ($status !== 1)<form class="inline" method="POST" action="{{ route('status', ['id' => $tfe->id, 'status' => 1]) }}" data-confirm="Publier ce document ?">@csrf<button class="icon-btn ok" type="submit" title="Publier" aria-label="Publier"><i class="fas fa-check"></i></button></form>@endif
                            @if ($status !== 2)<button class="icon-btn bad" type="button" title="Rejeter" aria-label="Rejeter" data-dialog="rejet" data-action="{{ route('status', ['id' => $tfe->id, 'status' => 2]) }}"><i class="fas fa-times"></i></button>@else
                                <form class="inline" method="POST" action="{{ route('status', ['id' => $tfe->id, 'status' => -3]) }}" data-confirm="Supprimer définitivement ce document ?">@csrf<button class="icon-btn bad" type="submit" title="Supprimer" aria-label="Supprimer"><i class="fas fa-trash"></i></button></form>@endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Aucun document ici.</td></tr>
                @endforelse
                </tbody></table></div>
            {{ $tfes->links('vendor.pagination.m3') }}
        </section>
    </div>
    <dialog id="rejet"><h2>Rejeter le document</h2><form method="POST" action="">@csrf
        <x-field name="motif_rejet" label="Motif, visible par l’étudiant" type="textarea" maxlength="1000" required />
        <div class="actions"><button class="btn" type="button" data-close>Annuler</button><button class="btn btn-danger" type="submit">Rejeter</button></div></form></dialog>
@endsection
