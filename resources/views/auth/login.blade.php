@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')
<div class="container py-5 px-2">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow rounded-lg">
                <div class="card-header  text-white text-center py-3" style="background-color:  #1EA38B" >
                    <h4 class="mb-0">Connexion à votre compte</h4>
                </div>
                    @include('auth.partials.social-buttons')
                 <hr class="my-4">
                   
                <div class="card-body p-4 p-md-5">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-envelope" style="color:  #FF8E2A;"></i>
                                </span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock" style="color:  #FF8E2A;"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember_me">
                                Se souvenir de moi
                            </label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    Mot de passe oublié?
                                </a>
                            @endif
                            <button type="submit" class="btn px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-primary" style="background-color:  #1EA38B; color:white" aria-label="Se connecter à mon compte">
                                <i class="fas fa-sign-in-alt me-2" style="color:  #FF8E2A;"></i>Connexion
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 pt-3 border-top text-center">
                        <p class="mb-3">Pas encore de compte ?</p>
                        <a href="{{ route('register') }}" class="btn w-full focus:outline-none focus:ring-2 focus:ring-primary" style="background-color:  #1EA38B ; color:white" aria-label="Créer un compte AfriCode">
                            <i class="fas fa-user-plus me-2" style="color:  #FF8E2A;"></i>S'inscrire
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
