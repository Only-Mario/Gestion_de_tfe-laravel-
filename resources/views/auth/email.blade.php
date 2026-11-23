@extends('layouts.app')
@section('title', 'Mon adresse email')
@section('content')
    <div class="stack auth">
        <section class="card">
            <h1>{{ $user->email_verified_at && !$demande ? 'Adresse confirmée' : 'Confirmer mon adresse' }}</h1>
            <p class="muted">{{ $demande['adresse'] ?? $user->email }}</p>
            @if ($demande)
                <form class="stack" method="POST" action="{{ route('email.verifier') }}">@csrf
                    <x-field name="code" label="Code à 6 chiffres" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required autofocus />
                    <button class="btn btn-filled btn-block" type="submit">Confirmer</button>
                </form>
            @endif
            @if ($demande || !$user->email_verified_at)
                <form method="POST" action="{{ route('email.envoyer') }}" style="margin-top:12px">@csrf<button class="btn {{ $demande ? '' : 'btn-filled' }} btn-block" type="submit">{{ $demande ? 'Renvoyer un code' : 'Recevoir un code' }}</button></form>
            @endif
        </section>
        <section class="card"><h2>Changer d’adresse</h2>
            <form class="stack" method="POST" action="{{ route('email.changer') }}">@csrf
                <x-field name="email" label="Nouvelle adresse email" type="email" required autocomplete="email" />
                <x-field name="mot_de_passe" label="Votre mot de passe" type="password" required autocomplete="current-password" />
                <button class="btn btn-outlined btn-block" type="submit">Envoyer un code à cette adresse</button>
            </form>
        </section>
    </div>
@endsection
