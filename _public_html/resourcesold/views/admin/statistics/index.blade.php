@extends('admin.layouts.app')

@section('breadcrumb', 'Statistiques')

@section('content')
<div class="fade-in statistics-page">
    <!-- En-tête -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.25rem;">
                    <i class="fas fa-chart-line me-2" style="color: #11c06d;"></i>
                    Statistiques de la plateforme
                </h1>
                <p class="admin-card-subtitle mb-0">Analyse des performances et de l'évolution</p>
            </div>
        </div>
    </div>

    <!-- Cartes récapitulatives (4 petites cadres) -->
    <div class="stats-grid" data-aos="fade-up" data-aos-delay="50">
        <div class="stats-box">
            <div class="stats-box-icon" style="background: linear-gradient(135deg, #11c06d, #20c49c);">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stats-box-content">
                <div class="stats-box-number">{{ array_sum(array_column($monthlyEnrollments ?? [], 'count')) }}</div>
                <div class="stats-box-label">Inscriptions totales</div>
                <div class="stats-box-detail">+{{ !empty($monthlyEnrollments) ? ($monthlyEnrollments[count($monthlyEnrollments) - 1]['count'] ?? 0) : 0 }} ce mois</div>
            </div>
        </div>
        <div class="stats-box">
            <div class="stats-box-icon" style="background: linear-gradient(135deg, #11c06d, #239e79);">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stats-box-content">
                <div class="stats-box-number">{{ number_format(array_sum(array_column($monthlyRevenue ?? [], 'amount')), 0, ',', ' ') }} <span style="font-size: 0.7em; color: #64748b;">FCFA</span></div>
                <div class="stats-box-label">Revenu total (FCFA)</div>
                <div class="stats-box-detail">+{{ number_format(!empty($monthlyRevenue) ? ($monthlyRevenue[count($monthlyRevenue) - 1]['amount'] ?? 0) : 0, 0, ',', ' ') }} FCFA ce mois</div>
            </div>
        </div>
        <div class="stats-box">
            <div class="stats-box-icon" style="background: linear-gradient(135deg, #8B5CF6, #A78BFA);">
                <i class="fas fa-book"></i>
            </div>
            <div class="stats-box-content">
                <div class="stats-box-number">{{ ($courseLevels['Débutant'] ?? 0) + ($courseLevels['Intermédiaire'] ?? 0) + ($courseLevels['Avancé'] ?? 0) }}</div>
                <div class="stats-box-label">Cours publiés</div>
                <div class="stats-box-detail">{{ $courseLevels['Débutant'] ?? 0 }} déb. · {{ $courseLevels['Intermédiaire'] ?? 0 }} int. · {{ $courseLevels['Avancé'] ?? 0 }} av.</div>
            </div>
        </div>
        <div class="stats-box">
            <div class="stats-box-icon" style="background: linear-gradient(135deg, #F59E0B, #FBBF24);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-box-content">
                <div class="stats-box-number">{{ ($userRoles['Administrateurs'] ?? 0) + ($userRoles['Formateurs'] ?? 0) + ($userRoles['Apprenants'] ?? 0) }}</div>
                <div class="stats-box-label">Utilisateurs</div>
                <div class="stats-box-detail">{{ $userRoles['Administrateurs'] ?? 0 }} admin · {{ $userRoles['Formateurs'] ?? 0 }} form. · {{ $userRoles['Apprenants'] ?? 0 }} app.</div>
            </div>
        </div>
    </div>

    <!-- Section Comptabilité (FCFA) -->
    @php
        $comptabilite = $comptabilite ?? [];
        $formateursParVentes = $formateursParVentes ?? collect();
    @endphp
    <div class="admin-card" data-aos="fade-up" data-aos-delay="50" style="margin-bottom: 1.5rem;">
        <div class="admin-card-header">
            <h2 class="admin-card-title" style="font-size: 1.25rem; margin: 0;">
                <i class="fas fa-calculator me-2" style="color: #0d9488;"></i>
                Comptabilité plateforme
            </h2>
            <p class="mb-0 text-muted" style="font-size: 0.9rem;">Tous les montants en FCFA</p>
        </div>
        <div class="admin-card-body">
            <div class="comptabilite-grid">
                <div class="comptabilite-box">
                    <div class="comptabilite-icon" style="background: linear-gradient(135deg, #0d9488, #14b8a6);">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="comptabilite-content">
                        <div class="comptabilite-value">{{ number_format($comptabilite['montant_total_cours_publies'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="comptabilite-label">Montant total des cours publiés</div>
                        <div class="comptabilite-detail">Valeur catalogue (cours publiés)</div>
                    </div>
                </div>
                <div class="comptabilite-box">
                    <div class="comptabilite-icon" style="background: linear-gradient(135deg, #059669, #10b981);">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="comptabilite-content">
                        <div class="comptabilite-value">{{ number_format($comptabilite['montant_cours_vendus'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="comptabilite-label">Montant des cours vendus</div>
                        <div class="comptabilite-detail">Chiffre d'affaires total plateforme</div>
                    </div>
                </div>
                <div class="comptabilite-box">
                    <div class="comptabilite-icon" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
                        <i class="fas fa-money-bill-transfer"></i>
                    </div>
                    <div class="comptabilite-content">
                        <div class="comptabilite-value">{{ number_format($comptabilite['montant_retraits_total'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="comptabilite-label">Montant total des retraits effectués</div>
                        <div class="comptabilite-detail">Retraits formateurs (complétés)</div>
                    </div>
                </div>
                <div class="comptabilite-box">
                    <div class="comptabilite-icon" style="background: linear-gradient(135deg, #7c3aed, #8b5cf6);">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="comptabilite-content">
                        <div class="comptabilite-value">{{ number_format($comptabilite['revenus_africode'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="comptabilite-label">Revenus AfriCode (commission 23 %)</div>
                        <div class="comptabilite-detail">Part plateforme sur les ventes</div>
                    </div>
                </div>
                <div class="comptabilite-box">
                    <div class="comptabilite-icon" style="background: linear-gradient(135deg, #ea580c, #f97316);">
                        <i class="fas fa-users-line"></i>
                    </div>
                    <div class="comptabilite-content">
                        <div class="comptabilite-value">{{ number_format($comptabilite['total_personnes'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="comptabilite-label">Total personnes sur la plateforme</div>
                        <div class="comptabilite-detail">Recrutement + Apprenants + Formateurs + Newsletter</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau : Formateurs par ventes (au moins 2 ventes, du plus grand au plus petit) -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 1.5rem;">
        <div class="admin-card-header">
            <h3 class="admin-card-title" style="font-size: 1.1rem; margin: 0;">
                <i class="fas fa-chart-bar me-2" style="color: #0d9488;"></i>
                Classement des formateurs par ventes (FCFA)
            </h3>
            <span class="badge bg-teal" style="background: #0d9488;">{{ $formateursParVentes->count() }} formateurs</span>
        </div>
        <p class="mb-0 px-3 pt-0 pb-2 text-muted" style="font-size: 0.85rem;">Affichés uniquement les formateurs ayant vendu <strong>au moins 2 cours</strong> (au moins 2 paiements réussis).</p>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Formateur</th>
                            <th>Nombre de cours</th>
                            <th>Nb ventes</th>
                            <th>Inscriptions</th>
                            <th>Ventes totales (FCFA)</th>
                            <th>Revenus formateur 77 % (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($formateursParVentes as $index => $formateur)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <a href="{{ route('admin.formateurs.show', $formateur->id) }}" style="color: #0d9488; text-decoration: none;">{{ $formateur->first_name }} {{ $formateur->last_name }}</a>
                                </td>
                                <td>{{ $formateur->coursesInstructed->count() }}</td>
                                <td>{{ $formateur->nombre_ventes ?? 0 }}</td>
                                <td>{{ $formateur->total_enrollments ?? 0 }}</td>
                                <td><strong>{{ number_format($formateur->total_ventes_brut ?? 0, 0, ',', ' ') }}</strong></td>
                                <td>{{ number_format($formateur->total_revenue ?? 0, 0, ',', ' ') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Aucun formateur avec au moins 2 ventes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ligne 1 : 2 graphiques (Inscriptions + Revenus) -->
    <div class="charts-row charts-row-2">
        <div class="chart-frame" data-aos="fade-up" data-aos-delay="100">
            <div class="chart-frame-header">
                <h3 class="chart-frame-title"><i class="fas fa-user-plus me-2" style="color: #11c06d;"></i>Inscriptions mensuelles</h3>
            </div>
            <div class="chart-frame-body">
                <canvas id="enrollmentsChart"></canvas>
            </div>
        </div>
        <div class="chart-frame" data-aos="fade-up" data-aos-delay="150">
            <div class="chart-frame-header">
                <h3 class="chart-frame-title"><i class="fas fa-money-bill-wave me-2" style="color: #10B981;"></i>Revenus mensuels</h3>
            </div>
            <div class="chart-frame-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Ligne 2 : 3 graphiques (Catégories, Utilisateurs, Niveaux) -->
    <div class="charts-row charts-row-3">
        <div class="chart-frame" data-aos="fade-up" data-aos-delay="200">
            <div class="chart-frame-header">
                <h3 class="chart-frame-title"><i class="fas fa-folder me-2" style="color: #8B5CF6;"></i>Cours par catégorie</h3>
            </div>
            <div class="chart-frame-body chart-frame-body-sm">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <div class="chart-frame" data-aos="fade-up" data-aos-delay="250">
            <div class="chart-frame-header">
                <h3 class="chart-frame-title"><i class="fas fa-users me-2" style="color: #F59E0B;"></i>Utilisateurs par rôle</h3>
            </div>
            <div class="chart-frame-body chart-frame-body-sm">
                <canvas id="userRolesChart"></canvas>
            </div>
        </div>
        <div class="chart-frame" data-aos="fade-up" data-aos-delay="300">
            <div class="chart-frame-header">
                <h3 class="chart-frame-title"><i class="fas fa-signal me-2" style="color: #EC4899;"></i>Niveaux des cours</h3>
            </div>
            <div class="chart-frame-body chart-frame-body-sm">
                <canvas id="courseLevelsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tableaux : Cours populaires + Formateurs actifs -->
    <div class="tables-row">
        <div class="table-frame" data-aos="fade-up" data-aos-delay="350">
            <div class="table-frame-header">
                <h3 class="table-frame-title"><span class="table-frame-count" style="background: #11c06d; color: white; padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.85rem; margin-right: 0.5rem;">{{ $topCourses->count() }}</span><i class="fas fa-trophy me-2" style="color: #11c06d;"></i>Cours les plus populaires</h3>
            </div>
            <div class="table-frame-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Cours</th>
                            <th>Formateur</th>
                            <th>Catégorie</th>
                            <th>Inscr.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCourses as $course)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.courses.show', $course->id) }}" style="color: #11c06d; text-decoration: none;">{{ Str::limit($course->title, 35) }}</a>
                                </td>
                                <td>{{ $course->formateur->first_name ?? 'N/A' }} {{ $course->formateur->last_name ?? '' }}</td>
                                <td>{{ $course->category->name ?? '—' }}</td>
                                <td><strong>{{ $course->enrollments_count }}</strong></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Aucun cours</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-frame" data-aos="fade-up" data-aos-delay="400">
            <div class="table-frame-header">
                <h3 class="table-frame-title"><span class="table-frame-count" style="background: #fea837; color: white; padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.85rem; margin-right: 0.5rem;">{{ $topFormateurs->count() }}</span><i class="fas fa-chalkboard-teacher me-2" style="color: #fea837;"></i>Formateurs les plus actifs</h3>
            </div>
            <div class="table-frame-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Formateur</th>
                            <th>Cours</th>
                            <th>Inscr.</th>
                            <th>Revenus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topFormateurs as $formateur)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.formateurs.show', $formateur->id) }}" style="color: #11c06d; text-decoration: none;">{{ $formateur->first_name }} {{ $formateur->last_name }}</a>
                                </td>
                                <td>{{ $formateur->coursesInstructed->count() }}</td>
                                <td><strong>{{ $formateur->total_enrollments ?? 0 }}</strong></td>
                                <td>{{ number_format($formateur->total_revenue ?? 0, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Aucun formateur</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.statistics-page { max-width: 1400px; margin: 0 auto; }

/* Grille des 4 cartes récap */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}
.stats-box {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #E9ECEF;
    padding: 1.25rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    transition: transform 0.2s, box-shadow 0.2s;
}
.stats-box:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(0,0,0,0.08); }
.stats-box-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.25rem;
}
.stats-box-content { flex: 1; min-width: 0; }
.stats-box-number { font-size: 1.5rem; font-weight: 700; color: #2C3E50; line-height: 1.2; }
.stats-box-label { font-size: 0.85rem; color: #6C757D; margin-top: 0.25rem; }
.stats-box-detail { font-size: 0.75rem; color: #95A5A6; margin-top: 0.35rem; }

/* Lignes de graphiques */
.charts-row { display: grid; gap: 1.25rem; margin-bottom: 1.5rem; }
.charts-row-2 { grid-template-columns: repeat(2, 1fr); }
.charts-row-3 { grid-template-columns: repeat(3, 1fr); }

.chart-frame {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #E9ECEF;
    overflow: hidden;
    transition: box-shadow 0.2s;
}
.chart-frame:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.08); }
.chart-frame-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #E9ECEF;
    background: #FAFBFC;
}
.chart-frame-title {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #2C3E50;
}
.chart-frame-body {
    position: relative;
    height: 220px;
    padding: 1rem;
}
.chart-frame-body-sm { height: 200px; }

/* Tableaux côte à côte */
.tables-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
.table-frame {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #E9ECEF;
    overflow: hidden;
}
.table-frame-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #E9ECEF;
    background: #FAFBFC;
}
.table-frame-title { margin: 0; font-size: 1rem; font-weight: 600; color: #2C3E50; }
.table-frame-body { overflow-x: auto; }
.table-frame-body .admin-table { margin: 0; }
.table-frame-body .admin-table th,
.table-frame-body .admin-table td { padding: 0.65rem 1rem; font-size: 0.9rem; }

/* Grille Comptabilité */
.comptabilite-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
}
.comptabilite-box {
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    padding: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    transition: box-shadow 0.2s, transform 0.2s;
}
.comptabilite-box:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    transform: translateY(-1px);
}
.comptabilite-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.1rem;
}
.comptabilite-content { flex: 1; min-width: 0; }
.comptabilite-value { font-size: 1.25rem; font-weight: 700; color: #0f172a; line-height: 1.2; }
.comptabilite-label { font-size: 0.8rem; color: #475569; margin-top: 0.2rem; font-weight: 500; }
.comptabilite-detail { font-size: 0.7rem; color: #94a3b8; margin-top: 0.25rem; }
@media (max-width: 1200px) {
    .comptabilite-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .comptabilite-grid { grid-template-columns: 1fr; }
}

@media (max-width: 1200px) {
    .charts-row-3 { grid-template-columns: repeat(2, 1fr); }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
    .charts-row-2,
    .charts-row-3 { grid-template-columns: 1fr; }
    .tables-row { grid-template-columns: 1fr; }
    .chart-frame-body { height: 240px; }
    .chart-frame-body-sm { height: 220px; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const months = @json(array_column($monthlyEnrollments ?? [], 'month'));
    const enrollmentCounts = @json(array_column($monthlyEnrollments ?? [], 'count'));
    const revenueAmounts = @json(array_column($monthlyRevenue ?? [], 'amount'));

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { color: '#6B7280', maxRotation: 45 } },
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: '#6B7280' } }
        }
    };

    // Inscriptions mensuelles
    const enrollmentsCtx = document.getElementById('enrollmentsChart');
    if (enrollmentsCtx) {
        new Chart(enrollmentsCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Inscriptions',
                    data: enrollmentCounts,
                    borderColor: '#11c06d',
                    backgroundColor: 'rgba(59, 130, 246, 0.08)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    tooltip: { callbacks: { label: function(ctx) { return ctx.parsed.y + ' inscriptions'; } } }
                }
            }
        });
    }

    // Revenus mensuels
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenus',
                    data: revenueAmounts,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    ...chartOptions.scales,
                    y: {
                        ...chartOptions.scales.y,
                        ticks: {
                            ...chartOptions.scales.y.ticks,
                            callback: function(v) {
                                if (v >= 1e6) return (v/1e6).toFixed(1) + 'M';
                                if (v >= 1e3) return (v/1e3).toFixed(0) + 'k';
                                return v;
                            }
                        }
                    }
                },
                plugins: {
                    ...chartOptions.plugins,
                    tooltip: { callbacks: { label: function(ctx) { return new Intl.NumberFormat('fr-FR').format(ctx.parsed.y) + ' FCFA'; } } }
                }
            }
        });
    }

    // Catégories
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($categoriesStats->pluck('name')),
                datasets: [{
                    data: @json($categoriesStats->pluck('courses_count')),
                    backgroundColor: ['#4F46E5', '#10B981', '#11c06d', '#F59E0B', '#EC4899', '#8B5CF6', '#06B6D4', '#EF4444', '#F97316'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 }, padding: 8 } } },
                cutout: '60%'
            }
        });
    }

    // Rôles utilisateurs
    const userRolesCtx = document.getElementById('userRolesChart');
    if (userRolesCtx) {
        new Chart(userRolesCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Administrateurs', 'Formateurs', 'Apprenants'],
                datasets: [{
                    data: [{{ $userRoles['Administrateurs'] ?? 0 }}, {{ $userRoles['Formateurs'] ?? 0 }}, {{ $userRoles['Apprenants'] ?? 0 }}],
                    backgroundColor: ['#4F46E5', '#10B981', '#11c06d'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 }, padding: 8 } } },
                cutout: '60%'
            }
        });
    }

    // Niveaux cours
    const courseLevelsCtx = document.getElementById('courseLevelsChart');
    if (courseLevelsCtx) {
        new Chart(courseLevelsCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Débutant', 'Intermédiaire', 'Avancé'],
                datasets: [{
                    data: [{{ $courseLevels['Débutant'] ?? 0 }}, {{ $courseLevels['Intermédiaire'] ?? 0 }}, {{ $courseLevels['Avancé'] ?? 0 }}],
                    backgroundColor: ['#11c06d', '#8B5CF6', '#EC4899'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 }, padding: 8 } } },
                cutout: '60%'
            }
        });
    }
});
</script>
@endpush
