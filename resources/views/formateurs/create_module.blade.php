@extends('formateurs.layouts.app')

@section('title', __('messages.add_module_title'))

@section('page-heading', __('messages.add_new_module'))
@section('page-subheading', __('messages.course_for', ['course' => $course->title]))

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('formateur.modules.store', ['courseId' => $course->id]) }}">
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
