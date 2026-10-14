{{-- Vue profil pour les formateurs uniquement --}}
@extends('formateurs.layouts.app')
@section('title', 'AfriCode - Mon profil')
@section('page-title', 'Mon profil')
@section('page-subtitle', 'Gérez vos informations personnelles et vos paramètres de paiement')

@section('content')
<div class="profile-edit-container fade-in">
    {{-- Gestion centralisée de toutes les alertes --}}
    @include('profile.partials.alerts')

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8 mb-4">
            {{-- Formulaire d'informations personnelles (AVEC informations de paiement pour les formateurs) --}}
            @include('profile.partials.personal-information-form', [
                'user' => $user, 
                'isFormateur' => true, 
                'isAdmin' => false
            ])

            {{-- Formulaire de modification du mot de passe --}}
            @include('profile.partials.password-form')
        </div>

        <!-- Sidebar - Informations formateur -->
        @include('profile.partials.formateur-sidebar', ['user' => $user])
    </div>
</div>
@endsection

