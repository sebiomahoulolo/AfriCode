@extends('admin.layouts.app')

@section('title', 'Créer une tâche')

@section('content')
<div class="admin-content">
    <div class="admin-content-header">
        <div class="admin-content-header-content">
            <h1 class="admin-content-title"><i class="fas fa-plus-circle"></i> Nouvelle tâche</h1>
            <nav class="admin-breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.tasks.index') }}">Tâches</a>
                <span>/</span>
                <span>Nouvelle</span>
            </nav>
        </div>
        <div class="admin-content-actions">
            <a href="{{ route('admin.tasks.index') }}" class="admin-button admin-button-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>
    </div>

    <div class="admin-form-container">
        <form action="{{ route('admin.tasks.store') }}" method="POST" class="admin-form">
            @csrf
            <div class="admin-form-section">
                <div class="admin-form-group">
                    <label for="title" class="admin-form-label">Titre *</label>
                    <input type="text" class="admin-form-input @error('title') error @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')<span class="admin-form-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-form-group">
                    <label for="description" class="admin-form-label">Description</label>
                    <textarea class="admin-form-input @error('description') error @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')<span class="admin-form-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-form-group">
                    <label for="due_date" class="admin-form-label">Date d'échéance</label>
                    <input type="date" class="admin-form-input @error('due_date') error @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                    @error('due_date')<span class="admin-form-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="admin-form-actions">
                <button type="submit" class="admin-button admin-button-primary"><i class="fas fa-save"></i> Créer</button>
                <a href="{{ route('admin.tasks.index') }}" class="admin-button admin-button-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
