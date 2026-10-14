@extends('admin.layouts.app')

@section('breadcrumb', 'Événements')

@section('content')
<div class="fade-in">
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-calendar-alt me-2" style="color: #11c06d;"></i>
                    {{ $event->title }}
                </h1>
                <p class="admin-card-subtitle mb-0">Dashboard / Événements / Détail</p>
            </div>
            <div class="admin-card-actions">
                <a href="{{ route('admin.events.edit', $event) }}" style="background: linear-gradient(135deg, #fea837, #FFB366); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
                <a href="{{ route('admin.events.index') }}" style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="admin-card mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-card-body">
            <div class="admin-form-section">
                <h3 class="admin-form-section-title" style="color: #11c06d; margin-bottom: 1rem;"><i class="fas fa-info-circle me-2"></i>Détails de l'événement</h3>
                <div style="display: grid; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: rgba(17, 192, 109, 0.08); border-radius: 8px; border-left: 4px solid #11c06d;">
                        <i class="fas fa-play-circle" style="color: #11c06d; font-size: 1.25rem;"></i>
                        <div>
                            <div style="font-size: 0.8rem; color: #6C757D; text-transform: uppercase; font-weight: 600;">Début</div>
                            <div style="font-weight: 600; color: #2C3E50;">{{ $event->start_datetime ? $event->start_datetime->format('d/m/Y à H:i') : '—' }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: rgba(254, 168, 55, 0.08); border-radius: 8px; border-left: 4px solid #fea837;">
                        <i class="fas fa-stop-circle" style="color: #fea837; font-size: 1.25rem;"></i>
                        <div>
                            <div style="font-size: 0.8rem; color: #6C757D; text-transform: uppercase; font-weight: 600;">Fin</div>
                            <div style="font-weight: 600; color: #2C3E50;">{{ $event->end_datetime ? $event->end_datetime->format('d/m/Y à H:i') : '—' }}</div>
                        </div>
                    </div>
                    @if($event->description)
                        <div style="padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                            <div style="font-size: 0.8rem; color: #6C757D; text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Description</div>
                            <div style="color: #2C3E50; white-space: pre-line;">{{ $event->description }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
