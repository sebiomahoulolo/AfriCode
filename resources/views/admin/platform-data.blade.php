@extends('admin.layouts.app')

@section('breadcrumb', 'Données de la plateforme')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page avec design moderne -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="admin-card gradient-header">
                <div class="admin-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="admin-card-title mb-2">
                                <i class="fas fa-chart-line me-3 text-primary"></i>Données de la plateforme
                            </h1>
                            <p class="admin-card-subtitle mb-0">Aperçu complet des performances et statistiques de votre plateforme d'apprentissage</p>
                        </div>
                        <div class="action-buttons d-none d-md-flex">
                            <button class="btn btn-outline-primary me-2">
                                <i class="fas fa-download me-2"></i>Exporter
                            </button>
                            <button class="btn btn-primary">
                                <i class="fas fa-sync-alt me-2"></i>Actualiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques modernes -->
    <div class="row mb-5 g-4">
        <!-- Total des utilisateurs -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stat-card stat-card-primary" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label">Total Utilisateurs</div>
                    <div class="stat-number">{{ number_format(array_sum($userRoles)) }}</div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+12% ce mois</span>
                    </div>
                </div>
                <div class="stat-chart">
                    <canvas id="usersChart" width="120" height="50"></canvas>
                </div>
            </div>
        </div>

        <!-- Total des cours -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stat-card stat-card-success" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label">Cours Disponibles</div>
                    <div class="stat-number">{{ number_format($topCourses->count()) }}</div>
                    <div class="stat-trend positive">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ $topCourses->count() }} actifs</span>
                    </div>
                </div>
                <div class="stat-chart">
                    <canvas id="coursesChart" width="120" height="50"></canvas>
                </div>
            </div>
        </div>

        <!-- Total des inscriptions -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stat-card stat-card-info" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label">Inscriptions Totales</div>
                    <div class="stat-number">{{ number_format($monthlyEnrollments->sum('count')) }}</div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+{{ $monthlyEnrollments->last()['count'] ?? 0 }} récentes</span>
                    </div>
                </div>
                <div class="stat-chart">
                    <canvas id="enrollmentsChart" width="120" height="50"></canvas>
                </div>
            </div>
        </div>

        <!-- Revenus totaux -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stat-card stat-card-warning" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-label">Revenus (XOF)</div>
                    <div class="stat-number">{{ number_format($monthlyRevenue->sum('amount'), 0, ',', ' ') }}</div>
                    <div class="stat-trend positive">
                        <i class="fas fa-trend-up"></i>
                        <span>+8.5% ce mois</span>
                    </div>
                </div>
                <div class="stat-chart">
                    <canvas id="revenueChart" width="120" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques principaux -->
    <div class="row mb-5 g-4">
        <!-- Graphique des inscriptions mensuelles -->
        <div class="col-lg-8 mb-4">
            <div class="chart-card" data-aos="fade-up" data-aos-delay="100">
                <div class="chart-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-2 mb-md-0">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-area me-2 text-primary"></i>Évolution des inscriptions
                            </h5>
                            <p class="chart-subtitle">Tendance des inscriptions sur les 6 derniers mois</p>
                        </div>
                        <div class="chart-controls">
                            <button class="btn btn-sm btn-outline-primary active">6 mois</button>
                            <button class="btn btn-sm btn-outline-primary">1 an</button>
                        </div>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="mainEnrollmentsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Répartition des utilisateurs -->
        <div class="col-lg-4 mb-4">
            <div class="chart-card" data-aos="fade-up" data-aos-delay="200">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-users-cog me-2 text-success"></i>Répartition des utilisateurs
                    </h5>
                    <p class="chart-subtitle">Distribution par type d'utilisateur</p>
                </div>
                <div class="chart-body d-flex align-items-center justify-content-center">
                    <canvas id="userDistributionChart"></canvas>
                </div>
                <div class="chart-legend">
                    @foreach($userRoles as $role => $count)
                    <div class="legend-item">
                        <span class="legend-color legend-{{ $role === 'Apprenants' ? 'primary' : ($role === 'Formateurs' ? 'secondary' : 'warning') }}"></span>
                        <span class="legend-label">{{ $role }}</span>
                        <span class="legend-value">{{ number_format($count) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques secondaires -->
    <div class="row mb-5 g-4">
        <!-- Revenus mensuels -->
        <div class="col-lg-6 mb-4">
            <div class="chart-card" data-aos="fade-up" data-aos-delay="300">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-bar me-2 text-warning"></i>Revenus mensuels
                    </h5>
                    <p class="chart-subtitle">Évolution des revenus par mois (XOF)</p>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Catégories populaires -->
        <div class="col-lg-6 mb-4">
            <div class="chart-card" data-aos="fade-up" data-aos-delay="400">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-tags me-2 text-info"></i>Catégories populaires
                    </h5>
                    <p class="chart-subtitle">Nombre de cours par catégorie</p>
                </div>
                <div class="chart-body">
                    <canvas id="categoriesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableaux des données -->
    <div class="row g-4">
        <!-- Top cours -->
        @if($topCourses && $topCourses->count() > 0)
        <div class="col-lg-6 mb-4">
            <div class="data-card" data-aos="fade-up" data-aos-delay="100">
                <div class="data-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="data-title">
                            <i class="fas fa-trophy me-2 text-warning"></i>Cours les plus populaires
                        </h5>
                        <span class="data-badge">Top {{ $topCourses->count() }}</span>
                    </div>
                </div>
                <div class="data-body">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Cours</th>
                                    <th>Formateur</th>
                                    <th class="text-center">Inscriptions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCourses as $index => $course)
                                <tr>
                                    <td>
                                        <div class="course-info">
                                            <div class="course-rank">#{{ $index + 1 }}</div>
                                            <div class="course-details">
                                                <a href="{{ route('admin.courses.show', $course) }}" class="course-title">
                                                    {{ Str::limit($course->title, 25) }}
                                                </a>
                                                <div class="course-meta">
                                                    <small class="text-muted">{{ $course->category->name ?? 'Non catégorisé' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="instructor-info">
                                            <small class="text-muted">
                                                {{ $course->formateur->first_name ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="metric-badge bg-primary">
                                            {{ number_format($course->enrollments_count ?? 0) }}
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

        <!-- Top formateurs -->
        @if($topFormateurs && $topFormateurs->count() > 0)
        <div class="col-lg-6 mb-4">
            <div class="data-card" data-aos="fade-up" data-aos-delay="200">
                <div class="data-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="data-title">
                            <i class="fas fa-chalkboard-teacher me-2 text-success"></i>Formateurs les plus actifs
                        </h5>
                        <span class="data-badge">Top {{ $topFormateurs->count() }}</span>
                    </div>
                </div>
                <div class="data-body">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Formateur</th>
                                    <th class="text-center">Cours</th>
                                    <th class="text-center">Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topFormateurs as $index => $formateur)
                                <tr>
                                    <td>
                                        <div class="instructor-info">
                                            <div class="instructor-rank">#{{ $index + 1 }}</div>
                                            <div class="instructor-details">
                                                <a href="{{ route('admin.users.show', $formateur) }}" class="instructor-name">
                                                    {{ $formateur->first_name }} {{ $formateur->last_name }}
                                                </a>
                                                <div class="instructor-meta">
                                                    <small class="text-muted">{{ Str::limit($formateur->email, 25) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="course-count">
                                            {{ number_format($formateur->courses_instructed_count ?? 0) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="metric-badge bg-success">
                                            {{ number_format($formateur->total_enrollments ?? 0) }}
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
</div>

<style>
/* Variables CSS modernes */
:root {
    --primary: #1ea38b;
    --primary-light: #2dd4bf;
    --primary-dark: #0f766e;
    --secondary: #10b981;
    --accent: #f59e0b;
    --info: #06b6d4;
    --warning: #f59e0b;
    --danger: #ef4444;
    --success: #10b981;
    --dark: #1f2937;
    --dark-light: #374151;
    --light: #f8fafc;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --white: #ffffff;
    --border-radius: 16px;
    --border-radius-sm: 8px;
    --border-radius-lg: 24px;
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --font-inter: 'Inter', 'Segoe UI', sans-serif;
}

/* Global improvements */
* {
    box-sizing: border-box;
}

body {
    font-family: var(--font-inter);
    line-height: 1.6;
    color: var(--gray-600);
    background: var(--gray-50);
}

/* Container improvements */
.container-fluid {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    max-width: 1440px;
    margin: 0 auto;
}

/* Header moderne avec glassmorphism */
.gradient-header {
    background: linear-gradient(135deg, 
        rgba(30, 163, 139, 0.08) 0%, 
        rgba(16, 185, 129, 0.12) 50%, 
        rgba(6, 182, 212, 0.08) 100%);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
}

.gradient-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.5;
    pointer-events: none;
}

.admin-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.admin-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
}

.admin-card-body {
    padding: 2rem;
    position: relative;
    z-index: 1;
}

.admin-card-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
    background: linear-gradient(135deg, var(--dark), var(--dark-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.admin-card-subtitle {
    color: var(--gray-500);
    font-size: 1rem;
    margin: 0.5rem 0 0;
    font-weight: 400;
}

.action-buttons .btn {
    border-radius: var(--border-radius-sm);
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    transition: var(--transition);
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}

.action-buttons .btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s;
}

.action-buttons .btn:hover::before {
    left: 100%;
}

.action-buttons .btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-color: var(--primary);
    color: white;
}

.action-buttons .btn-outline-primary {
    border-color: var(--primary);
    color: var(--primary);
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

/* Cartes statistiques ultra-modernes */
.stat-card {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    position: relative;
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
    min-height: 200px;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
}

.stat-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(30, 163, 139, 0.05) 0%, transparent 70%);
    transform: scale(0);
    transition: transform 0.5s ease;
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary);
}

.stat-card:hover::after {
    transform: scale(1);
}

.stat-card-primary::before { background: linear-gradient(90deg, var(--primary), var(--primary-light)); }
.stat-card-success::before { background: linear-gradient(90deg, var(--secondary), #34d399); }
.stat-card-info::before { background: linear-gradient(90deg, var(--info), #38bdf8); }
.stat-card-warning::before { background: linear-gradient(90deg, var(--warning), #fbbf24); }

.stat-card-icon {
    width: 72px;
    height: 72px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    margin-bottom: 1.5rem;
    position: relative;
    box-shadow: var(--shadow);
}

.stat-card-icon::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: linear-gradient(45deg, rgba(255,255,255,0.3), transparent, rgba(255,255,255,0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover .stat-card-icon::before {
    opacity: 1;
}

.stat-card-primary .stat-card-icon { 
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
}
.stat-card-success .stat-card-icon { 
    background: linear-gradient(135deg, var(--secondary), #047857);
}
.stat-card-info .stat-card-icon { 
    background: linear-gradient(135deg, var(--info), #0891b2);
}
.stat-card-warning .stat-card-icon { 
    background: linear-gradient(135deg, var(--warning), #d97706);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray-500);
    font-weight: 600;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 1rem;
    line-height: 1;
    background: linear-gradient(135deg, var(--dark), var(--primary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-trend {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius-lg);
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
    color: var(--secondary);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.stat-trend.positive {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
    color: var(--secondary);
    border-color: rgba(16, 185, 129, 0.2);
}

.stat-trend.negative {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
    color: var(--danger);
    border-color: rgba(239, 68, 68, 0.2);
}

.stat-chart {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 120px;
    height: 50px;
    opacity: 0.15;
    z-index: 0;
}

/* Cartes de graphiques modernisées */
.chart-card, .data-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
    position: relative;
}

.chart-card::before, .data-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--info));
}

.chart-card:hover, .data-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary);
}

.chart-header, .data-header {
    padding: 2rem 2rem 1rem;
    border-bottom: 1px solid var(--gray-200);
    background: linear-gradient(135deg, var(--gray-50), var(--white));
}

.chart-title, .data-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.chart-subtitle {
    font-size: 0.875rem;
    color: var(--gray-500);
    margin: 0.5rem 0 0;
    font-weight: 400;
}

.data-badge {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius-lg);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: var(--shadow-sm);
}

.chart-controls .btn {
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    margin-left: 0.5rem;
    border-radius: var(--border-radius-sm);
    transition: var(--transition);
    border: 1px solid var(--gray-300);
}

.chart-controls .btn.active {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-color: var(--primary);
    box-shadow: var(--shadow-sm);
}

.chart-body, .data-body {
    padding: 2rem;
    height: 400px;
    position: relative;
}

.chart-body canvas {
    width: 100% !important;
    height: 100% !important;
}

/* Légende améliorée */
.chart-legend {
    padding: 1.5rem 2rem;
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
}

.legend-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-200);
    transition: var(--transition);
}

.legend-item:last-child {
    border-bottom: none;
}

.legend-item:hover {
    background: rgba(30, 163, 139, 0.05);
    border-radius: var(--border-radius-sm);
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    margin-right: 1rem;
    box-shadow: var(--shadow-sm);
}

.legend-primary { background: var(--primary) !important; }
.legend-secondary { background: var(--secondary) !important; }
.legend-warning { background: var(--warning) !important; }

.legend-label {
    flex: 1;
    font-size: 0.875rem;
    color: var(--dark);
    font-weight: 500;
}

.legend-value {
    font-weight: 700;
    color: var(--dark);
    font-size: 0.875rem;
}

/* Tableaux ultra-modernes */
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.modern-table th {
    background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
    padding: 1.25rem;
    text-align: left;
    font-weight: 700;
    font-size: 0.875rem;
    color: var(--dark);
    border-bottom: 2px solid var(--gray-200);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.modern-table td {
    padding: 1.25rem;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
    background: var(--white);
    transition: var(--transition);
}

.modern-table tbody tr:hover td {
    background: linear-gradient(135deg, rgba(30, 163, 139, 0.05), rgba(30, 163, 139, 0.02));
    transform: scale(1.01);
}

/* Éléments de tableau améliorés */
.course-info, .instructor-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.course-rank, .instructor-rank {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 700;
    box-shadow: var(--shadow-sm);
}

.course-title, .instructor-name {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.course-title:hover, .instructor-name:hover {
    color: var(--primary-dark);
    text-decoration: underline;
    transform: translateX(4px);
}

.course-meta, .instructor-meta {
    margin-top: 0.25rem;
}

.metric-badge {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius-lg);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: var(--shadow-sm);
}

.metric-badge.bg-primary { 
    background: linear-gradient(135deg, var(--primary), var(--primary-light)) !important; 
}
.metric-badge.bg-success { 
    background: linear-gradient(135deg, var(--secondary), #34d399) !important; 
}

.course-count {
    font-weight: 700;
    color: var(--dark);
    font-size: 1rem;
}

/* Animations et micro-interactions */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

[data-aos="fade-up"] {
    animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.stat-card-icon {
    animation: pulse 2s infinite;
}

.stat-card:hover .stat-card-icon {
    animation: none;
    transform: scale(1.1) rotate(5deg);
}

/* Design responsif optimisé */
@media (max-width: 1400px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .chart-body, .data-body {
        height: 350px;
        padding: 1.5rem;
    }
}

@media (max-width: 1200px) {
    .stat-number {
        font-size: 1.75rem;
    }
    
    .chart-body, .data-body {
        height: 320px;
    }
    
    .stat-card {
        min-height: 180px;
        padding: 1.5rem;
    }
}

@media (max-width: 992px) {
    .col-xl-3.col-lg-6.col-md-6.col-sm-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0.5rem;
    }
    
    .admin-card-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .admin-card-body {
        padding: 1.5rem;
    }
    
    .action-buttons {
        display: none !important;
    }
    
    .stat-card {
        padding: 1.25rem;
        min-height: 160px;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .chart-body, .data-body {
        padding: 1rem;
        height: 280px;
    }
    
    .chart-header, .data-header {
        padding: 1.5rem 1rem 0.5rem;
    }
    
    .modern-table th,
    .modern-table td {
        padding: 1rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .col-xl-3.col-lg-6.col-md-6.col-sm-6 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        padding: 0.25rem;
    }
}

@media (max-width: 576px) {
    .stat-card-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .stat-number {
        font-size: 1.25rem;
    }
    
    .chart-controls {
        display: none;
    }
    
    .chart-body, .data-body {
        height: 250px;
        padding: 0.75rem;
    }
    
    .admin-card-title {
        font-size: 1.25rem;
    }
}

/* Améliorations pour écrans larges */
@media (min-width: 1600px) {
    .container-fluid {
        max-width: 1536px;
    }
    
    .stat-number {
        font-size: 3rem;
    }
    
    .chart-body, .data-body {
        height: 450px;
    }
    
    .stat-card {
        min-height: 240px;
    }
}

/* Optimisations de performance */
.stat-card,
.chart-card,
.data-card {
    will-change: transform;
    backface-visibility: hidden;
}

/* Accessibilité améliorée */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Mode sombre (optionnel) */
@media (prefers-color-scheme: dark) {
    :root {
        --white: #1f2937;
        --gray-50: #111827;
        --gray-100: #1f2937;
        --gray-200: #374151;
        --dark: #f9fafb;
        --gray-500: #9ca3af;
    }
}
</style>
@endsection

@push('scripts')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 600,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50
    });

    // Color palette
    const colors = {
        primary: '#1EA38B',
        secondary: '#10B981',
        info: '#06B6D4',
        warning: '#F59E0B',
        success: '#10B981',
        dark: '#1F2937',
        light: '#F9FAFB'
    };

    // Chart options par défaut
    const defaultOptions = {
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
                    color: '#F3F4F6',
                    drawBorder: false
                },
                ticks: {
                    color: '#6B7280',
                    font: {
                        size: 11
                    }
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#6B7280',
                    font: {
                        size: 11
                    }
                }
            }
        }
    };

    // Fonction pour créer des gradients
    function createGradient(ctx, color1, color2) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    }

    // Mini graphiques des cartes de statistiques
    function createMiniChart(canvasId, data, color) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: data,
                    borderColor: color,
                    backgroundColor: color + '20',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                elements: {
                    line: { tension: 0.4 },
                    point: { radius: 0 }
                }
            }
        });
    }

    // Créer les mini graphiques
    createMiniChart('usersChart', [45, 52, 48, 67, 73, 89], colors.primary);
    createMiniChart('coursesChart', [12, 15, 18, 22, 25, 28], colors.secondary);
    createMiniChart('enrollmentsChart', [145, 162, 158, 189, 203, 234], colors.info);
    createMiniChart('revenueChart', [125, 156, 143, 189, 234, 267], colors.warning);

    // Graphique principal des inscriptions
    const enrollmentsCtx = document.getElementById('mainEnrollmentsChart');
    if (enrollmentsCtx) {
        const enrollmentsGradient = createGradient(enrollmentsCtx.getContext('2d'), colors.primary + '60', colors.primary + '10');
        
        new Chart(enrollmentsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyEnrollments->pluck('month')) !!},
                datasets: [{
                    label: 'Inscriptions',
                    data: {!! json_encode($monthlyEnrollments->pluck('count')) !!},
                    backgroundColor: enrollmentsGradient,
                    borderColor: colors.primary,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    ...defaultOptions.plugins,
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        cornerRadius: 8,
                        displayColors: false
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
                labels: ['Apprenants', 'Formateurs', 'Administrateurs'],
                datasets: [{
                    data: [
                        {{ $userRoles['Apprenants'] ?? 0 }},
                        {{ $userRoles['Formateurs'] ?? 0 }},
                        {{ $userRoles['Administrateurs'] ?? 0 }}
                    ],
                    backgroundColor: [colors.primary, colors.secondary, colors.warning],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        cornerRadius: 8
                    }
                }
            }
        });
    }

    // Graphique des revenus mensuels
    const revenueCtx = document.getElementById('monthlyRevenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
                datasets: [{
                    label: 'Revenus (XOF)',
                    data: {!! json_encode($monthlyRevenue->pluck('amount')) !!},
                    backgroundColor: colors.warning + '80',
                    borderColor: colors.warning,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    ...defaultOptions.plugins,
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Revenus: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' XOF';
                            }
                        }
                    }
                }
            }
        });
    }

    // Graphique des catégories
    const categoriesCtx = document.getElementById('categoriesChart');
    if (categoriesCtx) {
        new Chart(categoriesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($categoriesStats->pluck('name')) !!},
                datasets: [{
                    label: 'Nombre de cours',
                    data: {!! json_encode($categoriesStats->pluck('courses_count')) !!},
                    backgroundColor: colors.info + '80',
                    borderColor: colors.info,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                ...defaultOptions,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: '#F3F4F6'
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    }
                },
                plugins: {
                    ...defaultOptions.plugins,
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        cornerRadius: 8,
                        displayColors: false
                    }
                }
            }
        });
    }

    // Interactions pour les boutons de contrôle des graphiques
    document.querySelectorAll('.chart-controls .btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.chart-controls').querySelectorAll('.btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endpush
