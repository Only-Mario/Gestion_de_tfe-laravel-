@extends('layouts.admin')
@section('title', 'Filières')
@section('content')
    <div class="page-head"><div><h1>Filières</h1><p>Chaque filière appartient à un niveau : licence, master ou doctorat.</p></div><button class="btn btn-filled" type="button" data-dialog="ajout"><i class="fas fa-plus"></i>Ajouter une filière</button></div>
    <div class="stack">
        @if ($sansNiveau->count())<div class="alert alert-bad"><i class="fas fa-exclamation-circle"></i><span>Sans niveau, donc invisibles au dépôt : {{ $sansNiveau->pluck('nom')->implode(', ') }}. Modifiez-les pour choisir leur niveau.</span></div>@endif
        @foreach ($niveaux->concat($sansNiveau->count() ? [(object) ['id' => null, 'nom' => 'Sans niveau', 'code' => '', 'filieres' => $sansNiveau]] : []) as $niveau)
            <section class="card"><h2>{{ $niveau->nom }} <span class="tag tag-{{ $niveau->code }}">{{ count($niveau->filieres) }}</span></h2>
                @if (count($niveau->filieres))
                <div class="table-wrap"><table><thead><tr><th>Sigle</th><th>Nom</th><th>Documents</th><th>État</th><th><span class="sr">Actions</span></th></tr></thead><tbody>
                    @foreach ($niveau->filieres as $f)
                        <tr><td data-l="Sigle"><b>{{ $f->nom }}</b></td><td class="wide" data-l="Nom">{{ trim($f->description) }}</td><td data-l="Documents">{{ $f->tfes_count }}</td>
                            <td data-l="État"><span class="tag {{ $f->active ? 'tag-ok' : '' }}">{{ $f->active ? 'Ouverte' : 'Fermée' }}</span></td>
                            <td class="acts">
                                <button class="icon-btn" type="button" title="Modifier" aria-label="Modifier {{ $f->nom }}" data-dialog="edition" data-action="{{ route('filieres.update', $f->id) }}" data-fill="{{ json_encode(['nom' => $f->nom, 'description' => trim($f->description), 'niveau_id' => $f->niveau_id]) }}"><i class="fas fa-edit"></i></button>
                                <form class="inline" method="POST" action="{{ route('filieres.basculer', $f->id) }}">@csrf<button class="icon-btn" type="submit" title="{{ $f->active ? 'Fermer' : 'Ouvrir' }}" aria-label="{{ $f->active ? 'Fermer' : 'Ouvrir' }} {{ $f->nom }}"><i class="fas fa-{{ $f->active ? 'lock' : 'lock-open' }}"></i></button></form>
                                @if (!$f->tfes_count)<form class="inline" method="POST" action="{{ route('filieres.destroy', $f->id) }}" data-confirm="Supprimer la filière {{ $f->nom }} ?">@csrf @method('DELETE')<button class="icon-btn bad" type="submit" title="Supprimer" aria-label="Supprimer {{ $f->nom }}"><i class="fas fa-trash"></i></button></form>@endif
                            </td></tr>
                    @endforeach
                </tbody></table></div>
                @else<p class="muted">Aucune filière pour ce niveau.</p>@endif
            </section>
        @endforeach
    </div>
    @foreach (['ajout' => ['Ajouter une filière', route('filieres.store')], 'edition' => ['Modifier la filière', '']] as $id => $d)
        <dialog id="{{ $id }}" @if ($errors->any() && $id === 'ajout' && old('_method') !== 'PUT') data-open @endif><h2>{{ $d[0] }}</h2>
            @if ($errors->any())<div class="alert alert-bad">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ $d[1] }}">@csrf @if ($id === 'edition') @method('PUT') @endif
            <div class="fields">
                <x-field name="niveau_id" :id="$id.'-niveau'" label="Niveau" type="select" required full>@foreach ($niveaux as $n)<option value="{{ $n->id }}" @if (old('niveau_id') == $n->id) selected @endif>{{ $n->nom }}</option>@endforeach</x-field>
                <x-field name="nom" :id="$id.'-nom'" label="Sigle" required maxlength="20" placeholder="GEI" />
                <x-field name="description" :id="$id.'-desc'" label="Nom complet" required maxlength="255" />
            </div>
            <div class="actions"><button class="btn" type="button" data-close>Annuler</button><button class="btn btn-filled" type="submit">Enregistrer</button></div></form></dialog>
    @endforeach
    @if ($errors->any() && old('_method') === 'PUT')<div class="alert alert-bad">{{ $errors->first() }}</div>@endif
@endsection
