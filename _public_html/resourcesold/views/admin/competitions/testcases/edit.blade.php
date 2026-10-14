@extends('admin.layouts.app')

@section('content')
<div class="admin-content">
    <div class="admin-content-header">
        <h1 class="admin-content-title">
            <i class="fas fa-edit"></i> Modifier le cas de test #{{ $testcase->id }} de la compétition : {{ $competition->title }}
        </h1>
        <a href="{{ route('admin.competitions.testcases.index', $competition) }}" class="admin-button admin-button-outline">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div class="admin-card">
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.competitions.testcases.update', [$competition, $testcase]) }}">
                @csrf
                @method('PUT')
                <div class="admin-form-group">
                    <label for="input" class="admin-form-label">Entrée (stdin)</label>
                    <textarea id="input" name="input" class="admin-form-textarea" rows="3">{{ old('input', $testcase->input) }}</textarea>
                </div>
                <div class="admin-form-group">
                    <label for="expected_output" class="admin-form-label">Sortie attendue *</label>
                    <textarea id="expected_output" name="expected_output" class="admin-form-textarea" rows="3" required>{{ old('expected_output', $testcase->expected_output) }}</textarea>
                </div>
                <button type="submit" class="admin-button admin-button-primary">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 