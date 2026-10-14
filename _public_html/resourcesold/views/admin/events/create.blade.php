@extends('admin.layouts.app')

@section('breadcrumb', 'Événements')

@section('content')
<div class="fade-in">
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-calendar-plus me-2" style="color: #11c06d;"></i>
                    Créer un événement
                </h1>
                <p class="admin-card-subtitle mb-0">Dashboard / Événements / Nouveau</p>
            </div>
            <div class="admin-card-actions">
                <a href="{{ route('admin.events.index') }}" style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="admin-card mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-card-body">
            <form action="{{ route('admin.events.store') }}" method="POST" class="admin-form">
                @csrf
                <div class="admin-form-section">
                    <h3 class="admin-form-section-title" style="color: #11c06d;"><i class="fas fa-info-circle me-2"></i>Informations</h3>
                    <div class="admin-form-group">
                        <label for="title" class="admin-form-label">Titre *</label>
                        <input type="text" class="admin-form-input @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="Ex. Webinaire PHP">
                        @error('title')<span class="admin-form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="start_datetime" class="admin-form-label">Date et heure de début *</label>
                        <input type="datetime-local" class="admin-form-input @error('start_datetime') is-invalid @enderror" id="start_datetime" name="start_datetime" value="{{ old('start_datetime') }}" required>
                        @error('start_datetime')<span class="admin-form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="end_datetime" class="admin-form-label">Date et heure de fin</label>
                        <input type="datetime-local" class="admin-form-input @error('end_datetime') is-invalid @enderror" id="end_datetime" name="end_datetime" value="{{ old('end_datetime') }}">
                        @error('end_datetime')<span class="admin-form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label for="description" class="admin-form-label">Description</label>
                        <textarea class="admin-form-input @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Description optionnelle">{{ old('description') }}</textarea>
                        @error('description')<span class="admin-form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="admin-form-actions" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #E9ECEF;">
                    <button type="submit" style="background: linear-gradient(135deg, #11c06d, #239e79); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
                        <i class="fas fa-save me-1"></i> Créer
                    </button>
                    <a href="{{ route('admin.events.index') }}" style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
