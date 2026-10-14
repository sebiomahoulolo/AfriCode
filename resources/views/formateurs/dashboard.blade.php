@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Tableau de bord formateur')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bonjour ' . Auth::user()->first_name . ', gérez vos cours et suivez vos performances')

@section('header-actions')
@endsection

@section('content')
<style>
.formateur-whatsapp-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #25D366;
    color: white !important;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: opacity 0.2s, transform 0.2s;
}
.formateur-whatsapp-link:hover {
    color: white !important;
    opacity: 0.95;
    transform: translateY(-1px);
}
</style>
    <!-- Messages d'erreur -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Message de profil incomplet -->
    @if(isset($isProfileComplete) && !$isProfileComplete)
        <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert" style="border-left: 4px solid #FF8E2A; background: #FFF3E0; border-color: #FF8E2A;">
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-user-edit me-2"></i>Profil incomplet - Action requise
                    </h5>
                    <p class="mb-2"><strong>Veuillez compléter votre profil avant toute activité en tant que formateur.</strong></p>

                    <div class="mt-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                            <i class="fas fa-user-edit me-1"></i> Compléter mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Messages de validation des cours -->
    @if(isset($coursesWithMessages) && $coursesWithMessages->isNotEmpty())
        @foreach($coursesWithMessages as $course)
            @if($course->validation_status === 'pending_validation')
                <div class="alert alert-info alert-dismissible fade show mb-3" role="alert" style="border-left: 4px solid #1EA38B; background: #E3F2FD; border-color: #1EA38B;">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-clock fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-hourglass-half me-2"></i>Cours en attente de validation - {{ $course->title }}
                            </h5>
                            @if($course->submitted_for_validation_at)
                                <div style="background: white; padding: 0.75rem; border-radius: 8px; margin-top: 0.75rem; border: 1px solid #BBDEFB;">
                                    <div style="font-size: 0.9rem; color: #333;">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <strong>Soumis le :</strong> {{ $course->submitted_for_validation_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                            @endif
                            <div class="mt-3">
                                <p class="text-muted mb-2">Vous recevrez une notification une fois quelle est examiné votre cours.</p>
                                <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#0d7a63);border:none;">
                                    <i class="fas fa-eye me-1"></i> Voir le cours
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($course->validation_status === 'needs_revision' && $course->revision_notes)
                <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert" style="border-left: 4px solid #FF8E2A; background: #FFF3E0; border-color: #FF8E2A;">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-edit fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Corrections demandées - {{ $course->title }}
                            </h5>
                            <p class="mb-2"><strong>Demandé des corrections pour votre cours.</strong></p>
                            <div style="background: white; padding: 1rem; border-radius: 8px; margin-top: 1rem; border: 1px solid #FFE0B2;">
                                <div style="font-size: 0.9rem; color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $course->revision_notes }}</div>
                            </div>
                            <div class="mt-3">
                                <p class="text-muted mb-2">Après avoir apporté les corrections demandées, vous pourrez resoumettre votre cours pour validation.</p>
                                <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i> Apporter les corrections
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($course->validation_status === 'rejected')
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="border-left: 4px solid #E32D31; background: #FFEBEE; border-color: #E32D31;">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-ban me-2"></i>Cours rejeté - {{ $course->title }}
                            </h5>
                            @if($course->admin_notes)
                                <div style="background: white; padding: 1rem; border-radius: 8px; margin-top: 1rem; border: 1px solid #FFCDD2;">
                                    <div style="font-size: 0.9rem; color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $course->admin_notes }}</div>
                                </div>
                            @endif
                            <div class="mt-3">
                                @if($course->admin_notes)
                                    <p class="text-muted mb-2">Après avoir apporté les modifications demandées, vous pouvez resoumettre ce cours ou créer un nouveau cours.</p>
                                @else
                                    <p class="text-muted mb-2">Vous pouvez modifier et resoumettre ce cours, ou créer un nouveau cours.</p>
                                @endif
                                <div class="d-flex gap-2">
                                    <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-1"></i> Gérer ce cours
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

  <!-- Lien forum formateurs WhatsApp -->
  <div class="row mb-3" data-aos="fade-up" data-aos-delay="100">
    <div class="col-md-12">
        <a href="https://chat.whatsapp.com/KUjG6oZqqJWDNKtHsneGec" target="_blank" rel="noopener noreferrer" class="formateur-whatsapp-link" title="Rejoindre le forum des formateurs">
            <i class="fab fa-whatsapp"></i> Assitance
        </a>
    </div>
  </div>

  <!-- Actions rapides -->
  <div class="row mb-4" data-aos="fade-up" data-aos-delay="200">
    <div class="col-md-12">
        <div class="card-modern">
            <div class="card-body-modern">
                <h5 class="mb-3">
                    <i class="fas fa-bolt text-warning"></i> Actions rapides
                </h5>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('formateur.earnings.index') }}" class="btn w-100" style="color:#1EA38B;border:1px solid #1EA38B;">
                            <i class="fas fa-money-bill-wave me-2"></i>Mes revenus
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('formateur.courses.create') }}" class="btn w-100" style="color:#27B371;border:1px solid #27B371;">
                            <i class="fas fa-plus me-2"></i>Créer un cours
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('formateur.courses.index') }}" class="btn w-100" style="color:#1EA38B;border:1px solid #1EA38B;">
                            <i class="fas fa-list me-2"></i>Mes cours
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('formateur.earnings.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-money-bill-wave me-2"></i>Mes Revenus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Statistiques principales -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon" style="color:#1EA38B;">
                            <i class="fas fa-book-open fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0" style="color:#1EA38B;">{{ $courses->where('status', 'published')->count() }}</h3>
                            <small class="text-muted">Cours actifs</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar" style="width: {{ $courses->count() ? ($courses->where('status', 'published')->count() / $courses->count() * 100) : 0 }}%; background:#1EA38B !important;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon text-success">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0 text-success">{{ $totalEnrollments }}</h3>
                            <small class="text-muted">Étudiants inscrits</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar" style="width: 75%; background:#27B371 !important;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-modern" style="cursor: pointer;" onclick="window.location.href='{{ route('formateur.earnings.index') }}'">
                <div class="card-body-modern text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon text-warning">
                            <i class="fas fa-euro-sign fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0 text-warning">{{ number_format($totalRevenue, 0) }}</h3>
                            <small class="text-muted">Revenus (FCFA)</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: 60%"></div>
                    </div>
                    <div class="mt-2">
                        <small style="color:#1EA38B;">
                            <i class="fas fa-arrow-right"></i> Voir mes revenus
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon text-info">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0 text-info">{{ number_format($averageRating, 1) }}</h3>
                            <small class="text-muted">Note moyenne</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar" style="width: {{ ($averageRating / 5) * 100 }}%; background:#1EA38B !important;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Section principale -->
    <div class="row">
        <!-- Mes cours -->
        <div class="col-xl-8 mb-4">
            <div class="card-modern" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header-modern d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-book-open me-2" style="color:#1EA38B;"></i>
                        Mes cours
                    </h5>
                    @if($courses->count() > 3)
                        <a href="{{ route('formateur.courses.index') }}" class="btn btn-sm" style="color:#1EA38B;border:1px solid #1EA38B;">
                            Voir tout ({{ $courses->count() }})
                        </a>
                    @endif
                </div>
                <div class="card-body-modern">
                    @if(!$courses || $courses->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-book-open fa-4x text-muted opacity-50"></i>
                            </div>
                            <h5 class="text-muted mb-3">Aucun cours créé</h5>
                            <p class="text-muted mb-4">Commencez par créer votre premier cours pour partager vos connaissances.</p>
                            <a href="{{ route('formateur.courses.create') }}" class="btn-primary-africode">
                                <i class="fas fa-plus me-2"></i>Créer mon premier cours
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($courses->take(3) as $course)
                                <div class="col-md-4 mb-3">
                                    <div class="course-card-modern">
                                        <div class="course-image-modern">
                                            @if($course->cover_image_path)
                                                <img src="{{ asset($course->cover_image_path) }}" alt="{{ $course->title }}">
                                            @else
                                                <div class="course-placeholder">
                                                    <i class="fas fa-graduation-cap fa-3x"></i>
                                                </div>
                                            @endif
                                            <div class="course-status">
                                                @if($course->status === 'published')
                                                    <span class="badge" style="background:#27B371;">Publié</span>
                                                @else
                                                    <span class="badge" style="background:#FF8E2A;">Brouillon</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="course-content-modern">
                                            <h6 class="course-title-modern">{{ Str::limit($course->title, 35) }}</h6>
                                            <p class="course-description-modern">{{ Str::limit($course->short_description, 60) }}</p>
                                            <div class="course-stats-modern mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-users me-1"></i>{{ $course->students_count ?? 0 }} étudiants
                                                </small>
                                                <small class="text-muted ms-2">
                                                    <i class="fas fa-layer-group me-1"></i>{{ $course->modules->count() }} modules
                                                </small>
                                            </div>
                                            <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-sm w-100" style="color:#1EA38B;border:1px solid #1EA38B;">
                                                <i class="fas fa-cog me-1"></i>Gérer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar droite -->
        <div class="col-xl-4">
            <!-- Actions rapides -->
            <div class="card-modern mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body-modern">
                    <div class="d-grid gap-2">
                        <a href="{{ route('formateur.courses.create') }}" class="btn-primary-africode w-100">
                            <i class="fas fa-plus me-2"></i>Créer un cours
                        </a>
                        @if($courses->isNotEmpty())
                            <a href="{{ route('formateur.manage.course', ['courseId' => $courses->first()->id]) }}" class="btn-secondary-africode w-100">
                                <i class="fas fa-edit me-2"></i>Gérer le dernier cours
                            </a>
                        @endif
                        <a href="#" class="btn w-100" style="color:#1EA38B;border:1px solid #1EA38B;">
                            <i class="fas fa-chart-bar me-2"></i>Voir les analytics
                        </a>
                    </div>
                </div>
            </div>

            <!-- État des cours -->
            <div class="card-modern mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2" style="color:#1EA38B;"></i>
                        État de vos cours
                    </h6>
                </div>
                <div class="card-body-modern">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Cours publiés</span>
                        <div>
                            <span class="badge" style="background:#27B371;">{{ $courses->where('status', 'published')->count() }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">En brouillon</span>
                        <div>
                            <span class="badge" style="background:#FF8E2A;">{{ $courses->where('status', 'draft')->count() }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-bold">Total</span>
                        <div>
                            <span class="badge" style="background:#1EA38B;">{{ $courses->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <!-- Activité récente -->
            <div class="card-modern" data-aos="fade-up" data-aos-delay="400">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2 text-info"></i>
                        Activité récente
                    </h6>
                </div>
                <div class="card-body-modern">
                    @if($recentActivities->isEmpty())
                        <div class="text-center py-3">
                            <i class="fas fa-history fa-2x text-muted opacity-50 mb-2"></i>
                            <p class="text-muted mb-0">Aucune activité récente</p>
                        </div>
                    @else
                        <div class="activity-timeline">
                            @foreach($recentActivities->take(5) as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        @if($activity['type'] === 'enrollment')
                                            <i class="fas fa-user-plus text-success"></i>
                                        @elseif($activity['type'] === 'rating')
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="fas fa-info" style="color:#1EA38B;"></i>
                                        @endif
                                    </div>
                                    <div class="activity-content">
                                        <p class="mb-1">{{ $activity['message'] }}</p>
                                        @if(isset($activity['course']))
                                            <small style="color:#1EA38B;">"{{ Str::limit($activity['course']->title, 30) }}"</small>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div> --}}
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Cartes de cours modernes */
    .course-card-modern {
        border: 1px solid var(--africode-gray-medium);
        border-radius: var(--africode-border-radius);
        overflow: hidden;
        transition: var(--africode-transition);
        height: 100%;
    }

    .course-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: var(--africode-shadow-lg);
        border-color: var(--africode-primary);
    }

    .course-image-modern {
        height: 120px;
        position: relative;
        overflow: hidden;
    }

    .course-image-modern img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .course-placeholder {
        width: 100%;
        height: 100%;
        background: var(--africode-gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .course-status {
        position: absolute;
        top: 8px;
        right: 8px;
    }

    .course-content-modern {
        padding: 1rem;
    }

    .course-title-modern {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--africode-dark-text);
    }

    .course-description-modern {
        font-size: 0.85rem;
        color: var(--africode-gray-dark);
        margin-bottom: 0.75rem;
    }

    .course-stats-modern {
        font-size: 0.75rem;
    }

    /* Timeline d'activité */
    .activity-timeline {
        position: relative;
    }

    .activity-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--africode-gray-medium);
    }

    .activity-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .activity-icon {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--africode-gray-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
    }

    .activity-content {
        flex: 1;
        font-size: 0.85rem;
    }

    .activity-content p {
        margin-bottom: 0.25rem;
        color: var(--africode-dark-text);
    }

    /* Icônes de statistiques */
    .stat-icon {
        opacity: 0.8;
    }

    /* Responsive amélioré */
    @media (max-width: 1200px) {
        .course-card-modern {
            margin-bottom: 1.5rem;
        }

        .activity-item {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
        }
    }

    @media (max-width: 992px) {
        .course-image-modern {
            height: 100px;
        }

        .course-content-modern {
            padding: 0.75rem;
        }

        .course-title-modern {
            font-size: 0.9rem;
        }

        .course-description-modern {
            font-size: 0.8rem;
        }

        .activity-content {
            font-size: 0.8rem;
        }
    }

    @media (max-width: 768px) {
        .course-card-modern {
            margin-bottom: 1rem;
        }

        .course-image-modern {
            height: 80px;
        }

        .course-content-modern {
            padding: 0.5rem;
        }

        .course-title-modern {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .course-description-modern {
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .course-stats-modern {
            font-size: 0.7rem;
            margin-bottom: 0.5rem;
        }

        .activity-item {
            flex-direction: column;
            text-align: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
        }

        .activity-icon {
            margin: 0 auto 0.5rem;
        }

        .activity-content {
            text-align: center;
        }

        /* Statistiques responsive */
        .card-body-modern {
            padding: 1rem;
        }

        .stat-icon {
            font-size: 1.5rem !important;
        }

        .card-body-modern h3 {
            font-size: 1.5rem;
        }

        .card-body-modern small {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .course-image-modern {
            height: 60px;
        }

        .course-placeholder {
            font-size: 1.5rem;
        }

        .course-content-modern {
            padding: 0.375rem;
        }

        .course-title-modern {
            font-size: 0.8rem;
        }

        .course-description-modern {
            font-size: 0.7rem;
            margin-bottom: 0.375rem;
        }

        .course-stats-modern {
            font-size: 0.65rem;
            margin-bottom: 0.375rem;
        }

        .course-stats-modern small {
            display: block;
            margin-bottom: 0.125rem;
        }

        .activity-icon {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        .activity-content {
            font-size: 0.75rem;
        }

        .activity-content p {
            font-size: 0.75rem;
        }

        .activity-content small {
            font-size: 0.7rem;
        }

        /* Cartes de statistiques */
        .card-body-modern {
            padding: 0.75rem;
        }

        .stat-icon i {
            font-size: 1.25rem !important;
        }

        .card-body-modern h3 {
            font-size: 1.25rem;
        }

        .card-body-modern small {
            font-size: 0.7rem;
        }

        /* Boutons responsive */
        .btn-primary-africode,
        .btn-secondary-africode {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 0.375rem 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .course-card-modern:hover {
            transform: none;
        }

        .activity-item {
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .activity-icon {
            width: 24px;
            height: 24px;
        }

        .card-body-modern {
            padding: 0.5rem;
        }

        .card-header-modern {
            padding: 0.75rem 0.5rem;
        }

        .card-header-modern h5,
        .card-header-modern h6 {
            font-size: 0.9rem;
        }
    }
</style>
@endsection
