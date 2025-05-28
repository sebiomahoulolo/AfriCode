@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="h3">Tableau de bord administrateur</h1>
        <div>
            <span class="badge bg-primary">Date: {{ now()->format('d/m/Y') }}</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header text-muted mb-0">Utilisateurs</h6>
                        <div class="stats-number">{{ $userCounts['total'] }}</div>
                        <div class="text-success small">+{{ $userCounts['newThisMonth'] }} ce mois</div>
                    </div>
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header text-muted mb-0">Cours</h6>
                        <div class="stats-number">{{ $courseCounts['total'] }}</div>
                        <div class="text-success small">{{ $courseCounts['published'] }} publiés</div>
                    </div>
                    <i class="fas fa-book fa-2x text-success"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header text-muted mb-0">Inscriptions</h6>
                        <div class="stats-number">{{ $enrollmentCounts['total'] }}</div>
                        <div class="text-success small">+{{ $enrollmentCounts['thisMonth'] }} ce mois</div>
                    </div>
                    <i class="fas fa-user-graduate fa-2x text-info"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header text-muted mb-0">Revenus</h6>
                        <div class="stats-number">{{ number_format($paymentStats['total'], 0, ',', ' ') }} XOF</div>
                        <div class="text-success small">{{ number_format($paymentStats['thisMonth'], 0, ',', ' ') }} XOF ce mois</div>
                    </div>
                    <i class="fas fa-money-bill fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row">
        <!-- User breakdown -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-card">
                <h5 class="card-header">Répartition des utilisateurs</h5>
                <div class="card-body">
                    <canvas id="userRoleChart" width="100" height="100"></canvas>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <div><i class="fas fa-circle text-primary"></i> Administrateurs: {{ $userCounts['admins'] }}</div>
                        <div><i class="fas fa-circle text-success"></i> Formateurs: {{ $userCounts['formateurs'] }}</div>
                        <div><i class="fas fa-circle text-info"></i> Apprenants: {{ $userCounts['apprenants'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Course breakdown -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-card">
                <h5 class="card-header">État des cours</h5>
                <div class="card-body">
                    <canvas id="courseStatusChart" width="100" height="100"></canvas>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <div><i class="fas fa-circle text-success"></i> Publiés: {{ $courseCounts['published'] }}</div>
                        <div><i class="fas fa-circle text-secondary"></i> Brouillons: {{ $courseCounts['draft'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Last enrollments chart -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-card">
                <h5 class="card-header">Inscriptions récentes</h5>
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="text-center">
                            <h1 class="mb-0">{{ $enrollmentCounts['today'] }}</h1>
                            <div class="small text-muted">Aujourd'hui</div>
                        </div>
                        <div class="text-center">
                            <h1 class="mb-0">{{ $enrollmentCounts['thisWeek'] }}</h1>
                            <div class="small text-muted">Cette semaine</div>
                        </div>
                        <div class="text-center">
                            <h1 class="mb-0">{{ $enrollmentCounts['thisMonth'] }}</h1>
                            <div class="small text-muted">Ce mois</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('admin.statistics.index') }}" class="btn btn-sm btn-outline-primary">Voir toutes les statistiques</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent users -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-header">Utilisateurs récents</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Tous les utilisateurs</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Inscrit le</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentUsers as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->role === 'administrateur')
                                            <span class="badge bg-primary">Admin</span>
                                        @elseif ($user->role === 'formateur')
                                            <span class="badge bg-success">Formateur</span>
                                        @elseif ($user->role === 'apprenant')
                                            <span class="badge bg-info">Apprenant</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun utilisateur récent</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent courses -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-header">Cours récents</h5>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-primary">Tous les cours</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Formateur</th>
                                <th>État</th>
                                <th>Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentCourses as $course)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.courses.show', $course) }}">
                                            {{ $course->title }}
                                        </a>
                                    </td>
                                    <td>{{ $course->formateur->first_name }} {{ $course->formateur->last_name }}</td>
                                    <td>
                                        @if ($course->status === 'published')
                                            <span class="badge bg-success">Publié</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td>{{ $course->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun cours récent</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent enrollments -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-header">Inscriptions récentes</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Apprenant</th>
                                <th>Cours</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentEnrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}</td>
                                    <td>{{ $enrollment->course->title }}</td>
                                    <td>{{ $enrollment->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Aucune inscription récente</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Tasks -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-header">Tâches en cours</h5>
                    <a href="{{ route('admin.tasks.create') }}" class="btn btn-sm btn-outline-primary">Nouvelle tâche</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($pendingTasks as $task)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $task->title }}</h6>
                                <small class="text-muted">Échéance: {{ $task->due_date->format('d/m/Y') }}</small>
                            </div>
                            <div>
                                <form action="{{ route('admin.tasks.complete', $task) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTask{{ $task->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteTask{{ $task->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Supprimer la tâche</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir supprimer cette tâche ?</p>
                                                <p class="fw-bold">{{ $task->title }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center">
                            <p class="mb-0">Aucune tâche en cours</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // User Role Chart
    const userRoleCtx = document.getElementById('userRoleChart').getContext('2d');
    const userRoleChart = new Chart(userRoleCtx, {
        type: 'doughnut',
        data: {
            labels: ['Administrateurs', 'Formateurs', 'Apprenants'],
            datasets: [{
                data: [{{ $userCounts['admins'] }}, {{ $userCounts['formateurs'] }}, {{ $userCounts['apprenants'] }}],
                backgroundColor: [
                    '#4F46E5', // Primary color
                    '#10B981', // Success color
                    '#3B82F6'  // Info color
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Course Status Chart
    const courseStatusCtx = document.getElementById('courseStatusChart').getContext('2d');
    const courseStatusChart = new Chart(courseStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Publiés', 'Brouillons'],
            datasets: [{
                data: [{{ $courseCounts['published'] }}, {{ $courseCounts['draft'] }}],
                backgroundColor: [
                    '#10B981', // Success color
                    '#9CA3AF'  // Secondary color
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endpush
