@extends('layouts.app')

@section('title', 'Ouvrir un ticket')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-plus-circle"></i> Ouvrir un ticket de support</h1>
    <form action="{{ route('support.tickets.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="subject" class="form-label">Sujet *</label>
            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Catégorie *</label>
            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" placeholder="ex: Technique, Facturation" required>
            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Priorité *</label>
            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Basse</option>
                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Moyenne</option>
                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Haute</option>
                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgente</option>
            </select>
            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description *</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">Envoyer</button>
        <a href="{{ route('support.tickets.index') }}" class="btn btn-outline-secondary">Annuler</a>
    </form>
</div>
@endsection
