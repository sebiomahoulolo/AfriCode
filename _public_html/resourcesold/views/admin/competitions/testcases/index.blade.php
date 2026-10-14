@extends('admin.layouts.app')

@section('content')
<div class="admin-content">
    <div class="admin-content-header">
        <h1 class="admin-content-title">
            <i class="fas fa-vial"></i> Cas de test pour la compétition : {{ $competition->title }}
        </h1>
        <a href="{{ route('admin.competitions.testcases.create', $competition) }}" class="admin-button admin-button-primary">
            <i class="fas fa-plus"></i> Ajouter un cas de test
        </a>
    </div>
    @if(session('success'))
        <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
    @endif
    <div class="admin-card">
        <div class="admin-card-body">
            @if($testcases->isEmpty())
                <p>Aucun cas de test pour cette compétition.</p>
            @else
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Entrée</th>
                            <th>Sortie attendue</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testcases as $testcase)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><pre>{{ $testcase->input }}</pre></td>
                            <td><pre>{{ $testcase->expected_output }}</pre></td>
                            <td>
                                <a href="{{ route('admin.competitions.testcases.edit', [$competition, $testcase]) }}" class="admin-button admin-button-sm admin-button-outline">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <form action="{{ route('admin.competitions.testcases.destroy', [$competition, $testcase]) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Supprimer ce cas de test ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-button admin-button-sm admin-button-danger">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <a href="{{ route('admin.challenges.index') }}" class="admin-button admin-button-outline mt-3"><i class="fas fa-arrow-left"></i> Retour aux compétitions</a>
</div>
@endsection 