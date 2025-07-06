@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Modifier le sujet</h2>
    <form method="POST" action="{{ route('forum.update', $forum->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Titre du sujet</label>
            <input type="text" name="title" id="title" class="form-control" required maxlength="255" value="{{ old('title', $forum->title) }}">
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <textarea name="content" id="content" class="form-control" rows="6" required>{{ old('content', $forum->content) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="{{ route('forum.show', $forum->id) }}" class="btn btn-secondary ms-2">Annuler</a>
    </form>
</div>
@endsection 