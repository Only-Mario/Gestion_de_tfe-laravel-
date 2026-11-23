@extends('layouts.app')
@section('title', 'Connexion')
@section('content')
    <section class="card auth">
        <img class="auth-logo" src="{{ \App\Http\Controllers\RessourceController::url('logo') }}" alt="Logo de l’INSTI" width="88" height="88">
        <h1>Connexion</h1><p class="muted">Déposez et suivez vos documents.</p>
        <form class="stack" method="POST" action="{{ route('login') }}">
            @csrf
            <x-field name="email" label="Adresse email" type="email" required autocomplete="email" autofocus />
            <x-field name="password" label="Mot de passe" type="password" required autocomplete="current-password" />
            <label class="check"><input type="checkbox" name="remember" value="1"> Rester connecté</label>
            <button class="btn btn-filled btn-block" type="submit">Se connecter</button>
        </form>
        <div class="row between" style="margin-top:16px"><a class="btn btn-sm" href="{{ route('password.request') }}">Mot de passe oublié ?</a><a class="btn btn-sm" href="{{ route('register') }}">Créer un compte</a></div>
    </section>
@endsection
