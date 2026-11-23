@extends('layouts.app')
@section('title', 'Mot de passe oublié')
@section('content')
    <section class="card auth">
        <h1>Mot de passe oublié</h1>
        <p class="muted">Nous vous enverrons un lien de réinitialisation.</p>
        <form class="stack" method="POST" action="{{ route('password.email') }}">
            @csrf
            <x-field name="email" label="Adresse email" type="email" required autocomplete="email" autofocus />
            <button class="btn btn-filled btn-block" type="submit">Envoyer le lien</button>
        </form>
    </section>
@endsection
