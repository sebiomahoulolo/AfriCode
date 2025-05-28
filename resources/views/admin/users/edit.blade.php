@extends('admin.layouts.app')

@section('title', 'Modifier un Utilisateur')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-edit fa-fw me-1"></i> Modifier l'utilisateur
            </h6>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left fa-fw"></i> Retour à la liste
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="surname" class="form-label">Prénom</label>
                        <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', $user->surname) }}">
                        @error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
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