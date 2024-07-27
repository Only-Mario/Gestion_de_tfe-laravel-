@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justity-content-center">
            <div class="col-md-8">
                <p>
                <h5>
                    Pour plus de détails sur ce tfe nous vous prions de vous <a href="{{ route('login') }}">connecter</a> si
                    vous avez déjà un compte , sinon veuillez bien <a href="{{ route('register') }}"> créer votre compte
                    </a>.
                </h5>
                </p>
            </div>
        </div>
    </div>
@stop
