@extends('admin.layouts.app')

@section('breadcrumb', 'Statistiques')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">Statistiques de la plateforme</h1>
                <p class="admin-card-subtitle">Analysez les performances et l'évolution de votre plateforme</p>
            </div>
            <div class="admin-card-actions">
                <div style="position: relative; display: inline-block;">
                    <button style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                        <i class="fas fa-calendar-alt"></i> Période
                    </button>
                </div>
                <a href="#" style="background: linear-gradient(135deg, #FF8E2A, #FFB366); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; margin-left: 0.5rem;">
                    <i class="fas fa-file-export"></i> Exporter
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="admin-grid admin-grid-4" style="margin-bottom: 2rem;">
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-stats-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="admin-stats-number">{{ array_sum(array_column($monthlyEnrollments, 'count')) }}</div>
            <div class="admin-stats-label">Inscriptions totales</div>
            <div class="admin-stats-change positive">
                <i class="fas fa-arrow-up"></i> +{{ $monthlyEnrollments[count($monthlyEnrollments) - 1]['count'] ?? 0 }} ce mois
            </div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #FF8E2A, #FFB366);">
                <i class="fas fa-euro-sign"></i>
            </div>
            <div class="admin-stats-number">{{ number_format(array_sum(array_column($monthlyRevenue, 'amount')), 0, ',', ' ') }}€</div>
            <div class="admin-stats-label">Revenu total</div>
            <div class="admin-stats-change positive">
                <i class="fas fa-arrow-up"></i> +{{ number_format($monthlyRevenue[count($monthlyRevenue) - 1]['amount'] ?? 0, 0, ',', ' ') }}€ ce mois
            </div>
                    </div>
                    <i class="fas fa-money-bill fa-2x text-success"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header text-muted mb-0">Cours publiés</h6>
                        <div class="stats-number">{{ $courseLevels['Débutant'] + $courseLevels['Intermédiaire'] + $courseLevels['Avancé'] }}</div>
                        <div class="text-success small">
                            {{ $courseLevels['Débutant'] }} débutant, {{ $courseLevels['Intermédiaire'] }} intermédiaire, {{ $courseLevels['Avancé'] }} avancé
                        </div>
                    </div>
                    <i class="fas fa-book fa-2x text-primary"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header text-muted mb-0">Utilisateurs actifs</h6>
                        <div class="stats-number">{{ $userRoles['Administrateurs'] + $userRoles['Formateurs'] + $userRoles['Apprenants'] }}</div>
                        <div class="text-success small">
                            {{ $userRoles['Administrateurs'] }} admin, {{ $userRoles['Formateurs'] }} form., {{ $userRoles['Apprenants'] }} app.
                        </div>
                    </div>
                    <i class="fas fa-users fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Enrollments Chart -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-header">Inscriptions mensuelles</h5>
                <div class="card-body">
                    <canvas id="enrollmentsChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Monthly Revenue Chart -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-header">Revenus mensuels</h5>
                <div class="card-body">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Category Distribution Chart -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-header">Cours par catégorie</h5>
                <div class="card-body">
                    <canvas id="categoryChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- User Roles Chart -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-header">Distribution des utilisateurs</h5>
                <div class="card-body">
                    <canvas id="userRolesChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Course Levels Chart -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-header">Niveaux des cours</h5>
                <div class="card-body">
                    <canvas id="courseLevelsChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Courses Table -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card">
                <h5 class="card-header">Cours les plus populaires</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Cours</th>
                                <th>Formateur</th>
                                <th>Catégorie</th>
                                <th>Inscriptions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topCourses as $course)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.courses.show', $course) }}">
                                            {{ $course->title }}
                                        </a>
                                    </td>
                                    <td>{{ $course->formateur->first_name }} {{ $course->formateur->last_name }}</td>
                                    <td>{{ $course->category->name ?? 'Non catégorisé' }}</td>
                                    <td>{{ $course->enrollments_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun cours trouvé</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Top Formateurs Table -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card">
                <h5 class="card-header">Formateurs les plus actifs</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Formateur</th>
                                <th>Cours</th>
                                <th>Inscriptions</th>
                                <th>Revenus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topFormateurs as $formateur)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.show', $formateur) }}">
                                            {{ $formateur->first_name }} {{ $formateur->last_name }}
                                        </a>
                                    </td>
                                    <td>{{ $formateur->coursesInstructed->count() }}</td>
                                    <td>{{ $formateur->total_enrollments }}</td>
                                    <td>{{ number_format(0, 0, ',', ' ') }} XOF</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun formateur trouvé</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Helper function to get months
    function getMonths() {
        return [
            @foreach($monthlyEnrollments as $data)
                "{{ $data['month'] }}",
            @endforeach
        ];
    }

    // Helper function to get enrollment counts
    function getEnrollmentCounts() {
        return [
            @foreach($monthlyEnrollments as $data)
                {{ $data['count'] }},
            @endforeach
        ];
    }

    // Helper function to get revenue amounts
    function getRevenueAmounts() {
        return [
            @foreach($monthlyRevenue as $data)
                {{ $data['amount'] }},
            @endforeach
        ];
    }

    // Monthly Enrollments Chart
    const enrollmentsCtx = document.getElementById('enrollmentsChart').getContext('2d');
    const enrollmentsChart = new Chart(enrollmentsCtx, {
        type: 'line',
        data: {
            labels: getMonths(),
            datasets: [{
                label: 'Inscriptions',
                data: getEnrollmentCounts(),
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: '#3B82F6',
                borderWidth: 2,
                tension: 0.3,
                pointBackgroundColor: '#3B82F6',
                pointRadius: 4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    padding: 10,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 14
                    },
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' inscriptions';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: getMonths(),
            datasets: [{
                label: 'Revenus',
                data: getRevenueAmounts(),
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: '#10B981',
                borderWidth: 2,
                tension: 0.3,
                pointBackgroundColor: '#10B981',
                pointRadius: 4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    padding: 10,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 14
                    },
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' XOF';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'k';
                            }
                            return value;
                        }
                    }
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($categoriesStats as $category)
                    "{{ $category->name }}",
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($categoriesStats as $category)
                        {{ $category->courses_count }},
                    @endforeach
                ],
                backgroundColor: [
                    '#4F46E5', '#10B981', '#3B82F6', '#F59E0B', '#EC4899', '#8B5CF6',
                    '#06B6D4', '#14B8A6', '#22C55E', '#EF4444', '#F97316'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        },
                        padding: 15
                    }
                }
            },
            cutout: '60%'
        }
    });

    // User Roles Chart
    const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
    const userRolesChart = new Chart(userRolesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Administrateurs', 'Formateurs', 'Apprenants'],
            datasets: [{
                data: [{{ $userRoles['Administrateurs'] }}, {{ $userRoles['Formateurs'] }}, {{ $userRoles['Apprenants'] }}],
                backgroundColor: ['#4F46E5', '#10B981', '#3B82F6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        },
                        padding: 15
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Course Levels Chart
    const courseLevelsCtx = document.getElementById('courseLevelsChart').getContext('2d');
    const courseLevelsChart = new Chart(courseLevelsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Débutant', 'Intermédiaire', 'Avancé'],
            datasets: [{
                data: [{{ $courseLevels['Débutant'] }}, {{ $courseLevels['Intermédiaire'] }}, {{ $courseLevels['Avancé'] }}],
                backgroundColor: ['#3B82F6', '#4F46E5', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        },
                        padding: 15
                    }
                }
            },
            cutout: '60%'
        }
    });
</script>
@endpush
