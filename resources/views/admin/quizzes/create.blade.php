@extends('admin.layouts.app')

@section('title', 'Créer un Quiz')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        Créer un nouveau quiz pour le module: {{ $module->title }}
                    </h5>
                    <a href="{{ route('admin.courses.show', $module->course_id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour au cours
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quizzes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="module_id" value="{{ $module->id }}">
                        <input type="hidden" name="related_type" value="App\Models\Module">
                        <input type="hidden" name="related_id" value="{{ $module->id }}">

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="title" class="form-label">Titre du quiz <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="passing_score" class="form-label">Score minimum (%)</label>
                                <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                                       id="passing_score" name="passing_score" value="{{ old('passing_score', 70) }}" 
                                       min="0" max="100">
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="time_limit" class="form-label">Temps limite (minutes)</label>
                                <input type="number" class="form-control @error('time_limit') is-invalid @enderror" 
                                       id="time_limit" name="time_limit" value="{{ old('time_limit') }}" 
                                       min="1" placeholder="Laissez vide pour aucune limite">
                                @error('time_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_required" name="is_required" 
                                           value="1" {{ old('is_required') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required">
                                        Quiz obligatoire pour compléter le module
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Instructions et informations pour les étudiants...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Information :</strong> Une fois le quiz créé, vous pourrez y ajouter des questions 
                            depuis la page d'édition.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.courses.show', $module->course_id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Créer le quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
