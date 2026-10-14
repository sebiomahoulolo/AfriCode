@extends('admin.layouts.app')

@section('breadcrumb', 'Modifier le poste')
@section('title', 'Modifier le poste')

@section('content')
<div class="fade-in">
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.25rem;">
                    <i class="fas fa-edit" style="color: #1EA38B;"></i> Modifier le poste
                </h1>
                <p class="admin-card-subtitle mb-0">{{ $position->title }}</p>
            </div>
            <a href="{{ route('admin.recruitment.index') }}" style="background: linear-gradient(135deg, #6C757D, #95A5A6); color: white; padding: 0.6rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-arrow-left me-1"></i> Retour au recrutement
            </a>
        </div>
    </div>

    <div class="admin-card" data-aos="fade-up" data-aos-delay="50" style="margin-bottom: 1.5rem;">
        <div class="admin-card-body">
            <div style="background: linear-gradient(135deg, rgba(30, 163, 139, 0.08), rgba(39, 179, 113, 0.08)); padding: 1rem 1.25rem; border-radius: 8px; border-left: 4px solid #1EA38B; margin-bottom: 1.5rem;">
                <i class="fas fa-info-circle" style="color: #1EA38B;"></i>
                Définissez ici la <strong>date limite de candidature</strong> ainsi que les autres informations du poste. Le poste se ferme automatiquement une fois la date limite passée.
            </div>

            <form action="{{ route('admin.recruitment.positions.update', $position->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="admin-form-group" style="margin-bottom: 1.25rem;">
                    <label for="title" class="admin-form-label">Titre du poste <span class="text-danger">*</span></label>
                    <input type="text" class="admin-form-input @error('title') error @enderror" id="title" name="title" value="{{ old('title', $position->title) }}" required>
                    @error('title') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-grid admin-grid-2" style="gap: 1.25rem;">
                    <div class="admin-form-group" style="margin-bottom: 1.25rem;">
                        <label for="places_count" class="admin-form-label">Nombre de places <span class="text-danger">*</span></label>
                        <input type="number" class="admin-form-input @error('places_count') error @enderror" id="places_count" name="places_count" value="{{ old('places_count', $position->places_count) }}" min="1" required>
                        @error('places_count') <span class="admin-form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="admin-form-group" style="margin-bottom: 1.25rem;">
                        <label for="deadline" class="admin-form-label">Date limite de candidature</label>
                        <input type="date" class="admin-form-input @error('deadline') error @enderror" id="deadline" name="deadline" value="{{ old('deadline', $position->deadline?->format('Y-m-d')) }}">
                        <div class="form-text" style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Laissez vide pour un poste sans date limite.</div>
                        @error('deadline') <span class="admin-form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="admin-form-group" style="margin-bottom: 1.25rem;">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $position->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Poste actif (visible sur la page de recrutement)</label>
                    </div>
                    @error('is_active') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-form-group" style="margin-bottom: 1.25rem;">
                    <label for="missions" class="admin-form-label">Missions principales</label>
                    <textarea class="admin-form-input @error('missions') error @enderror" id="missions" name="missions" rows="5">{{ old('missions', $position->missions) }}</textarea>
                    <div class="form-text" style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Une mission par ligne.</div>
                    @error('missions') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-form-group" style="margin-bottom: 1.25rem;">
                    <label for="profil" class="admin-form-label">Profil recherché</label>
                    <textarea class="admin-form-input @error('profil') error @enderror" id="profil" name="profil" rows="5">{{ old('profil', $position->profil) }}</textarea>
                    <div class="form-text" style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Un critère par ligne.</div>
                    @error('profil') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-form-group" style="margin-bottom: 1.5rem;">
                    <label for="dossier_fournir" class="admin-form-label">Dossier à fournir</label>
                    <textarea class="admin-form-input @error('dossier_fournir') error @enderror" id="dossier_fournir" name="dossier_fournir" rows="4">{{ old('dossier_fournir', $position->dossier_fournir) }}</textarea>
                    <div class="form-text" style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Un document par ligne.</div>
                    @error('dossier_fournir') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
                    <button type="submit" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.6rem 1.25rem; border: none; border-radius: 8px; font-weight: 500;">
                        <i class="fas fa-save me-1"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.recruitment.index') }}" style="color: #6C757D; padding: 0.6rem 1.25rem; text-decoration: none;">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
