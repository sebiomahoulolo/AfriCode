<div class="spa-container px-2 py-2">
    {{-- Navigation Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
        <ol class="breadcrumb-modern">
            <li class="breadcrumb-item">
                <button wire:click="showDashboard" class="btn-link {{ $currentView === 'dashboard' ? 'active' : '' }} focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Retour au tableau de bord">
                    <i class="fas fa-home me-1"></i>Tableau de bord
                </button>
            </li>
            @if($currentView === 'lesson')
                <li class="breadcrumb-item">
                    <span class="text-muted">Leçon en cours</span>
                </li>
            @elseif($currentView === 'profile')
                <li class="breadcrumb-item">
                    <span class="text-muted">Mon Profil</span>
                </li>
            @elseif($currentView === 'certifications')
                <li class="breadcrumb-item">
                    <span class="text-muted">Mes Certifications</span>
                </li>
            @endif
        </ol>
    </nav>

    {{-- Vue Dashboard --}}
    @if($currentView === 'dashboard')
        <div class="dashboard-view" data-aos="fade-up">
            {{-- Hero Section --}}
            <div class="welcome-hero mb-4">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Bonjour {{ $user->first_name }} ! 👋
                    </h1>
                    <p class="hero-subtitle">
                        Continuez votre parcours d'apprentissage et atteignez vos objectifs.
                    </p>
                </div>
                <div class="hero-stats">
                    <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="stat-value">{{ $enrollments->count() }}</div>
                        <div class="stat-label">Cours suivis</div>
                    </div>
                    <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="stat-value">{{ $completedCourses }}</div>
                        <div class="stat-label">Cours terminés</div>
                    </div>
                    <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                        <div class="stat-value">{{ round($avgProgress) }}%</div>
                        <div class="stat-label">Progression</div>
                    </div>
                    <div class="stat-item" data-aos="fade-up" data-aos-delay="400">
                        <div class="stat-value">{{ $certifications->count() }}</div>
                        <div class="stat-label">Certifications</div>
                    </div>
                </div>
            </div>

            @if(Auth::user())
                <div class="my-6 p-4 bg-green-50 border-l-4 border-green-400">
                    <h2 class="font-bold text-lg mb-2 text-green-700">Recommandations personnalisées</h2>
                    @php $reco = Auth::user()->recommendedCourses(); @endphp
                    @if($reco->isEmpty())
                        <div class="text-gray-500">Aucune recommandation pour le moment. Suivez plus de cours pour en obtenir !</div>
                    @else
                        <ul class="space-y-2">
                            @foreach($reco as $course)
                                <li class="p-2 bg-white rounded shadow flex flex-col md:flex-row md:items-center gap-2">
                                    <span class="font-semibold text-green-800">{{ $course->title }}</span>
                                    <span class="text-xs text-gray-500">({{ $course->category->name ?? 'Sans catégorie' }})</span>
                                    <a href="{{ route('courses.show', $course->slug) }}" class="ml-auto text-green-600 hover:underline">Voir le cours</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            @if(Auth::user() && Auth::user()->hasLearningDifficulties())
                <div class="my-6 p-4 bg-red-50 border-l-4 border-red-400">
                    <h2 class="font-bold text-lg mb-2 text-red-700">Besoin d'aide ?</h2>
                    <div class="mb-2 text-gray-700">
                        Nous avons détecté que vous rencontrez des difficultés dans votre apprentissage.<br>
                        Voici quelques ressources pour vous aider :
                    </div>
                    <ul class="list-disc pl-6 text-sm text-gray-800">
                        <li><a href="{{ route('faq') }}" class="text-blue-600 hover:underline">Consulter la FAQ</a></li>
                        <li><a href="{{ route('mentorat') }}" class="text-blue-600 hover:underline">Demander l'aide d'un mentor</a></li>
                        <li><a href="{{ route('contact') }}" class="text-blue-600 hover:underline">Contacter le support</a></li>
                    </ul>
                </div>
            @endif

            <div class="row">
                {{-- Cours en cours --}}
                <div class="col-lg-8 mb-4">
                    <div class="modern-card" data-aos="fade-up" data-aos-delay="500">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-play-circle me-2 text-primary"></i>
                                Mes cours en cours
                            </h5>
                        </div>
                        
                        <div class="courses-grid">
                            @forelse($enrollments->take(6) as $enrollment)
                                <div class="course-card-modern" data-aos="fade-up" data-aos-delay="{{ 600 + $loop->index * 100 }}">
                                    <div class="course-image">
                                        <img src="{{ $enrollment->course->cover_image_path ? asset($enrollment->course->cover_image_path) : asset('assets/images/default-course.jpg') }}" 
                                             alt="Image du cours {{ $enrollment->course->title }}" class="img-fluid" />
                                        <div class="course-overlay">
                                            <button wire:click="$emit('continueFromLastLesson', {{ $enrollment->course->id }})" 
                                                    class="btn btn-modern btn-sm w-full focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Continuer le cours {{ $enrollment->course->title }}">
                                                <i class="fas fa-play me-2"></i>Continuer
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="course-content">
                                        <h6 class="course-title">{{ $enrollment->course->title }}</h6>
                                        <div class="course-progress">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="progress-label">Progression</span>
                                                <span class="progress-value">{{ round($enrollment->progress_percentage) }}%</span>
                                            </div>
                                            <div class="progress-modern">
                                                <div class="progress-bar-modern" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-book-open display-1 text-muted opacity-50"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">Aucun cours en cours</h5>
                                    <p class="text-muted mb-4">Découvrez notre catalogue de formations pour commencer votre apprentissage.</p>
                                    <a href="{{ route('courses.index') }}" class="btn btn-modern">
                                        <i class="fas fa-search me-2"></i>Découvrir les cours
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Progression Overview --}}
                    <div class="progress-card mb-4" data-aos="fade-up" data-aos-delay="700">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Progression globale</h5>
                            <span class="h4 mb-0">{{ round($avgProgress) }}%</span>
                        </div>
                        
                        <div class="progress-modern mb-3" style="background: rgba(255, 255, 255, 0.2);">
                            <div class="progress-bar-modern" style="width: {{ $avgProgress }}%; background: white;"></div>
                        </div>
                        
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h6 mb-0">{{ $enrollments->count() }}</div>
                                <small class="opacity-75">Cours inscrits</small>
                            </div>
                            <div class="col-4">
                                <div class="h6 mb-0">{{ $completedCourses }}</div>
                                <small class="opacity-75">Terminés</small>
                            </div>
                            <div class="col-4">
                                <div class="h6 mb-0">{{ $certifications->count() }}</div>
                                <small class="opacity-75">Certifiés</small>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="modern-card mb-4" data-aos="fade-up" data-aos-delay="800">
                        <h5 class="mb-4">
                            <i class="fas fa-bolt me-2 text-warning"></i>
                            Actions rapides
                        </h5>
                        
                        <div class="quick-actions">
                            <button wire:click="showProfileView" class="action-card focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Accéder à mon profil">
                                <div class="action-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h6 class="mb-0">Profil</h6>
                                <small class="text-muted">Gérer mon compte</small>
                            </button>
                            
                            <button wire:click="showCertificationsView" class="action-card focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Voir mes certifications">
                                <div class="action-icon">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <h6 class="mb-0">Certifications</h6>
                                <small class="text-muted">Mes diplômes</small>
                            </button>
                            
                            <div class="action-card focus:outline-none focus:ring-2 focus:ring-primary" onclick="window.location.href='{{ route('courses.index') }}'" tabindex="0" aria-label="Explorer les nouveaux cours">
                                <div class="action-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h6 class="mb-0">Explorer</h6>
                                <small class="text-muted">Nouveaux cours</small>
                            </div>
                            
                            <div class="action-card focus:outline-none focus:ring-2 focus:ring-primary" onclick="window.location.href='{{ route('pages.forumapp') }}'" tabindex="0" aria-label="Accéder à la communauté">
                                <div class="action-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h6 class="mb-0">Communauté</h6>
                                <small class="text-muted">Échanger</small>
                            </div>
                        </div>
                    </div>

                    {{-- Certifications Recent --}}
                    <div class="modern-card" data-aos="fade-up" data-aos-delay="900">
                        <h5 class="mb-4">
                            <i class="fas fa-award me-2 text-success"></i>
                            Mes certifications
                        </h5>
                        
                        @forelse($certifications->take(3) as $certification)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-medal text-warning"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="activity-title">{{ $certification->course->title }}</h6>
                                    <p class="activity-text">Obtenue le {{ $certification->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-award display-4 text-muted opacity-50"></i>
                                <p class="text-muted mt-3">Aucune certification pour le moment</p>
                                <small class="text-muted">Terminez vos cours pour obtenir vos premières certifications</small>
                            </div>
                        @endforelse
                        
                        @if($certifications->count() > 3)
                            <div class="text-center mt-3">
                                <button wire:click="showCertificationsView" class="btn btn-outline-modern btn-sm">
                                    Voir toutes les certifications
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Vue Leçon --}}
    @if($currentView === 'lesson')
        <div class="lesson-view" data-aos="fade-up">
            @livewire('lesson-viewer', ['lessonId' => $currentLessonId], key('lesson-'.$currentLessonId))
        </div>
    @endif

    {{-- Vue Profil --}}
    @if($currentView === 'profile')
        <div class="profile-view" data-aos="fade-up">
            @livewire('profile-manager')
        </div>
    @endif

    {{-- Vue Certifications --}}
    @if($currentView === 'certifications')
        <div class="certifications-view" data-aos="fade-up">
            @include('apprenants.partials.certifications-content', $this->getCertificationData())
        </div>
    @endif
</div>

@push('styles')
<style>
    .spa-container {
        min-height: 100vh;
    }

    .breadcrumb-modern {
        background: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 1rem;
    }

    .breadcrumb-modern .btn-link {
        background: none;
        border: none;
        color: var(--text-secondary);
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
    }

    .breadcrumb-modern .btn-link:hover,
    .breadcrumb-modern .btn-link.active {
        background: var(--primary-color);
        color: white;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .course-card-modern {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
    }

    .course-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow);
    }

    .course-image {
        position: relative;
        height: 180px;
        overflow: hidden;
    }

    .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .course-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .course-card-modern:hover .course-overlay {
        opacity: 1;
    }

    .course-content {
        padding: 1.5rem;
    }

    .course-title {
        margin-bottom: 1rem;
        color: var(--text-primary);
        font-weight: 600;
    }

    .course-progress {
        margin-top: 1rem;
    }

    .progress-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .progress-value {
        font-size: 0.875rem;
        color: var(--primary-color);
        font-weight: 700;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .action-card {
        background: var(--gray-50);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        padding: 1.5rem 1rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
        box-shadow: var(--shadow);
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .action-card:hover .action-icon {
        background: white;
        color: var(--primary-color);
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        margin: 0 0 0.25rem 0;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .activity-text {
        margin: 0;
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    @media (max-width: 768px) {
        .courses-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Écouter les événements Livewire pour les changements de vue
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('continueFromLastLesson', (courseId) => {
            @this.call('continueFromLastLesson', courseId);
        });
    });
</script>
@endpush
