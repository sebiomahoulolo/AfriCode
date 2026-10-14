@extends('admin.layouts.app')

@section('breadcrumb', 'Recrutement')

@section('content')
<div class="fade-in">
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-briefcase me-2" style="color: #1EA38B;"></i>
                    Recrutement AfriCode
                </h1>
                <p class="admin-card-subtitle">Postes ouverts et candidatures</p>
            </div>
            <div>
                <a href="{{ route('admin.recruitment.applications.index') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-list me-1"></i> Voir toutes les candidatures
                </a>
            </div>
        </div>
    </div>

    <div class="admin-grid admin-grid-3" style="margin-bottom: 2rem;" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-stats-card">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #1EA38B, #27B371);">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="admin-stats-number">{{ $totalApplications }}</div>
            <div class="admin-stats-label">Candidatures totales</div>
        </div>
        <div class="admin-stats-card">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #27B371, #2ecc71);">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="admin-stats-number">{{ $completedCount }}</div>
            <div class="admin-stats-label">Test complété</div>
        </div>
        <div class="admin-stats-card">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #FF8E2A, #FFB366);">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="admin-stats-number">{{ $positions->count() }}</div>
            <div class="admin-stats-label">Postes ouverts</div>
        </div>
    </div>

    <div class="admin-card" data-aos="fade-up" data-aos-delay="150">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Postes et nombre de candidatures</h3>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Poste</th>
                            <th>Places</th>
                            <th>Candidatures</th>
                            <th>Date limite</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($positions as $position)
                        <tr>
                            <td><strong>{{ $position->title }}</strong></td>
                            <td>{{ $position->places_count }}</td>
                            <td>
                                <a href="{{ route('admin.recruitment.applications.index', ['position' => $position->id]) }}">
                                    {{ $position->applications_count }} candidature(s)
                                </a>
                            </td>
                            <td>{{ $position->deadline?->format('d/m/Y') ?? '—' }}</td>
                            <td>
                                @if($position->isOpen())
                                    <span class="badge" style="background:#27B371;">Ouvert</span>
                                @else
                                    <span class="badge bg-secondary">Fermé</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.recruitment.positions.edit', $position->id) }}" class="btn btn-sm" style="border:1.5px solid #1EA38B;color:#1EA38B;">
                                    <i class="fas fa-edit me-1"></i> Modifier
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Dernières candidatures</h3>
            <a href="{{ route('admin.recruitment.applications.index') }}" class="btn btn-sm" style="background: #6C757D; color: white; padding: 0.4rem 0.8rem; border-radius: 6px; text-decoration: none;">Voir tout</a>
        </div>
        <div class="admin-card-body p-0">
            @if($recentApplications->isEmpty())
                <p class="text-muted p-4 mb-0">Aucune candidature pour le moment.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Poste</th>
                                <th>Score</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplications as $app)
                            <tr>
                                <td>{{ $app->nom_prenoms }}</td>
                                <td>{{ $app->email }}</td>
                                <td>{{ $app->jobPosition->title }}</td>
                                <td>
                                    @if($app->test_score !== null)
                                        <span class="badge" style="background:{{ $app->test_score >= 7 ? '#27B371' : ($app->test_score >= 5 ? '#FF8E2A' : '#6C757D') }};">{{ $app->test_score }}/10</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $app->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.recruitment.applications.show', $app) }}" class="btn btn-sm" style="border:1.5px solid #1EA38B;color:#1EA38B;">Détail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
