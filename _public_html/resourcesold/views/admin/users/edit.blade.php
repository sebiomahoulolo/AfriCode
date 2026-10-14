@extends('admin.layouts.app')

@section('title', 'Modifier un Utilisateur')

@section('content')
<div class="admin-content-header">
    <h1><i class="fas fa-user-edit"></i> Modifier l'utilisateur</h1>
    <nav class="admin-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
        <span>/</span>
        <span>Modifier</span>
    </nav>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fas fa-user-edit"></i> Informations utilisateur</h2>
        <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf
            @method('PUT')

            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label for="name" class="admin-form-label">Nom *</label>
                    <input type="text" class="admin-form-input @error('name') error @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>
                
                <div class="admin-form-group">
                    <label for="surname" class="admin-form-label">Prénom</label>
                    <input type="text" class="admin-form-input @error('surname') error @enderror" id="surname" name="surname" value="{{ old('surname', $user->surname) }}">
                    @error('surname') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="admin-form-group">
                <label for="email" class="admin-form-label">Email *</label>
                <input type="email" class="admin-form-input @error('email') error @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <span class="admin-form-error">{{ $message }}</span> @enderror
            </div>

            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label for="password" class="admin-form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" class="admin-form-input @error('password') error @enderror" id="password" name="password">
                    @error('password') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>
                
                <div class="admin-form-group">
                    <label for="password_confirmation" class="admin-form-label">Confirmer le mot de passe</label>
                    <input type="password" class="admin-form-input" id="password_confirmation" name="password_confirmation">
                </div>
            </div>

            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label for="role" class="admin-form-label">Rôle *</label>
                    <select class="admin-form-select @error('role') error @enderror" id="role" name="role" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="apprenant" {{ old('role', $user->role) == 'apprenant' ? 'selected' : '' }}>Apprenant</option>
                        <option value="formateur" {{ old('role', $user->role) == 'formateur' ? 'selected' : '' }}>Formateur</option>
                        <option value="administrateur" {{ old('role', $user->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                    @error('role') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-form-group">
                    <label for="status" class="admin-form-label">Statut *</label>
                    <select class="admin-form-select @error('status') error @enderror" id="status" name="status" required>
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('status') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="admin-form-group">
                <label for="avatar" class="admin-form-label">Avatar</label>
                <div class="admin-form-grid">
                    <div class="admin-form-group">
                        @if($user->avatar)
                            <div class="admin-avatar-preview">
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar actuel" class="admin-avatar-current">
                                <span class="admin-avatar-label">Avatar actuel</span>
                            </div>
                        @else
                            <div class="admin-avatar-placeholder">
                                <i class="fas fa-user"></i>
                                <span>Pas d'avatar</span>
                            </div>
                        @endif
                    </div>
                    <div class="admin-form-group">
                        <input type="file" class="admin-form-input @error('avatar') error @enderror" id="avatar" name="avatar" accept="image/*">
                        <small class="admin-form-help">Formats acceptés: JPG, PNG, GIF. Taille max: 2 MB</small>
                        @error('avatar') <span class="admin-form-error">{{ $message }}</span> @enderror
                        
                        @if($user->avatar)
                            <div class="admin-form-check">
                                <input type="checkbox" class="admin-form-checkbox" id="remove_avatar" name="remove_avatar" value="1">
                                <label class="admin-form-check-label" for="remove_avatar">Supprimer l'avatar actuel</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="administrateur" {{ old('role', $user->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            <option value="formateur" {{ old('role', $user->role) == 'formateur' ? 'selected' : '' }}>Formateur</option>
                            <option value="apprenant" {{ old('role', $user->role) == 'apprenant' ? 'selected' : '' }}>Apprenant</option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Statut du compte</label>
                        <div class="d-flex mt-2">
                            <div class="form-check me-3">
                                <input type="radio" class="form-check-input" id="status_active" name="status" value="active" {{ old('status', $user->status) == 'active' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_active">Actif</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="status_inactive" name="status" value="inactive" {{ old('status', $user->status) == 'inactive' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_inactive">Inactif</label>
                            </div>
                        </div>
                        @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="avatar" class="form-label">Avatar</label>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar actuel" class="img-fluid rounded mb-2" style="max-height: 150px;">
                                <p class="small text-muted">Avatar actuel</p>
                            @else
                                <div class="text-center p-3 border rounded bg-light">
                                    <i class="fas fa-user fa-2x text-secondary"></i>
                                    <p class="small text-muted mt-1">Pas d'avatar</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                            <div class="form-text">Formats acceptés: JPG, PNG, GIF. Taille max: 2 MB</div>
                            @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            
                            @if($user->avatar)
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" id="remove_avatar" name="remove_avatar" value="1">
                                    <label class="form-check-label" for="remove_avatar">Supprimer l'avatar actuel</label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save fa-fw"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection