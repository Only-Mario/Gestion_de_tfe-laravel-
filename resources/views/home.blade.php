@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: var(--m3-primary-container); color: var(--m3-on-primary-container);">
                    <h4 class="mb-0">Tableau de Bord</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="lead">Bienvenue, {{ Auth::user()->name }} !</p>
                    <p>Vous êtes connecté(e).</p>

                    <hr class="my-4">

                    <form>
                        <div class="m3-form-group">
                            <input type="text" class="form-control" id="name" placeholder=" " value="{{ Auth::user()->name }}">
                            <label for="name">Nom d'utilisateur</label>
                        </div>

                        <div class="m3-form-group">
                            <input type="email" class="form-control" id="email" placeholder=" " value="{{ Auth::user()->email }}">
                            <label for="email">Adresse Email</label>
                        </div>

                        <div class="m3-form-group">
                            <textarea class="form-control" id="message" rows="3" placeholder=" "></textarea>
                            <label for="message">Votre Message</label>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="material-icons mr-2">send</i> Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
