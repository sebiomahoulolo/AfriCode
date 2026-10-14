@extends('admin.layouts.app')

@section('breadcrumb', 'Gestion des tâches')

@section('content')
<div class="fade-in">
    <!-- En-tête -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">Gestion des tâches</h1>
                <p class="admin-card-subtitle">Créer et suivre les tâches administratives</p>
            </div>
            <div class="admin-card-actions">
                <a href="{{ route('admin.tasks.create') }}" style="background: linear-gradient(135deg, #11c06d, #239e79); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                    <i class="fas fa-plus-circle"></i> Nouvelle tâche
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des tâches -->
    <div class="admin-table-card" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-table-header">
            <h3 class="admin-table-title">Liste des tâches ({{ $tasks->total() }})</h3>
            @if($tasks->total() > 0)
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="color: #6C757D; font-size: 0.9rem;">
                        Affichage de {{ $tasks->firstItem() }} à {{ $tasks->lastItem() }} sur {{ $tasks->total() }} tâches
                    </div>
                </div>
            @endif
        </div>
        <div class="admin-table-body">
            @if(session('success'))
                <div style="padding: 1rem 1.5rem; margin: 0 1.5rem 1rem; background: rgba(35, 158, 121, 0.1); border-radius: 8px; color: #239e79; font-weight: 500;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Échéance</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #333;">{{ $task->title }}</div>
                                @if($task->description)
                                    <div style="font-size: 0.85rem; color: #6C757D; margin-top: 0.25rem; max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($task->description, 50) }}</div>
                                @endif
                            </td>
                            <td style="color: #6C757D;">{{ $task->due_date ? $task->due_date->format('d/m/Y') : '—' }}</td>
                            <td>
                                @if(($task->priority ?? 'medium') === 'high')
                                    <span style="background: rgba(250, 28, 57, 0.1); color: #fa1c39; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;"><i class="fas fa-arrow-up"></i> Haute</span>
                                @elseif(($task->priority ?? '') === 'low')
                                    <span style="background: rgba(108, 117, 125, 0.1); color: #6C757D; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">Basse</span>
                                @else
                                    <span style="background: linear-gradient(135deg, rgba(254, 168, 55, 0.1), rgba(255, 179, 102, 0.1)); color: #fea837; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">Moyenne</span>
                                @endif
                            </td>
                            <td>
                                @if($task->is_completed)
                                    <span style="background: rgba(35, 158, 121, 0.1); color: #239e79; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> Terminée
                                    </span>
                                @else
                                    <span style="background: linear-gradient(135deg, rgba(254, 168, 55, 0.1), rgba(255, 179, 102, 0.1)); color: #fea837; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-spinner"></i> En cours
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.tasks.edit', $task) }}" style="color: #fea837; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$task->is_completed)
                                        <form action="{{ route('admin.tasks.complete', $task) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" style="color: #239e79; padding: 0.5rem; border: none; background: none; border-radius: 6px; cursor: pointer; transition: all 0.2s;" title="Marquer terminée">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" onclick="if(confirm('Supprimer cette tâche ?')) document.getElementById('delete-task-{{ $task->id }}').submit();" style="color: #fa1c39; padding: 0.5rem; border: none; background: none; border-radius: 6px; cursor: pointer; transition: all 0.2s;" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-task-{{ $task->id }}" action="{{ route('admin.tasks.destroy', $task) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem; color: #6C757D;">
                                <i class="fas fa-tasks" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                                <div style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Aucune tâche</div>
                                <div style="font-size: 0.9rem; margin-bottom: 1.5rem;">Créez votre première tâche pour commencer.</div>
                                <a href="{{ route('admin.tasks.create') }}" style="background: linear-gradient(135deg, #11c06d, #239e79); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-block;">
                                    <i class="fas fa-plus"></i> Nouvelle tâche
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tasks->hasPages() && $tasks->total() > 0)
            <div class="admin-table-pagination" style="padding: 1.5rem; border-top: 1px solid #E9ECEF;">
                <nav aria-label="Navigation des pages" style="display: flex; justify-content: center;">
                    <ul class="pagination" style="justify-content: center; margin: 0; padding: 0; list-style: none; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @if($tasks->onFirstPage())
                            <li style="margin: 0;">
                                <span style="color: #6c757d; background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; cursor: not-allowed; opacity: 0.65; display: inline-flex; align-items: center; min-width: 38px;"><i class="fas fa-chevron-left"></i> Précédent</span>
                            </li>
                        @else
                            <li style="margin: 0;">
                                <a href="{{ $tasks->previousPageUrl() }}" aria-label="Précédent" style="color: #11c06d; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; min-width: 38px;"><i class="fas fa-chevron-left"></i> Précédent</a>
                            </li>
                        @endif
                        @foreach($tasks->getUrlRange(max(1, $tasks->currentPage() - 2), min($tasks->lastPage(), $tasks->currentPage() + 2)) as $page => $url)
                            @if($page == $tasks->currentPage())
                                <li style="margin: 0;">
                                    <span style="color: white; background-color: #11c06d; border: 1px solid #11c06d; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; min-width: 38px;">{{ $page }}</span>
                                </li>
                            @else
                                <li style="margin: 0;">
                                    <a href="{{ $url }}" style="color: #11c06d; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; min-width: 38px;">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                        @if($tasks->hasMorePages())
                            <li style="margin: 0;">
                                <a href="{{ $tasks->nextPageUrl() }}" aria-label="Suivant" style="color: #11c06d; background-color: white; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; min-width: 38px;">Suivant <i class="fas fa-chevron-right"></i></a>
                            </li>
                        @else
                            <li style="margin: 0;">
                                <span style="color: #6c757d; background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 0.5rem 0.75rem; border-radius: 0.375rem; cursor: not-allowed; opacity: 0.65; display: inline-flex; align-items: center; min-width: 38px;">Suivant <i class="fas fa-chevron-right"></i></span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.admin-table-pagination ul a:hover { background-color: rgba(17, 192, 109, 0.1) !important; border-color: #11c06d !important; }
.admin-table-actions form { display: inline; }
.admin-table-actions a:hover,
.admin-table-actions button:hover { background: rgba(0, 0, 0, 0.06) !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.admin-table-actions a, .admin-table-actions button').forEach(function(el) {
        el.addEventListener('mouseenter', function() { this.style.background = 'rgba(0, 0, 0, 0.1)'; this.style.transform = 'scale(1.1)'; });
        el.addEventListener('mouseleave', function() { this.style.background = 'none'; this.style.transform = 'scale(1)'; });
    });
    document.querySelectorAll('a[style*="background: linear-gradient"]').forEach(function(el) {
        el.addEventListener('mouseenter', function() { this.style.transform = 'translateY(-2px)'; this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)'; });
        el.addEventListener('mouseleave', function() { this.style.transform = 'translateY(0)'; this.style.boxShadow = 'none'; });
    });
});
</script>
@endpush
