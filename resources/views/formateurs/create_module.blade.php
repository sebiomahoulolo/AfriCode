@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Ajouter un module')
@section('page-title', 'Ajouter un nouveau module')
@section('page-subtitle', 'Cours: ' . $course->title)

@section('header-actions')
    <a href="{{ route('formateur.manage.course', $course) }}" class="btn btn-outline-secondary me-2">
        <i class="fas fa-arrow-left me-2"></i>Retour au cours
    </a>
    <button type="submit" form="create-module-form" class="btn-primary-africode">
        <i class="fas fa-plus me-2"></i>Créer le module
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
        
        .list-group-item {
            padding: 0.75rem;
        }
        
        .list-group-item h6 {
            font-size: 0.9rem;
        }
        
        .list-group-item small {
            font-size: 0.75rem;
        }
        
        .btn {
            font-size: 0.875rem;
            padding: 0.625rem 1rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .d-flex.justify-content-between .btn {
            width: 100%;
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
        
        .list-group-item {
            padding: 0.5rem;
        }
        
        .list-group-item h6 {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        
        .list-group-item small {
            font-size: 0.7rem;
        }
        
        .list-group-item p {
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }
        
        .btn {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
        
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        
        h5 {
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .card-body-modern {
            padding: 0.5rem;
        }
        
        .form-control {
            font-size: 0.8rem;
            padding: 0.45rem 0.65rem;
        }
        
        .btn {
            font-size: 0.75rem;
            padding: 0.45rem 0.65rem;
        }
        
        .list-group-item {
            padding: 0.375rem;
        }
        
        .list-group-item .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .list-group-item small {
            margin-top: 0.25rem;
        }
    }
</style>
@endsection

@section('content')
    <div class="card-modern" data-aos="fade-up">
        <div class="card-body-modern">
            <form id="create-module-form" method="POST" action="{{ route('formateur.modules.store', ['courseId' => $course->id]) }}">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Titre du module <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    <div class="form-text">Donnez un titre clair qui décrit le contenu du module.</div>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description du module</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    <div class="form-text">Une brève description du contenu et des objectifs du module.</div>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <h5 class="mb-3">Modules existants</h5>
                    <div class="list-group mb-3">
                        @forelse($course->modules as $module)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h6 class="mb-1">{{ $module->order }}. {{ $module->title }}</h6>
                                    <small>{{ $module->lessons->count() }} leçon(s)</small>
                                </div>
                                @if($module->description)
                                    <p class="mb-1 text-muted small">{{ Str::limit($module->description, 100) }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="list-group-item text-muted">Aucun module existant. Ce sera le premier module du cours.</div>
                        @endforelse
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Ajouter le module</button>
                </div>
            </form>
        </div>
    </div>
@endsection
