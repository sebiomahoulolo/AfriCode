@extends('layouts.layout')

@section('title', 'AfriCode - Compléter votre profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Compléter votre profil</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-edit fa-3x text-primary mb-3"></i>
                        <p>Merci de vous être inscrit ! Veuillez compléter les informations ci-dessous pour personnaliser votre expérience sur AfriCode.</p>
                    </div>

                    <form method="POST" action="{{ route('profile.complete.store') }}">
                        @csrf

                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="first_name" class="form-label fw-bold">Prénom</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" 
                                       required autofocus autocomplete="given-name">
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
                                    <i class="fas fa-user"></i>
                                </span>
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" 
                                       required autocomplete="family-name">
                            </div>
                            @error('last_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label for="role" class="form-label fw-bold">Vous êtes</label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected>Choisir votre profil</option>
                                <option value="apprenant" {{ old('role') == 'apprenant' ? 'selected' : '' }}>Apprenant</option>
                                <option value="formateur" {{ old('role') == 'formateur' ? 'selected' : '' }}>Formateur</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-save me-2"></i>Enregistrer et continuer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
