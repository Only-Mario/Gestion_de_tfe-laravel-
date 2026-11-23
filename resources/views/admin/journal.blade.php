@extends('layouts.admin')
@section('title', 'Journal')
@section('content')
    <div class="page-head"><div><h1>Journal</h1><p>Historique des actions d’administration.</p></div></div>
    <section class="card" style="margin-bottom:16px"><h2>Tester l’envoi d’emails</h2>
        <form class="fields" method="POST" action="{{ route('testEmail') }}">@csrf
            <x-field name="adresse" label="Envoyer un message de test à" type="email" :value="Auth::user()->email" required />
            <div class="field"><label>&nbsp;</label><button class="btn btn-filled" type="submit"><i class="fas fa-paper-plane"></i>Envoyer le test</button></div>
        </form>
    </section>
    <section class="card"><div class="table-wrap"><table><thead><tr><th>Date</th><th>Auteur</th><th>Action</th><th>Détails</th><th>Adresse IP</th></tr></thead><tbody>
        @forelse ($entrees as $e)<tr><td data-l="Date">{{ $e->created_at->format('d/m/Y H:i') }}</td><td data-l="Auteur">{{ $e->auteur }}</td><td data-l="Action">{{ $e->action }}</td><td class="wide" data-l="Détails">{{ $e->details }}</td><td data-l="Adresse IP">{{ $e->ip }}</td></tr>
        @empty<tr><td colspan="5" class="empty">Aucune action enregistrée.</td></tr>@endforelse
    </tbody></table></div>{{ $entrees->links('vendor.pagination.m3') }}</section>
@endsection
