@extends('admin.layouts.app')

@section('breadcrumb', 'Statistiques')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.8rem; margin-bottom: 0.5rem;">Statistiques de la plateforme</h1>
                <p class="admin-card-subtitle">Analysez les performances et l'évolution de votre plateforme</p>
            </div>
            <div class="admin-card-actions">
                <button style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; margin-right: 0.5rem; cursor: pointer;">
                    <i class="fas fa-calendar-alt me-2"></i>Période
                </button>
                <button style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
                    <i class="fas fa-file-export me-2"></i>Exporter
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Grid - Responsive 4 columns on desktop, 2 on tablet, 1 on mobile -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Total Enrollments -->
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="100" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); border-left: 4px solid #1EA38B;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-graduate" style="font-size: 1.25rem;"></i>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 2rem; font-weight: 700; color: #1EA38B; line-height: 1;">{{ array_sum(array_column($monthlyEnrollments, 'count')) }}</div>
                    <div style="font-size: 0.875rem; color: #6C757D; font-weight: 500;">Inscriptions totales</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                <span style="background: rgba(39, 179, 113, 0.1); color: #27B371; padding: 0.25rem 0.5rem; border-radius: 20px; font-weight: 600;">
                    <i class="fas fa-arrow-up me-1"></i>+{{ $monthlyEnrollments[count($monthlyEnrollments) - 1]['count'] ?? 0 }}
                </span>
                <span style="color: #6C757D;">ce mois</span>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="200" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); border-left: 4px solid #10B981;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="background: linear-gradient(135deg, #10B981, #34D399); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-euro-sign" style="font-size: 1.25rem;"></i>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 2rem; font-weight: 700; color: #10B981; line-height: 1;">{{ number_format(array_sum(array_column($monthlyRevenue, 'amount')), 0, ',', ' ') }}€</div>
                    <div style="font-size: 0.875rem; color: #6C757D; font-weight: 500;">Revenu total</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                <span style="background: rgba(16, 185, 129, 0.1); color: #10B981; padding: 0.25rem 0.5rem; border-radius: 20px; font-weight: 600;">
                    <i class="fas fa-arrow-up me-1"></i>+{{ number_format($monthlyRevenue[count($monthlyRevenue) - 1]['amount'] ?? 0, 0, ',', ' ') }}€
                </span>
                <span style="color: #6C757D;">ce mois</span>
            </div>
        </div>

        <!-- Published Courses -->
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="300" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); border-left: 4px solid #3B82F6;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="background: linear-gradient(135deg, #3B82F6, #60A5FA); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-book" style="font-size: 1.25rem;"></i>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 2rem; font-weight: 700; color: #3B82F6; line-height: 1;">{{ $courseLevels['Débutant'] + $courseLevels['Intermédiaire'] + $courseLevels['Avancé'] }}</div>
                    <div style="font-size: 0.875rem; color: #6C757D; font-weight: 500;">Cours publiés</div>
                </div>
            </div>
            <div style="font-size: 0.875rem; color: #6C757D; line-height: 1.4;">
                <span style="color: #3B82F6; font-weight: 600;">{{ $courseLevels['Débutant'] }}</span> débutant, 
                <span style="color: #3B82F6; font-weight: 600;">{{ $courseLevels['Intermédiaire'] }}</span> intermédiaire, 
                <span style="color: #3B82F6; font-weight: 600;">{{ $courseLevels['Avancé'] }}</span> avancé
            </div>
        </div>

        <!-- Active Users -->
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="400" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); border-left: 4px solid #F59E0B;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="background: linear-gradient(135deg, #F59E0B, #FBBF24); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="font-size: 1.25rem;"></i>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 2rem; font-weight: 700; color: #F59E0B; line-height: 1;">{{ $userRoles['Administrateurs'] + $userRoles['Formateurs'] + $userRoles['Apprenants'] }}</div>
                    <div style="font-size: 0.875rem; color: #6C757D; font-weight: 500;">Utilisateurs actifs</div>
                </div>
            </div>
            <div style="font-size: 0.875rem; color: #6C757D; line-height: 1.4;">
                <span style="color: #F59E0B; font-weight: 600;">{{ $userRoles['Administrateurs'] }}</span> admin, 
                <span style="color: #F59E0B; font-weight: 600;">{{ $userRoles['Formateurs'] }}</span> formateurs, 
                <span style="color: #F59E0B; font-weight: 600;">{{ $userRoles['Apprenants'] }}</span> apprenants
            </div>
        </div>
    </div>

    <!-- Charts Grid - 2 columns on desktop, 1 on mobile -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
        <!-- Monthly Enrollments Chart -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Inscriptions mensuelles
                </h3>
                <div style="font-size: 0.875rem; color: #6C757D;">Évolution des inscriptions</div>
            </div>
            <div class="admin-card-body">
                <canvas id="enrollmentsChart" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-chart-area me-2 text-success"></i>Revenus mensuels
                </h3>
                <div style="font-size: 0.875rem; color: #6C757D;">Évolution des revenus</div>
            </div>
            <div class="admin-card-body">
                <canvas id="revenueChart" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
        <!-- Course Levels Chart -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-chart-pie me-2 text-info"></i>Répartition par niveau
                </h3>
                <div style="font-size: 0.875rem; color: #6C757D;">Distribution des cours</div>
            </div>
            <div class="admin-card-body">
                <canvas id="courseLevelsChart" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>

        <!-- User Roles Chart -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="400">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-chart-donut me-2 text-warning"></i>Types d'utilisateurs
                </h3>
                <div style="font-size: 0.875rem; color: #6C757D;">Répartition des rôles</div>
            </div>
            <div class="admin-card-body">
                <canvas id="userRolesChart" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="500">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-tachometer-alt me-2 text-danger"></i>Statistiques rapides
                </h3>
                <div style="font-size: 0.875rem; color: #6C757D;">Métriques clés</div>
            </div>
            <div class="admin-card-body">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; justify-content: between; align-items: center; padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                        <div>
                            <div style="font-size: 0.875rem; color: #6C757D; margin-bottom: 0.25rem;">Taux de conversion</div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: #1EA38B;">
                                {{ $userRoles['Apprenants'] > 0 ? number_format((array_sum(array_column($monthlyEnrollments, 'count')) / $userRoles['Apprenants']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-percentage"></i>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                        <div>
                            <div style="font-size: 0.875rem; color: #6C757D; margin-bottom: 0.25rem;">Revenu moyen par cours</div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: #10B981;">
                                {{ ($courseLevels['Débutant'] + $courseLevels['Intermédiaire'] + $courseLevels['Avancé']) > 0 ? number_format(array_sum(array_column($monthlyRevenue, 'amount')) / ($courseLevels['Débutant'] + $courseLevels['Intermédiaire'] + $courseLevels['Avancé']), 0, ',', ' ') : 0 }}€
                            </div>
                        </div>
                        <div style="background: linear-gradient(135deg, #10B981, #34D399); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                        <div>
                            <div style="font-size: 0.875rem; color: #6C757D; margin-bottom: 0.25rem;">Formateurs actifs</div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: #3B82F6;">{{ $userRoles['Formateurs'] }}</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #3B82F6, #60A5FA); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                        <div>
                            <div style="font-size: 0.875rem; color: #6C757D; margin-bottom: 0.25rem;">Croissance mensuelle</div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: #F59E0B;">
                                +{{ count($monthlyEnrollments) > 1 ? number_format((($monthlyEnrollments[count($monthlyEnrollments) - 1]['count'] - $monthlyEnrollments[count($monthlyEnrollments) - 2]['count']) / max($monthlyEnrollments[count($monthlyEnrollments) - 2]['count'], 1)) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div style="background: linear-gradient(135deg, #F59E0B, #FBBF24); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-trending-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Animation
    AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true
    });

    // Chart colors
    const colors = {
        primary: '#1EA38B',
        success: '#10B981',
        info: '#3B82F6',
        warning: '#F59E0B',
        danger: '#E32D31'
    };

    // Enrollments Chart
    const enrollmentsCtx = document.getElementById('enrollmentsChart').getContext('2d');
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
                tension: 0.4
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
                        color: '#F1F5F9'
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

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
            datasets: [{
                label: 'Revenus (€)',
                data: {!! json_encode(array_column($monthlyRevenue, 'amount')) !!},
                backgroundColor: colors.success + '80',
                borderColor: colors.success,
                borderWidth: 2,
                borderRadius: 6
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
                        color: '#F1F5F9'
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

    // Course Levels Chart
    const courseLevelsCtx = document.getElementById('courseLevelsChart').getContext('2d');
    new Chart(courseLevelsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Débutant', 'Intermédiaire', 'Avancé'],
            datasets: [{
                data: [{{ $courseLevels['Débutant'] }}, {{ $courseLevels['Intermédiaire'] }}, {{ $courseLevels['Avancé'] }}],
                backgroundColor: [colors.info, colors.warning, colors.danger],
                borderWidth: 0,
                cutout: '60%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // User Roles Chart
    const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
    new Chart(userRolesCtx, {
        type: 'pie',
        data: {
            labels: ['Administrateurs', 'Formateurs', 'Apprenants'],
            datasets: [{
                data: [{{ $userRoles['Administrateurs'] }}, {{ $userRoles['Formateurs'] }}, {{ $userRoles['Apprenants'] }}],
                backgroundColor: [colors.danger, colors.primary, colors.success],
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
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
</script>
@endpush
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