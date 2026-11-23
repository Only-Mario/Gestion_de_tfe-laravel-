@extends('layouts.app')
@section('title', 'Nouveau mot de passe')
@section('content')
    <section class="card auth">
        <h1>Nouveau mot de passe</h1>

        <form class="stack" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <x-field name="email" label="Adresse email" type="email" :value="$email ?? null" required autocomplete="email" />
            <x-field name="password" label="Nouveau mot de passe" type="password" required autocomplete="new-password" />
            <x-field name="password_confirmation" label="Confirmer" type="password" required autocomplete="new-password" />
            <button class="btn btn-filled btn-block" type="submit">Réinitialiser</button>
        </form>
    </section>
@endsection
