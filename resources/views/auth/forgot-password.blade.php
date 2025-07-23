@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')
<div class="container py-5 px-2">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow rounded-lg">
                <div class="card-header  text-white text-center py-3" style="background-color:  #1EA38B">
                    <h4 class="mb-0">Récupération de mot de passe</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4 text-center">
                        <i class="fas fa-lock fa-3x mb-3 " style="color:  #FF8E2A;"></i>
                        <p>Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation.</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-envelope" style="color:  #FF8E2A;"></i>
                                </span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block" style="color:  #FF8E2A;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn py-2 w-full focus:outline-none focus:ring-2 focus:ring-primary" style="background-color:  #1EA38B; color:white" aria-label="Envoyer le lien de réinitialisation du mot de passe">
                                <i class="fas fa-paper-plane me-2" style="color:  #FF8E2A;"></i>Envoyer le lien de réinitialisation
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1" style="color:  #FF8E2A;"></i> Retour à la page de connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
