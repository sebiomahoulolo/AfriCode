@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Modifier le module')
@section('page-title', 'Modifier le module')
@section('page-subtitle', 'Cours: ' . $course->title)

@section('header-actions')
    <a href="{{ route('formateur.manage.module', ['moduleId' => $module->id]) }}" class="btn btn-outline-secondary me-2">
        <i class="fas fa-arrow-left me-2"></i>Retour au module
    </a>
    <button type="submit" form="edit-module-form" class="btn-primary-africode">
        <i class="fas fa-save me-2"></i>Enregistrer les modifications
    </button>
@endsection

@section('styles')
<style>
    /* Responsive amélioré */
    @media (max-width: 768px) {
        .card-body-modern {
            padding: 1rem;
        }
        
        .form-label {
            font-size: 0.9rem;
        }
        
        .form-control {
            font-size: 0.9rem;
        }
        
        .form-text {
            font-size: 0.75rem;
        }
        
        .btn {
            font-size: 0.875rem;
            padding: 0.625rem 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .card-body-modern {
            padding: 0.75rem;
        }
        
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.375rem;
        }
        
        .form-control {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }
        
        .form-text {
            font-size: 0.7rem;
            margin-top: 0.25rem;
        }
        
        .btn {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
    }
</style>
@endsection

@section('content')
    <div class="card-modern" data-aos="fade-up">
        <div class="card-body-modern">
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Module {{ $module->order }}</strong> - Vous pouvez modifier les informations de ce module.
            </div>
            
            <form id="edit-module-form" method="POST" action="{{ route('formateur.modules.update', ['moduleId' => $module->id]) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">Titre du module <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $module->title) }}" 
                           required>
                    <div class="form-text">Donnez un titre clair qui décrit le contenu du module.</div>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description du module</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4">{{ old('description', $module->description) }}</textarea>
                    <div class="form-text">Une brève description du contenu et des objectifs du module.</div>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="order" class="form-label">Ordre du module</label>
                    <input type="number" 
                           class="form-control @error('order') is-invalid @enderror" 
                           id="order" 
                           name="order" 
                           value="{{ old('order', $module->order) }}" 
                           min="1">
                    <div class="form-text">L'ordre détermine la position du module dans le cours. Plus le nombre est petit, plus le module apparaîtra tôt.</div>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Informations du module</h6>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Cours:</strong> {{ $course->title }}</li>
                                <li><strong>Nombre de leçons:</strong> {{ $module->lessons->count() }}</li>
                                <li><strong>Quiz:</strong> {{ $module->quiz ? 'Oui' : 'Non' }}</li>
                                <li><strong>Date de création:</strong> {{ $module->created_at->format('d/m/Y à H:i') }}</li>
                                <li><strong>Dernière modification:</strong> {{ $module->updated_at->format('d/m/Y à H:i') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('formateur.manage.module', ['moduleId' => $module->id]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Annuler
                    </a>
                    <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                        <i class="fas fa-save me-1"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

