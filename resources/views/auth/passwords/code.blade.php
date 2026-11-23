@extends('layouts.app')
@section('title', 'Valider le nouveau mot de passe')
@section('content')
    <section class="card auth">
        <h1>Valider le changement</h1><p class="muted">Code envoyé à {{ $adresse }}. Votre mot de passe ne change qu’après ce code.</p>
        <form class="stack" method="POST" action="{{ route('password.code.verifier') }}">@csrf
            <x-field name="code" label="Code à 6 chiffres" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required autofocus />
            <button class="btn btn-filled btn-block" type="submit">Changer mon mot de passe</button>
        </form>
        <a class="btn btn-block" href="{{ route('changepasswordView') }}">Recommencer</a>
    </section>
@endsection
