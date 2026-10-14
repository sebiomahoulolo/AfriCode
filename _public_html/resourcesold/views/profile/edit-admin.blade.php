{{-- Vue profil pour les administrateurs uniquement --}}
@extends('admin.layouts.app')
@section('title', 'AfriCode - Mon profil')
@section('page-title', 'Mon profil')
@section('page-subtitle', 'Gérez vos informations personnelles')

@section('content')
<div class="profile-edit-container fade-in">
    {{-- Gestion centralisée de toutes les alertes --}}
    @include('profile.partials.alerts')

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-12 mb-4">
            {{-- Formulaire d'informations personnelles (SANS informations de paiement pour les admins) --}}
            @include('profile.partials.personal-information-form', [
                'user' => $user, 
                'isFormateur' => false, 
                'isAdmin' => true
            ])

            {{-- Formulaire de modification du mot de passe --}}
            @include('profile.partials.password-form')
        </div>
    </div>
</div>
@endsection

