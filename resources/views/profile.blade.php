@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card m3-card-elevation-1">
                <div class="card-header m3-card-header">
                    <h4 class="mb-0 m3-on-primary-container-text">Mon Profil</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Section -->
                        <div class="d-flex align-items-center mb-4">
                            <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/64/EADDFF/21005D?text=AV' }}"
                                 alt="Avatar" class="rounded-circle mr-3" style="width: 64px; height: 64px; object-fit: cover;">
                            <div>
                                <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                                <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                                <div class="custom-file mt-2">
                                    <input type="file" class="custom-file-input" id="avatar" name="avatar">
                                    <label class="custom-file-label" for="avatar">Changer l'avatar</label>
                                </div>
                            </div>
                        </div>

                        <!-- Nom d'utilisateur -->
                        <div class="m3-form-group">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                   placeholder=" " value="{{ old('name', Auth::user()->name) }}" required autocomplete="name" autofocus>
                            <label for="name">Nom d'utilisateur</label>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Adresse Email -->
                        <div class="m3-form-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                   placeholder=" " value="{{ old('email', Auth::user()->email) }}" required autocomplete="email">
                            <label for="email">Adresse Email</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary m3-button-primary">
                                <i class="material-icons mr-2">save</i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script pour afficher le nom du fichier sélectionné dans le custom-file-input
    document.querySelector('.custom-file-input').addEventListener('change', function (e) {
        var fileName = document.getElementById("avatar").files[0].name;
        var nextSibling = e.target.nextElementSibling
        nextSibling.innerText = fileName
    })
</script>
@endpush
