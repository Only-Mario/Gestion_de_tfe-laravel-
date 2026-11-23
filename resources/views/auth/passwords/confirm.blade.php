@extends('layouts.app')
@section('title', 'Confirmer votre mot de passe')
@section('content')
    <section class="card auth">
        <h1>Confirmer votre mot de passe</h1>

        <form class="stack" method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <x-field name="password" label="Mot de passe" type="password" required autocomplete="current-password" />
            <button class="btn btn-filled btn-block" type="submit">Confirmer</button>
        </form>
    </section>
@endsection
