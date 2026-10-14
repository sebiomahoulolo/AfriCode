@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Créer un nouveau sujet</h2>
    <form method="POST" action="{{ route('forum.store') }}">
        @csrf
        <div class="mb-3">
            <label for="course_id" class="form-label">Cours concerné</label>
            <select name="course_id" id="course_id" class="form-control" required>
                <option value="">-- Sélectionner un cours --</option>
                @foreach(App\Models\Course::all() as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Titre du sujet</label>
            <input type="text" name="title" id="title" class="form-control" required maxlength="255" value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <textarea name="content" id="content" class="form-control" rows="6" required>{{ old('content') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Publier le sujet</button>
        <a href="{{ route('forum.index') }}" class="btn btn-secondary ms-2">Annuler</a>
    </form>
</div>
@endsection 