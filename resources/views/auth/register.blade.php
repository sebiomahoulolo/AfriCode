@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')
<div class="container py-5 px-2">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow rounded-lg">
                <div class="card-header  text-white text-center py-3" style="background-color:  #1EA38B" >
                    <h4 class="mb-0">Créer votre compte</h4>
                </div>
                
                    
                    @include('auth.partials.social-buttons')
                    <hr class="my-4">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="first_name" class="form-label fw-bold">Prénom</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user" style="color:  #FF8E2A;"></i>
                                </span>
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="given-name">
                            </div>
                            @error('first_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="last_name" class="form-label fw-bold">Nom</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user" style="color:  #FF8E2A;"></i>
                                </span>
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">
                            </div>
                            @error('last_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-envelope" style="color:  #FF8E2A;"></i>
                                </span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="username">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Role Selection -->
                        <div class="mb-3">
                            <label for="role" class="form-label fw-bold">Vous êtes</label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="" disabled selected>Choisir votre profil</option>
                                <option value="apprenant" {{ old('role') == 'apprenant' ? 'selected' : '' }}>Apprenant</option>
                                {{-- <option value="formateur" {{ old('role') == 'formateur' ? 'selected' : '' }}>Formateur</option> --}}
                            </select>
                            @error('role')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock" style="color:  #FF8E2A;"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            </div>
                            <small class="form-text text-muted">Au moins 8 caractères, une majuscule et un chiffre</small>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">Confirmer le mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock" style="color:  #FF8E2A;"></i>
                                </span>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label" for="terms">
                                J’accepte les <a href="{{ route('pages.terms') }}" class="text-decoration-none" target="_blank">conditions d’utilisation</a> et la <a href="{{ route('pages.privacy') }}" class="text-decoration-none" target="_blank">politique de confidentialité</a> d’AfriCode.
                            </label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <a class="text-decoration-none" href="{{ route('login') }}">
                                Déjà inscrit ?
                            </a>
                            <button type="submit" class="btn px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-primary" style="background-color:  #1EA38B; color:white" aria-label="Créer mon compte AfriCode">
                                <i class="fas fa-user-plus me-2" style="color:  #FF8E2A;"></i>S'inscrire
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
