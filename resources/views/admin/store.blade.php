@extends('layouts.admin')
@section('title', 'Utilisateurs')
@section('content')
    <div class="page-head"><div><h1>Utilisateurs</h1><p>{{ $users->total() }} {{ $type === 'admins' ? 'administrateur(s)' : 'étudiant(s)' }}</p></div>
        <button class="btn btn-filled" type="button" data-dialog="{{ $type === 'admins' ? 'addAdmin' : 'addStudent' }}"><i class="fas fa-user-plus"></i>{{ $type === 'admins' ? 'Ajouter un administrateur' : 'Ajouter un étudiant' }}</button></div>
    <section class="card stack">
        <nav class="chips" aria-label="Type"><a class="chip" @if ($type === 'students' && !$doublons) aria-current="true" @endif href="{{ route('store') }}">Étudiants</a><a class="chip" @if ($type === 'admins') aria-current="true" @endif href="{{ route('store', ['type' => 'admins']) }}">Administrateurs</a>@if ($nombreDoublons)<a class="chip" @if ($doublons) aria-current="true" @endif href="{{ route('store', ['doublons' => 1]) }}">Doublons <span>{{ $nombreDoublons }}</span></a>@endif</nav>
        @if ($doublons)<div class="alert"><i class="fas fa-info-circle"></i><span>L’email ou le matricule de ces comptes appartient aussi à un compte plus ancien. Corrigez-le avec le bouton Modifier.</span></div>@endif
        <form class="fields" method="GET" action="{{ route('store') }}" data-autosubmit>
            <input type="hidden" name="type" value="{{ $type }}">
            <x-field name="q" label="Recherche" type="search" :value="$q" placeholder="Nom, email, matricule" />
            @if ($type === 'students')<x-field name="niveau" label="Niveau" type="select"><option value="">Tous</option>@foreach ($niveaux as $n)<option value="{{ $n->id }}" @if ($niveau === $n->id) selected @endif>{{ $n->nom }}</option>@endforeach</x-field>@endif
        </form>
        <div class="table-wrap"><table><thead><tr><th>Nom</th><th>Email</th>@if ($type === 'students')<th>Niveau</th><th>Matricule</th><th>Documents</th>@endif<th><span class="sr">Actions</span></th></tr></thead><tbody>
            @forelse ($users as $user)
                <tr><td data-l="Nom">{{ $user->name }}</td><td data-l="Email">{{ $user->email }}</td>
                    @if ($type === 'students')<td data-l="Niveau">{{ optional($user->niveau)->nom ?: '-' }}<br><span class="muted small">{{ $user->entity }} · {{ $user->study_year ? \App\Models\Niveau::libelleAnnee($user->study_year) : '-' }}</span></td><td data-l="Matricule">{{ $user->matricule }}</td><td data-l="Documents">{{ $user->tfes_count }}</td>@endif
                    <td class="acts">
                        @if ($type === 'students')<button class="icon-btn" type="button" title="Modifier" aria-label="Modifier {{ $user->name }}" data-dialog="editStudent" data-action="{{ route('update_student', $user->id) }}" data-fill="{{ json_encode($user->only('name', 'matricule', 'entity', 'study_year', 'email')) }}"><i class="fas fa-edit"></i></button>@endif
                        <form class="inline" method="POST" action="{{ route('envoyerLien', $user->id) }}" data-confirm="Envoyer un lien de réinitialisation du mot de passe à {{ $user->email }} ?">@csrf<button class="icon-btn" type="submit" title="Envoyer un lien de mot de passe" aria-label="Envoyer un lien de mot de passe"><i class="fas fa-key"></i></button></form>
                        @if ($user->id !== Auth::id())<form class="inline" method="POST" action="{{ route('delete_student', $user->id) }}" data-confirm="Supprimer définitivement ce compte et tous ses documents ?">@csrf<button class="icon-btn bad" type="submit" title="Supprimer" aria-label="Supprimer {{ $user->name }}"><i class="fas fa-trash"></i></button></form>@endif
                    </td></tr>
            @empty<tr><td colspan="6" class="empty">Aucun utilisateur.</td></tr>@endforelse
        </tbody></table></div>
        {{ $users->links('vendor.pagination.m3') }}
    </section>
    @foreach (['addStudent' => ['Ajouter un étudiant', route('addStudent')], 'editStudent' => ['Modifier l’étudiant', '']] as $id => $d)
        <dialog id="{{ $id }}" @if ($errors->any() && old('_form') === $id && $id === 'addStudent') data-open @endif><h2>{{ $d[0] }}</h2><form method="POST" action="{{ $d[1] }}" data-niveaux>@csrf<input type="hidden" name="_form" value="{{ $id }}">
            <div class="fields">
                <x-field name="name" :id="$id.'-name'" label="Nom complet" required full />
                <x-field name="entity" :id="$id.'-entity'" label="Filière" type="select" required full data-filiere>@foreach ($niveaux as $n)<optgroup label="{{ $n->nom }}" data-niveau="{{ $n->id }}">@foreach ($n->filieres as $f)<option value="{{ $f->nom }}">{{ $f->libelle }}</option>@endforeach</optgroup>@endforeach</x-field>
                <x-field name="study_year" :id="$id.'-year'" label="Année d’étude" type="select" required data-annee><option value="">Choisir une année</option>@foreach ($niveaux as $n)<optgroup label="{{ $n->nom }}" data-niveau="{{ $n->id }}">@foreach ($n->annees as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach</optgroup>@endforeach</x-field>
                <x-field name="matricule" :id="$id.'-mat'" label="N° matricule" required />
                <x-field name="email" :id="$id.'-email'" label="Email" type="email" required />@if ($id === 'addStudent')<x-field name="password" :id="$id.'-pw'" label="Mot de passe" type="password" minlength="8" required autocomplete="new-password" />@endif
            </div><div class="actions"><button class="btn" type="button" data-close>Annuler</button><button class="btn btn-filled" type="submit">Enregistrer</button></div></form></dialog>
    @endforeach
    @if ($errors->any() && old('_form') === 'editStudent')<div class="alert alert-bad" style="margin-top:16px">{{ $errors->first() }}</div>@endif
    <dialog id="addAdmin" @if ($errors->any() && old('_form') === 'addAdmin') data-open @endif><h2>Ajouter un administrateur</h2><form method="POST" action="{{ route('addAdmin') }}">@csrf<input type="hidden" name="_form" value="addAdmin">
        <div class="fields"><x-field name="name" id="a-name" label="Nom" full /><x-field name="username" id="a-email" label="Email" type="email" required full /><x-field name="password" id="a-pw" label="Son mot de passe" type="password" minlength="10" required autocomplete="new-password" /><x-field name="passwordV" id="a-pwv" label="Votre mot de passe" type="password" required autocomplete="current-password" /></div>
        <div class="actions"><button class="btn" type="button" data-close>Annuler</button><button class="btn btn-filled" type="submit">Ajouter</button></div></form></dialog>
@endsection
