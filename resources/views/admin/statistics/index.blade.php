@extends('admin.layouts.app')

@section('breadcrumb', 'Statistiques')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div class="admin-card-header-content">
                <h1 class="admin-card-title">Statistiques de la plateforme</h1>
                <p class="admin-card-subtitle">Analysez les performances et l'évolution de votre plateforme</p>
            </div>
            <div class="admin-card-actions">
                <button class="admin-button admin-button-primary">
                    <i class="fas fa-calendar-alt"></i> Période
                </button>
                <button class="admin-button admin-button-secondary">
                    <i class="fas fa-file-export"></i> Exporter
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="admin-stats-grid">
        <!-- Total Enrollments Card -->
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-stats-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="admin-stats-content">
                <div class="admin-stats-number">{{ array_sum(array_column($monthlyEnrollments, 'count')) }}</div>
                <div class="admin-stats-label">Inscriptions totales</div>
                <div class="admin-stats-change positive">
                    <i class="fas fa-arrow-up"></i> +{{ $monthlyEnrollments[count($monthlyEnrollments) - 1]['count'] ?? 0 }} ce mois
                </div>
            </div>
        </div>
        
        <!-- Total Revenue Card -->
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-stats-icon" style="background-color: #10B981;">
                <i class="fas fa-euro-sign"></i>
            </div>
            <div class="admin-stats-content">
                <div class="admin-stats-number">{{ number_format(array_sum(array_column($monthlyRevenue, 'amount')), 0, ',', ' ') }}€</div>
                <div class="admin-stats-label">Revenu total</div>
                <div class="admin-stats-change positive">
                    <i class="fas fa-arrow-up"></i> +{{ number_format($monthlyRevenue[count($monthlyRevenue) - 1]['amount'] ?? 0, 0, ',', ' ') }}€ ce mois
                </div>
            </div>
        </div>
        
        <!-- Published Courses Card -->
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-stats-icon" style="background-color: #3B82F6;">
                <i class="fas fa-book"></i>
            </div>
            <div class="admin-stats-content">
                <div class="admin-stats-number">{{ $courseLevels['Débutant'] + $courseLevels['Intermédiaire'] + $courseLevels['Avancé'] }}</div>
                <div class="admin-stats-label">Cours publiés</div>
                <div class="admin-stats-details">
                    {{ $courseLevels['Débutant'] }} débutant, {{ $courseLevels['Intermédiaire'] }} intermédiaire, {{ $courseLevels['Avancé'] }} avancé
                </div>
            </div>
        </div>
        
        <!-- Active Users Card -->
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="400">
            <div class="admin-stats-icon" style="background-color: #F59E0B;">
                <i class="fas fa-users"></i>
            </div>
            <div class="admin-stats-content">
                <div class="admin-stats-number">{{ $userRoles['Administrateurs'] + $userRoles['Formateurs'] + $userRoles['Apprenants'] }}</div>
                <div class="admin-stats-label">Utilisateurs actifs</div>
                <div class="admin-stats-details">
                    {{ $userRoles['Administrateurs'] }} admin, {{ $userRoles['Formateurs'] }} form., {{ $userRoles['Apprenants'] }} app.
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="admin-charts-row">
        <!-- Monthly Enrollments Chart -->
        <div class="admin-chart-card" data-aos="fade-up">
            <div class="admin-chart-header">
                <h3>Inscriptions mensuelles</h3>
            </div>
            <div class="admin-chart-body">
                <canvas id="enrollmentsChart" height="250"></canvas>
            </div>
        </div>
        
        <!-- Monthly Revenue Chart -->
        <div class="admin-chart-card" data-aos="fade-up">
            <div class="admin-chart-header">
                <h3>Revenus mensuels</h3>
            </div>
            <div class="admin-chart-body">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="admin-charts-row">
        <!-- Category Distribution Chart -->
        <div class="admin-chart-card" data-aos="fade-up">
            <div class="admin-chart-header">
                <h3>Cours par catégorie</h3>
            </div>
            <div class="admin-chart-body">
                <canvas id="categoryChart" height="250"></canvas>
            </div>
        </div>
        
        <!-- User Roles Chart -->
        <div class="admin-chart-card" data-aos="fade-up">
            <div class="admin-chart-header">
                <h3>Distribution des utilisateurs</h3>
            </div>
            <div class="admin-chart-body">
                <canvas id="userRolesChart" height="250"></canvas>
            </div>
        </div>
        
        <!-- Course Levels Chart -->
        <div class="admin-chart-card" data-aos="fade-up">
            <div class="admin-chart-header">
                <h3>Niveaux des cours</h3>
            </div>
            <div class="admin-chart-body">
                <canvas id="courseLevelsChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="admin-tables-row">
        <!-- Top Courses Table -->
        <div class="admin-table-card" data-aos="fade-up">
            <div class="admin-table-header">
                <h3>Cours les plus populaires</h3>
            </div>
            <div class="admin-table-body">
                <table class="admin-table">
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
        
        <!-- Top Formateurs Table -->
        <div class="admin-table-card" data-aos="fade-up">
            <div class="admin-table-header">
                <h3>Formateurs les plus actifs</h3>
            </div>
            <div class="admin-table-body">
                <table class="admin-table">
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
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
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
                    },
                    ticks: {
                        color: '#6B7280'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#6B7280'
                    },
                    grid: {
                        color: 'rgba(229, 231, 235, 0.5)'
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
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
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
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
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
                    },
                    ticks: {
                        color: '#6B7280'
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
                        },
                        color: '#6B7280'
                    },
                    grid: {
                        color: 'rgba(229, 231, 235, 0.5)'
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
                        padding: 15,
                        color: '#6B7280'
                    }
                }
            },
            cutout: '65%'
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
                        padding: 15,
                        color: '#6B7280'
                    }
                }
            },
            cutout: '65%'
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
                        padding: 15,
                        color: '#6B7280'
                    }
                }
            },
            cutout: '65%'
        }
    });
</script>
@endpush