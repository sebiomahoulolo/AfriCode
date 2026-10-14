@extends('admin.layouts.app')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="admin-content-header">
        <div class="admin-content-header-content">
            <h1 class="admin-content-title">
                <i class="fas fa-tasks"></i> Liste des Défis
            </h1>
            <nav class="admin-breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span>/</span>
                <span>Défis</span>
            </nav>
        </div>
        <div class="admin-content-actions">
            <a href="{{ route('admin.challenges.create') }}" class="admin-button admin-button-primary">
                <i class="fas fa-plus"></i> Créer un nouveau défi
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-card">
        <div class="admin-card-body">
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead class="admin-table-header">
                        <tr>
                            <th>Titre</th>
                            <th>Difficulté</th>
                            <th>Type</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse($challenges as $challenge)
                            <tr>
                                <td>
                                    <div class="admin-table-item-title">{{ $challenge->name }}</div>
                                    @if($challenge->description)
                                        <div class="admin-table-item-description">{{ Str::limit($challenge->description, 60) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="admin-badge admin-badge-{{ strtolower($challenge->difficulty) }}">
                                        {{ ucfirst($challenge->difficulty) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($challenge->type) }}</td>
                                <td>{{ $challenge->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="admin-table-actions">
                                        <a href="{{ route('admin.challenges.questions', $challenge->id) }}" class="admin-button-icon admin-button-icon-primary" title="Gérer les QCM">
                                            <i class="fas fa-question-circle"></i>
                                        </a>
                                        @if($challenge->type === 'competition' && $competition = \App\Models\Competition::where('title', $challenge->name)->first())
                                            <a href="{{ route('admin.competitions.testcases.index', $competition) }}" class="admin-button-icon admin-button-icon-primary" title="Gérer les cas de test">
                                                <i class="fas fa-vial"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-table-empty">
                                    <i class="fas fa-info-circle"></i> Aucun défi trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($challenges->hasPages())
                <div class="admin-table-pagination">
                    {{ $challenges->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .admin-content {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .admin-content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .admin-content-header-content {
        flex: 1;
    }

    .admin-content-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .admin-breadcrumb {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    .admin-breadcrumb a {
        color: #3498db;
        text-decoration: none;
    }

    .admin-breadcrumb a:hover {
        text-decoration: underline;
    }

    .admin-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .admin-card-body {
        padding: 20px;
    }

    .admin-table-responsive {
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .admin-table-header th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .admin-table-body tr {
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.2s;
    }

    .admin-table-body tr:hover {
        background-color: #f8f9fa;
    }

    .admin-table-body td {
        padding: 15px;
        vertical-align: middle;
        color: #495057;
    }

    .admin-table-item-title {
        font-weight: 500;
        color: #2c3e50;
    }

    .admin-table-item-description {
        font-size: 0.85rem;
        color: #7f8c8d;
        margin-top: 3px;
    }

    .admin-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-badge-debutant {
        background-color: #d4edda;
        color: #155724;
    }

    .admin-badge-intermediaire {
        background-color: #fff3cd;
        color: #856404;
    }

    .admin-badge-avance {
        background-color: #f8d7da;
        color: #721c24;
    }

    .admin-table-actions {
        display: flex;
        gap: 8px;
    }

    .admin-button-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background-color: transparent;
        border: 1px solid #dee2e6;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s;
    }

    .admin-button-icon:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    .admin-button-icon-primary {
        border-color: #3498db;
        color: #3498db;
    }

    .admin-button-icon-primary:hover {
        background-color: #e3f2fd;
    }

    .admin-button-icon-danger {
        border-color: #e74c3c;
        color: #e74c3c;
    }

    .admin-button-icon-danger:hover {
        background-color: #fde8e6;
    }

    .admin-table-empty {
        text-align: center;
        padding: 30px;
        color: #7f8c8d;
        font-style: italic;
    }

    .admin-table-pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .admin-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .admin-button-primary {
        background-color: #3498db;
        color: white;
    }

    .admin-button-primary:hover {
        background-color: #2980b9;
    }

    @media (max-width: 768px) {
        .admin-content-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .admin-table-actions {
            flex-direction: column;
            gap: 5px;
        }
        
        .admin-table-header {
            display: none;
        }
        
        .admin-table-body tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        
        .admin-table-body td {
            display: block;
            text-align: right;
            padding: 10px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .admin-table-body td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .admin-table-body td:last-child {
            border-bottom: 0;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adaptation pour l'affichage mobile
    if (window.innerWidth < 768) {
        const headers = document.querySelectorAll('.admin-table-header th');
        const cells = document.querySelectorAll('.admin-table-body td');
        
        headers.forEach((header, index) => {
            const label = header.textContent;
            cells.forEach(cell => {
                if (cell.cellIndex === index) {
                    cell.setAttribute('data-label', label);
                }
            });
        });
    }
});
</script>
@endsection