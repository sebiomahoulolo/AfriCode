@extends('admin.layouts.app')

@section('breadcrumb', 'Statistiques')

@push('styles')
<style>
.card {
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Espacement entre toutes les cartes */
.stats-row .col-xl-3,
.charts-row .col-lg-8,
.charts-row .col-lg-4,
.secondary-charts-row .col-lg-6,
.tables-row .col-lg-6 {
    padding: 0 15px;
    margin-bottom: 30px;
}

/* S'assurer que les 4 cartes de stats restent sur la même ligne */
.stats-row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px 30px -15px;
}

.stats-row .col-xl-3 {
    flex: 0 0 25%;
    max-width: 25%;
}

/* Ligne des graphiques principaux */
.charts-row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px 30px -15px;
}

.charts-row .col-lg-8 {
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
}

.charts-row .col-lg-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
}

/* Ligne des graphiques secondaires */
.secondary-charts-row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px 30px -15px;
}

.secondary-charts-row .col-lg-6 {
    flex: 0 0 50%;
    max-width: 50%;
}

/* Ligne des tableaux */
.tables-row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px 30px -15px;
}

.tables-row .col-lg-6 {
    flex: 0 0 50%;
    max-width: 50%;
}

/* Responsive pour tablettes */
@media (max-width: 1199px) {
    .stats-row .col-xl-3 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .charts-row .col-lg-8,
    .charts-row .col-lg-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

/* Responsive pour mobiles */
@media (max-width: 767px) {
    .stats-row .col-xl-3,
    .secondary-charts-row .col-lg-6,
    .tables-row .col-lg-6 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }
    
    .stats-row,
    .charts-row,
    .secondary-charts-row,
    .tables-row {
        margin: 0 -10px 20px -10px;
    }
    
    .stats-row .col-xl-3,
    .charts-row .col-lg-8,
    .charts-row .col-lg-4,
    .secondary-charts-row .col-lg-6,
    .tables-row .col-lg-6 {
        padding: 0 10px;
    }
}

.chart-container {
    position: relative;
    height: 300px;
}

.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}

/* Animation d'apparition */
.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="h3 mb-2 text-gray-800">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Tableau de bord
                    </h1>
                    <p class="text-muted">Vue d'ensemble des performances de la plateforme</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Ligne 1: Les 4 cartes sur la même ligne -->
    <div class="stats-row fade-in">
        <!-- Total Users -->
        <div class="col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 rounded-circle" style="background: linear-gradient(135deg, #0d6efd, #0056b3);">
                                <i class="fas fa-users fa-lg text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted mb-1">Utilisateurs total</div>
                            <div class="h4 mb-0 fw-bold">{{ array_sum($userRoles) }}</div>
                            <div class="small text-success">
                                <i class="fas fa-arrow-up"></i> +12% ce mois
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Courses -->
        <div class="col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 rounded-circle" style="background: linear-gradient(135deg, #198754, #146c43);">
                                <i class="fas fa-graduation-cap fa-lg text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted mb-1">Cours disponibles</div>
                            <div class="h4 mb-0 fw-bold">{{ $topCourses->count() }}</div>
                            <div class="small text-success">
                                <i class="fas fa-arrow-up"></i> +5% ce mois
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Enrollments -->
        <div class="col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 rounded-circle" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
                                <i class="fas fa-user-plus fa-lg text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted mb-1">Inscriptions</div>
                            <div class="h4 mb-0 fw-bold">{{ array_sum(array_column($monthlyEnrollments, 'count')) }}</div>
                            <div class="small text-success">
                                <i class="fas fa-arrow-up"></i> +18% ce mois
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 rounded-circle" style="background: linear-gradient(135deg, #fd7e14, #e55a00);">
                                <i class="fas fa-euro-sign fa-lg text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted mb-1">Revenus (XOF)</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format(array_sum(array_column($monthlyRevenue, 'amount')), 0, ',', ' ') }}</div>
                            <div class="small text-success">
                                <i class="fas fa-arrow-up"></i> +8% ce mois
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ligne 2: Graphiques principaux - les deux sur la même ligne -->
    <div class="charts-row fade-in">
        <!-- Graphique des inscriptions mensuelles -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Évolution des inscriptions
                    </h5>
                    <p class="text-muted small mb-0">Tendance des inscriptions sur les 6 derniers mois</p>
                </div>
                <div class="card-body">
                    <canvas id="enrollmentsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Répartition des utilisateurs -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users-cog me-2 text-success"></i>Répartition des utilisateurs
                    </h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="userDistributionChart" width="300" height="300"></canvas>
                </div>
                <div class="card-footer bg-light">
                    @foreach($userRoles as $role => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center">
                            <span class="badge me-2" style="background-color: {{ $role === 'Apprenants' ? '#0d6efd' : ($role === 'Formateurs' ? '#198754' : '#fd7e14') }}; width: 12px; height: 12px;"></span>
                            {{ $role }}
                        </span>
                        <strong>{{ $count }}</strong>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Ligne 3: Graphiques secondaires - les deux sur la même ligne -->
    <div class="secondary-charts-row fade-in">
        <!-- Revenus mensuels -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2 text-warning"></i>Revenus mensuels
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Progression des cours -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-graduation-cap me-2 text-info"></i>Progression des cours
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="coursesProgressChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Ligne 4: Tables Section - les deux tableaux sur la même ligne -->
    @if($topCourses->count() > 0 || $topFormateurs->count() > 0)
    <div class="tables-row fade-in">
        <!-- Top Courses -->
        @if($topCourses->count() > 0)
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2 text-warning"></i>Cours les plus populaires
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Cours</th>
                                    <th>Inscriptions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCourses as $course)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.courses.show', $course) }}" class="text-decoration-none">
                                            {{ Str::limit($course->title, 30) }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            {{ $course->formateur->first_name ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $course->enrollments_count }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Top Instructors -->
        @if($topFormateurs->count() > 0)
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chalkboard-teacher me-2 text-success"></i>Formateurs les plus actifs
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Formateur</th>
                                    <th>Inscriptions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topFormateurs as $formateur)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.show', $formateur) }}" class="text-decoration-none">
                                            {{ $formateur->first_name }} {{ $formateur->last_name }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            {{ $formateur->coursesInstructed->count() }} cours
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $formateur->total_enrollments }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des couleurs
    const colors = {
        primary: '#0d6efd',
        success: '#198754',
        warning: '#fd7e14',
        info: '#0dcaf0',
        danger: '#dc3545'
    };

    // Graphique des inscriptions mensuelles
    const enrollmentsCtx = document.getElementById('enrollmentsChart');
    if (enrollmentsCtx) {
        new Chart(enrollmentsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyEnrollments, 'month')) !!},
                datasets: [{
                    label: 'Inscriptions',
                    data: {!! json_encode(array_column($monthlyEnrollments, 'count')) !!},
                    borderColor: colors.primary,
                    backgroundColor: colors.primary + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f3f4'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Graphique de répartition des utilisateurs
    const userDistributionCtx = document.getElementById('userDistributionChart');
    if (userDistributionCtx) {
        new Chart(userDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($userRoles)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($userRoles)) !!},
                    backgroundColor: [colors.primary, colors.success, colors.warning],
                    borderWidth: 0,
                    cutout: '60%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Graphique des revenus mensuels
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
                datasets: [{
                    label: 'Revenus (XOF)',
                    data: {!! json_encode(array_column($monthlyRevenue, 'amount')) !!},
                    backgroundColor: colors.warning + '80',
                    borderColor: colors.warning,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f3f4'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' XOF';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Graphique de progression des cours
    const coursesProgressCtx = document.getElementById('coursesProgressChart');
    if (coursesProgressCtx) {
        // Données simulées pour la progression des cours
        const progressData = [
            { status: 'Complétés', count: 45, color: colors.success },
            { status: 'En cours', count: 32, color: colors.info },
            { status: 'En attente', count: 18, color: colors.warning },
            { status: 'Abandonnés', count: 5, color: colors.danger }
        ];

        new Chart(coursesProgressCtx, {
            type: 'bar',
            data: {
                labels: progressData.map(item => item.status),
                datasets: [{
                    label: 'Nombre de cours',
                    data: progressData.map(item => item.count),
                    backgroundColor: progressData.map(item => item.color + '80'),
                    borderColor: progressData.map(item => item.color),
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f3f4'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>

@endsection