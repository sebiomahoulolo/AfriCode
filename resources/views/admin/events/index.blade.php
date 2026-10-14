@extends('admin.layouts.app')

@section('breadcrumb', 'Événements')

@section('content')
<div class="fade-in">
    <!-- En-tête -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-calendar-alt me-2" style="color: #1EA38B;"></i>
                    Gestion des événements
                </h1>
                <p class="admin-card-subtitle">Calendrier : créez, modifiez ou supprimez des événements</p>
            </div>
            <div class="admin-card-actions">
                <a href="{{ route('admin.events.create') }}" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                    <i class="fas fa-plus-circle"></i> Nouvel événement
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des événements -->
    <div class="admin-table-card" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-table-header">
            <h3 class="admin-table-title">Liste des événements ({{ $events->total() }})</h3>
            @if($events->total() > 0)
                <div style="color: #6C757D; font-size: 0.9rem;">
                    Affichage de {{ $events->firstItem() }} à {{ $events->lastItem() }} sur {{ $events->total() }}
                </div>
            @endif
        </div>
        <div class="admin-table-body">
            @if(session('success'))
                <div style="padding: 1rem 1.5rem; margin: 0 1.5rem 1rem; background: rgba(39, 179, 113, 0.1); border-radius: 8px; color: #27B371; font-weight: 500;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #2C3E50;">{{ $event->title }}</div>
                                @if($event->description)
                                    <div style="font-size: 0.85rem; color: #6C757D; margin-top: 0.25rem; max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($event->description, 55) }}</div>
                                @endif
                            </td>
                            <td style="color: #6C757D;">
                                <i class="fas fa-play-circle me-1" style="color: #1EA38B;"></i>
                                {{ $event->start_datetime ? $event->start_datetime->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td style="color: #6C757D;">
                                <i class="fas fa-stop-circle me-1" style="color: #FF8E2A;"></i>
                                {{ $event->end_datetime ? $event->end_datetime->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.events.show', $event) }}" style="color: #1EA38B; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}" style="color: #FF8E2A; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="if(confirm('Supprimer cet événement ?')) document.getElementById('delete-event-{{ $event->id }}').submit();" style="color: #E32D31; padding: 0.5rem; border: none; background: none; border-radius: 6px; cursor: pointer; transition: all 0.2s;" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-event-{{ $event->id }}" action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 3rem; color: #6C757D;">
                                <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                                <div style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Aucun événement</div>
                                <div style="font-size: 0.9rem; margin-bottom: 1.5rem;">Créez votre premier événement pour le calendrier.</div>
                                <a href="{{ route('admin.events.create') }}" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-block;">
                                    <i class="fas fa-plus"></i> Nouvel événement
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages() && $events->total() > 0)
            <div class="admin-table-pagination" style="padding: 1.5rem; border-top: 1px solid #E9ECEF;">
                <nav aria-label="Navigation" style="display: flex; justify-content: center;">
                    {{ $events->withQueryString()->links() }}
                </nav>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.admin-table-actions a:hover, .admin-table-actions button:hover { background: rgba(0, 0, 0, 0.06) !important; }
.admin-table-actions form { display: inline; }
</style>
@endpush
@endsection
