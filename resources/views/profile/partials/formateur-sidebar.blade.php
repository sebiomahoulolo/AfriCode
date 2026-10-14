{{-- Partiel pour la sidebar formateur avec statut et liens rapides --}}
@php
    if (!isset($user)) {
        $user = Auth::user();
    }
    
    // Calculer les statistiques réelles
    // Cours PUBLIÉS uniquement
    $publishedCourses = \App\Models\Course::where('formateur_id', $user->id)
        ->where('status', 'published')
        ->get();
    $publishedCoursesCount = $publishedCourses->count();
    
    // Tous les cours du formateur (pour calculer les étudiants)
    $allCourses = \App\Models\Course::where('formateur_id', $user->id)->pluck('id');
    
    // Nombre total d'apprenants distincts sur TOUS les cours
    $totalStudents = 0;
    if ($allCourses->isNotEmpty()) {
        $totalStudents = \App\Models\Enrollment::whereIn('course_id', $allCourses)
            ->select('user_id')
            ->distinct()
            ->count();
    }
    
    // Revenus totaux depuis les paiements APPROUVÉS (status = 'succeeded' ou 'paid')
    $totalEarnings = 0;
    
    // Méthode 1 : Depuis les paiements directs
    $earningsFromPayments = \App\Models\Payment::where('payable_type', \App\Models\Course::class)
        ->whereIn('payable_id', $allCourses)
        ->where('status', 'succeeded')
        ->sum('amount');
    
    // Méthode 2 : Depuis formateur_earnings (si disponible)
    $earningsFromFormateurEarnings = \App\Models\FormateurEarning::where('formateur_id', $user->id)
        ->where('payment_status', 'paid')
        ->sum('total_earnings');
    
    // Utiliser la méthode qui donne le meilleur résultat
    $totalEarnings = max($earningsFromPayments, $earningsFromFormateurEarnings);
    
    // Si aucune méthode ne fonctionne, utiliser la valeur stockée
    if ($totalEarnings == 0) {
        $totalEarnings = $user->total_earnings ?? 0;
    }
@endphp

<div class="col-lg-4 mb-4">
    <!-- Statut formateur -->
    <div class="card-modern" data-aos="fade-up" data-aos-delay="200">
        <div class="card-header-modern">
            <h5 class="mb-0">
                <i class="fas fa-chalkboard-teacher me-2" style="color:#1EA38B;"></i>
                Statut formateur
            </h5>
        </div>
        <div class="card-body-modern">
            <div class="mb-3">
                <strong>Statut:</strong>
                @php
                    $statusColors = [
                        'candidat' => 'warning',
                        'actif' => 'success',
                        'suspendu' => 'danger',
                        'inactif' => 'secondary',
                        'banni' => 'dark'
                    ];
                    $color = $statusColors[$user->formateur_status ?? 'candidat'] ?? 'secondary';
                @endphp
                @php
                    $statusStyleMap = [
                        'warning'   => 'background:#FF8E2A;color:#fff;',
                        'success'   => 'background:#27B371;',
                        'danger'    => 'background:#dc3545;',
                        'secondary' => 'background:#6c757d;',
                        'dark'      => 'background:#212529;',
                    ];
                    $statusStyle = $statusStyleMap[$color] ?? 'background:#6c757d;';
                @endphp
                <span class="badge ms-2" style="{{ $statusStyle }}">
                    {{ ucfirst($user->formateur_status ?? 'candidat') }}
                </span>
                @if(($user->formateur_status ?? 'candidat') === 'candidat')
                    <small class="d-block text-muted mt-1">
                        <i class="fas fa-info-circle"></i> En attente de validation
                    </small>
                @endif
            </div>
            @if($user->badge_level)
                <div class="mb-3">
                    <strong>Badge:</strong>
                    <span class="badge ms-2" style="background:#1EA38B;">
                        <i class="fas fa-medal"></i> {{ ucfirst($user->badge_level) }}
                    </span>
                </div>
            @endif
            <div class="mb-3">
                <strong>Onboarding:</strong>
                <span class="badge ms-2" style="{{ $user->onboarding_completed ? 'background:#27B371;' : 'background:#FF8E2A;color:#fff;' }}">
                    {{ $user->onboarding_completed ? 'Complété' : 'Non complété' }}
                </span>
                @if(!$user->onboarding_completed)
                    <small class="d-block text-muted mt-1">
                        <i class="fas fa-info-circle"></i> Complétez votre profil et créez votre premier cours
                    </small>
                @endif
            </div>
            <hr>
            <div class="mb-3">
                <strong>Cours publiés:</strong>
                <span class="ms-2">{{ $publishedCoursesCount }}</span>
            </div>
            <div class="mb-3">
                <strong>Étudiants:</strong>
                <span class="ms-2">{{ number_format($totalStudents) }}</span>
                <small class="d-block text-muted mt-1">Sur tous vos cours</small>
            </div>
            <div class="mb-0">
                <strong>Revenus totaux:</strong>
                <span class="ms-2 text-success">
                    <strong>{{ number_format($totalEarnings, 0, ',', ' ') }} FCFA</strong>
                </span>
                <small class="d-block text-muted mt-1">Ventes approuvées</small>
            </div>
        </div>
    </div>

    <!-- Liens rapides -->
    <div class="card-modern mt-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card-header-modern">
            <h5 class="mb-0">
                <i class="fas fa-link me-2" style="color:#1EA38B;"></i>
                Liens rapides
            </h5>
        </div>
        <div class="card-body-modern">
            <div class="d-grid gap-2">
                <a href="{{ route('formateur.earnings.index') }}" class="btn" style="border:1.5px solid #1EA38B;color:#1EA38B;">
                    <i class="fas fa-money-bill-wave me-2"></i>Mes revenus
                </a>
                <a href="{{ route('formateur.courses.index') }}" class="btn btn-outline-info">
                    <i class="fas fa-book me-2"></i>Mes cours
                </a>
                <a href="{{ route('formateur.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-chart-line me-2"></i>Tableau de bord
                </a>
            </div>
        </div>
    </div>
</div>

