@extends('layouts.app')

@section('content')
 <h3 class="text-dark mb-4 ml-3">Se connecter</h3>
<div class="container">
    <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-flex">
                        <div class="flex-grow-1 bg-login-image" style="background-image: url(&quot;images/image.jpg&quot;);">     
                        </div>
                    </div>
                    <div class="col-lg-6 d-lg-flex">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Bienvenu</h4>
                            </div>
                            <form class="user form w-100" method="POST" action="{{ route('login') }}">
                                  @csrf
                                <div class="form-group">
                                  <input id="exampleInputEmail" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email Address...">
                                  @error('email')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                            </div>
                            <div class="form-group">  
                               <input id="exampleInputPassword" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                               @error('password')
                               <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                               </span>
                                @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox small">
                                 <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Se rappeler de moi') }}
                                    </label>
                                </div>
                         </div>
                     </div>
                     <button class="btn btn-primary btn-block text-white btn-user" type="submit"> {{ __('Se connecter') }}
                     </button>

                 </form>
                 <div class="text-center">
                    @if (Route::has('password.request'))
                    <a class="btn btn-link small" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié?') }}
                    </a>
                    @endif
                </div>
                {{__("Vous n'avez pas un compte?")}}
                <div class="text-center">
                    <a class="small" href="{{route('register')}}" style="text-decoration: underline;">S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

