@extends('layouts.app')
@section('title', 'Changer mon mot de passe')
@section('content')
    <section class="card auth">
        <h1>Changer mon mot de passe</h1>

        <form class="stack" method="POST" action="{{ route('passwordUpdate') }}">
            @csrf
            <x-field name="password" label="Mot de passe actuel" type="password" required autocomplete="current-password" />
            <x-field name="new_password" label="Nouveau mot de passe" type="password" required autocomplete="new-password" hint="8 caractères au moins, avec des lettres et des chiffres." />
            <x-field name="comfirm_new_password" label="Confirmer" type="password" required autocomplete="new-password" />
            <button class="btn btn-filled btn-block" type="submit">Enregistrer</button>
        </form>
    </section>
@endsection
