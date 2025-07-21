@extends('admin.layouts.app')

@section('title', 'Ajouter un Utilisateur')

@section('content')
<div class="admin-content-header">
    <h1><i class="fas fa-user-plus"></i> Nouveau Utilisateur</h1>
    <nav class="admin-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
        <span>/</span>
        <span>Nouveau</span>
    </nav>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fas fa-user-plus"></i> Informations utilisateur</h2>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf

            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label for="name" class="admin-form-label">Nom *</label>
                    <input type="text" class="admin-form-input @error('name') error @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>
                <div class="admin-form-group">
                    <label for="surname" class="admin-form-label">Prénom</label>
                    <input type="text" class="admin-form-input @error('surname') error @enderror" id="surname" name="surname" value="{{ old('surname') }}">
                    @error('surname') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="admin-form-group">
                <label for="email" class="admin-form-label">Email *</label>
                <input type="email" class="admin-form-input @error('email') error @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email') <span class="admin-form-error">{{ $message }}</span> @enderror
            </div>

            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label for="password" class="admin-form-label">Mot de passe *</label>
                    <input type="password" class="admin-form-input @error('password') error @enderror" id="password" name="password" required>
                    @error('password') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>
                <div class="admin-form-group">
                    <label for="password_confirmation" class="admin-form-label">Confirmer Mot de passe *</label>
                    <input type="password" class="admin-form-input" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label for="role" class="admin-form-label">Rôle *</label>
                <select class="admin-form-select" id="role" name="role" required>
                    <option value="">Sélectionner un rôle</option>
                    <option value="apprenant" {{ old('role') == 'apprenant' ? 'selected' : '' }}>Apprenant</option>
                    <option value="formateur" {{ old('role') == 'formateur' ? 'selected' : '' }}>Formateur</option>
                    <option value="administrateur" {{ old('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                </select>
            </div>

            <div class="admin-form-group">
                <label for="avatar" class="admin-form-label">Avatar (Optionnel)</label>
                <input type="file" class="admin-form-input @error('avatar') error @enderror" id="avatar" name="avatar" accept="image/*">
                @error('avatar') <span class="admin-form-error">{{ $message }}</span> @enderror
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection