@extends('admin.layouts.app')

@section('breadcrumb', 'Détails du cours')

@section('content')
<div class="fade-in">
    <!-- Course Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div style="display: flex; align-items: center; gap: 1rem;">
                @if($course->cover_image_path)
                    <img src="{{ asset($course->cover_image_path) }}" 
                         alt="{{ $course->title }}" 
                         style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 3px solid #1EA38B;">
                @else
                    <div style="width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                        <i class="fas fa-book"></i>
                    </div>
                @endif
                <div>
                    <h1 class="admin-card-title" style="font-size: 1.8rem; margin-bottom: 0.5rem;">{{ $course->title }}</h1>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                        @if($course->status === 'published')
                            <span style="background: rgba(39, 179, 113, 0.1); color: #27B371; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                <i class="fas fa-eye me-1"></i>Publié
                            </span>
                        @else
                            <span style="background: rgba(108, 117, 125, 0.1); color: #6C757D; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                <i class="fas fa-edit me-1"></i>Brouillon
                            </span>
                        @endif
                        <span style="color: #6C757D; font-size: 0.9rem;">
                            <i class="fas fa-calendar me-1"></i>Créé le {{ $course->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                    <p style="color: #6C757D; margin: 0; line-height: 1.5;">{{ $course->short_description }}</p>
                </div>
            </div>
            <div class="admin-card-actions">
                <a href="{{ route('courses.show', $course->slug) }}" target="_blank" 
                   style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; margin-right: 0.5rem;">
                    <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                </a>
                <a href="{{ route('admin.courses.edit', $course) }}" 
                   style="background: linear-gradient(135deg, #FF8E2A, #FFB366); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="admin-grid admin-grid-4" style="margin-bottom: 2rem;">
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="admin-stats-number">{{ $course->enrollments->count() }}</div>
            <div class="admin-stats-label">Étudiants inscrits</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #FF8E2A, #FFB366);">
                <i class="fas fa-play-circle"></i>
            </div>
            <div class="admin-stats-number">{{ $course->modules->sum(function($module) { return $module->lessons->count(); }) }}</div>
            <div class="admin-stats-label">Leçons</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #E32D31, #FF6B6B);">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="admin-stats-number">{{ $course->modules->count() }}</div>
            <div class="admin-stats-label">Modules</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="400">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #6F42C1, #8A63D2);">
                <i class="fas fa-star"></i>
            </div>
            <div class="admin-stats-number">{{ number_format($course->ratings->avg('rating') ?? 0, 1) }}</div>
            <div class="admin-stats-label">Note moyenne</div>
        </div>
    </div>

    <div class="admin-grid admin-grid-3">
        <!-- Course Information -->
        <div class="admin-card admin-span-2" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-info-circle me-2"></i>Informations détaillées
                </h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-grid admin-grid-2" style="gap: 2rem;">
                    <div>
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-user me-2 text-primary"></i>Formateur
                            </label>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                    {{ strtoupper(substr($course->formateur->first_name, 0, 1)) }}{{ strtoupper(substr($course->formateur->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 500; color: #333;">{{ $course->formateur->first_name }} {{ $course->formateur->last_name }}</div>
                                    <div style="font-size: 0.85rem; color: #6C757D;">{{ $course->formateur->email }}</div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-folder me-2 text-warning"></i>Catégorie
                            </label>
                            <span style="background: linear-gradient(135deg, rgba(255, 142, 42, 0.1), rgba(255, 179, 102, 0.1)); color: #FF8E2A; padding: 0.5rem 1rem; border-radius: 25px; font-weight: 600;">
                                {{ $course->category->name ?? 'Non catégorisé' }}
                            </span>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-signal me-2 text-info"></i>Niveau
                            </label>
                            @php
                                $levelColors = [
                                    'débutant' => '#17A2B8',
                                    'intermédiaire' => '#FFC107', 
                                    'avancé' => '#DC3545',
                                    'expert' => '#6F42C1'
                                ];
                                $levelColor = $levelColors[$course->level] ?? '#6C757D';
                            @endphp
                            <span style="background: {{ $levelColor }}; color: white; padding: 0.5rem 1rem; border-radius: 25px; font-weight: 600; text-transform: capitalize;">
                                {{ $course->level }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-euro-sign me-2 text-success"></i>Prix
                            </label>
                            <div style="font-size: 1.5rem; font-weight: 700; color: {{ $course->price > 0 ? '#1EA38B' : '#FF8E2A' }};">
                                @if($course->price > 0)
                                    {{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency }}
                                @else
                                    Gratuit
                                @endif
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-certificate me-2 text-danger"></i>Certification
                            </label>
                            @if($course->is_certifying)
                                <span style="background: rgba(39, 179, 113, 0.1); color: #27B371; padding: 0.5rem 1rem; border-radius: 25px; font-weight: 600;">
                                    <i class="fas fa-check me-1"></i>Certifiant
                                </span>
                            @else
                                <span style="background: rgba(108, 117, 125, 0.1); color: #6C757D; padding: 0.5rem 1rem; border-radius: 25px; font-weight: 600;">
                                    <i class="fas fa-times me-1"></i>Non certifiant
                                </span>
                            @endif
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-clock me-2 text-secondary"></i>Dates
                            </label>
                            <div style="font-size: 0.9rem; color: #6C757D; line-height: 1.6;">
                                <div><strong>Créé :</strong> {{ $course->created_at->format('d/m/Y à H:i') }}</div>
                                <div><strong>Modifié :</strong> {{ $course->updated_at->format('d/m/Y à H:i') }}</div>
                                @if($course->published_at)
                                    <div><strong>Publié :</strong> {{ $course->published_at->format('d/m/Y à H:i') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($course->full_description)
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #E9ECEF;">
                        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 1rem;">
                            <i class="fas fa-align-left me-2 text-primary"></i>Description complète
                        </label>
                        <div style="background: #F8F9FA; padding: 1.5rem; border-radius: 8px; line-height: 1.6; color: #495057;">
                            {!! nl2br(e($course->full_description)) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Course Structure -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-sitemap me-2"></i>Structure du cours
                </h3>
            </div>
            <div class="admin-card-body">
                @if($course->modules->count() > 0)
                    <div style="space-y: 1rem;">
                        @foreach($course->modules as $index => $module)
                            <div style="border: 1px solid #E9ECEF; border-radius: 8px; overflow: hidden; margin-bottom: 1rem;">
                                <div style="background: linear-gradient(135deg, #F8F9FA, #E9ECEF); padding: 1rem; border-bottom: 1px solid #E9ECEF;">
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <h4 style="margin: 0; font-size: 1rem; font-weight: 600; color: #333;">
                                            <span style="background: #1EA38B; color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; margin-right: 0.5rem;">
                                                {{ $index + 1 }}
                                            </span>
                                            {{ $module->title }}
                                        </h4>
                                        <span style="background: rgba(30, 163, 139, 0.1); color: #1EA38B; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                            {{ $module->lessons->count() }} leçon(s)
                                        </span>
                                    </div>
                                    @if($module->description)
                                        <p style="margin: 0.5rem 0 0 2rem; font-size: 0.9rem; color: #6C757D;">{{ $module->description }}</p>
                                    @endif
                                </div>
                                @if($module->lessons->count() > 0)
                                    <div style="padding: 0;">
                                        @foreach($module->lessons as $lesson)
                                            <div style="padding: 0.75rem 1rem; border-bottom: 1px solid #F1F3F4; display: flex; align-items: center; justify-content: between;">
                                                <div style="display: flex; align-items: center; flex: 1;">
                                                    @php
                                                        $iconMap = [
                                                            'video' => 'fa-play-circle text-danger',
                                                            'text' => 'fa-file-text text-primary',
                                                            'pdf' => 'fa-file-pdf text-danger',
                                                            'external' => 'fa-external-link-alt text-info'
                                                        ];
                                                        $icon = $iconMap[$lesson->content_type] ?? 'fa-file text-secondary';
                                                    @endphp
                                                    <i class="fas {{ $icon }} me-2"></i>
                                                    <span style="font-size: 0.9rem; color: #333;">{{ $lesson->title }}</span>
                                                </div>
                                                @if($lesson->duration_minutes)
                                                    <span style="font-size: 0.8rem; color: #6C757D;">{{ $lesson->duration_minutes }} min</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem; color: #6C757D;">
                        <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                        <div style="font-weight: 500; margin-bottom: 0.5rem;">Aucun module</div>
                        <div style="font-size: 0.9rem;">Ce cours n'a pas encore de modules.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($course->learning_objectives && count($course->learning_objectives) > 0)
        <!-- Learning Objectives -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="300" style="margin-top: 2rem;">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-bullseye me-2"></i>Objectifs d'apprentissage
                </h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-grid admin-grid-2" style="gap: 1rem;">
                    @foreach($course->learning_objectives as $objective)
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                            <div style="background: #1EA38B; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 0.1rem;">
                                <i class="fas fa-check" style="font-size: 0.7rem;"></i>
                            </div>
                            <span style="color: #333; line-height: 1.5;">{{ $objective }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($course->prerequisites && count($course->prerequisites) > 0)
        <!-- Prerequisites -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="400" style="margin-top: 2rem;">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-clipboard-list me-2"></i>Prérequis
                </h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-grid admin-grid-2" style="gap: 1rem;">
                    @foreach($course->prerequisites as $prerequisite)
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem; background: #FFF3CD; border-radius: 8px; border-left: 4px solid #FFC107;">
                            <div style="background: #FFC107; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 0.1rem;">
                                <i class="fas fa-exclamation" style="font-size: 0.7rem;"></i>
                            </div>
                            <span style="color: #856404; line-height: 1.5;">{{ $prerequisite }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Animation des cartes
    AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true
    });
</script>
@endpush
                        </table>
                    </div>
                </div>

                <h5 class="mb-3">Description</h5>
                <div class="course-description mb-4">
                    {!! nl2br(e($course->description)) !!}
                </div>

                @if($course->preview_video_url)
                <h5 class="mb-3">Vidéo de présentation</h5>
                <div class="ratio ratio-16x9 mb-4">
                    @if(Str::contains($course->preview_video_url, ['youtube.com', 'youtu.be']))
                        @php
                            $videoId = null;
                            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([^\/\n\s"&?]{11})/', $course->preview_video_url, $match)) {
                                $videoId = $match[1];
                            }
                        @endphp
                        @if($videoId)
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen></iframe>
                        @else
                            <div class="alert alert-warning">Format de vidéo YouTube non reconnu</div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <a href="{{ $course->preview_video_url }}" target="_blank">Voir la vidéo</a>
                        </div>
                    @endif
                </div>
                @endif
            </div>
            
            <!-- Course Content -->
            <div class="dashboard-card">
                <h5 class="mb-3">Contenu du cours</h5>
                
                @php $moduleCount = $course->modules->count(); @endphp
                
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-primary me-2">{{ $moduleCount }} {{ \Illuminate\Support\Str::plural('module', $moduleCount) }}</span>
                        
                        @php
                            $lessonCount = 0;
                            $quizCount = 0;
                            foreach ($course->modules as $module) {
                                $lessonCount += $module->lessons->count();
                                $quizCount += $module->quizzes->count();
                            }
                        @endphp
                        
                        <span class="badge bg-info me-2">{{ $lessonCount }} {{ \Illuminate\Support\Str::plural('leçon', $lessonCount) }}</span>
                        <span class="badge bg-warning">{{ $quizCount }} {{ \Illuminate\Support\Str::plural('quiz', $quizCount) }}</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                            <i class="fas fa-plus-circle"></i> Ajouter un module
                        </button>
                    </div>
                </div>
                
                <div class="accordion" id="moduleAccordion">
                    @forelse($course->modules as $index => $module)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $module->id }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $module->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $module->id }}">
                                    <span class="fw-bold">Module {{ $index + 1 }}: {{ $module->title }}</span>
                                </button>
                            </h2>
                            <div id="collapse{{ $module->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $module->id }}" data-bs-parent="#moduleAccordion">
                                <div class="accordion-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div>
                                            <p class="text-muted mb-1">{{ $module->description }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.modules.edit', $module->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-edit"></i> Modifier le module
                                            </a>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-plus"></i> Ajouter
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.lessons.create', ['module_id' => $module->id]) }}">
                                                            <i class="fas fa-book-open me-2"></i> Leçon
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.quizzes.create', ['module_id' => $module->id]) }}">
                                                            <i class="fas fa-question-circle me-2"></i> Quiz
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group">
                                        @foreach($module->lessons as $lesson)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-book-open me-2 text-info"></i>
                                                    <a href="{{ route('admin.lessons.show', $lesson->id) }}">
                                                        {{ $lesson->title }}
                                                    </a>
                                                </div>
                                                <div>
                                                    <span class="badge bg-light text-dark me-2">
                                                        @if($lesson->type === 'video')
                                                            <i class="fas fa-video me-1"></i> Vidéo
                                                        @elseif($lesson->type === 'pdf')
                                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                                        @elseif($lesson->type === 'text')
                                                            <i class="fas fa-file-alt me-1"></i> Texte
                                                        @endif
                                                    </span>
                                                    <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @foreach($module->quizzes as $quiz)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-question-circle me-2 text-warning"></i>
                                                    <a href="{{ route('admin.quizzes.show', $quiz->id) }}">
                                                        {{ $quiz->title }}
                                                    </a>
                                                </div>
                                                <div>
                                                    <span class="badge bg-warning text-dark me-2">
                                                        <i class="fas fa-tasks me-1"></i> Quiz
                                                    </span>
                                                    <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            Ce cours n'a pas encore de modules.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Enrollment Stats -->
            <div class="dashboard-card mb-4">
                <h5>Statistiques d'inscription</h5>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>Total des inscriptions</div>
                    <div class="badge bg-primary fs-6">{{ $course->enrollments->count() }}</div>
                </div>
                
                <h6 class="text-muted mb-2">Inscriptions récentes</h6>
                <div class="list-group">
                    @forelse($course->enrollments->sortByDesc('created_at')->take(5) as $enrollment)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}
                            </div>
                            <div class="text-muted small">
                                {{ $enrollment->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-2">
                            Aucune inscription pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Ratings -->
            <div class="dashboard-card mb-4">
                <h5>Évaluations</h5>
                @php
                    $avgRating = $course->ratings->avg('rating') ?? 0;
                    $ratingCount = $course->ratings->count();
                @endphp
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div class="fw-bold me-2">{{ number_format($avgRating, 1) }}</div>
                        <div class="ratings">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-secondary">{{ $ratingCount }} {{ \Illuminate\Support\Str::plural('avis', $ratingCount) }}</span>
                    </div>
                </div>
                
                <div class="list-group">
                    @forelse($course->ratings->sortByDesc('created_at')->take(3) as $rating)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="fw-bold">{{ $rating->user->first_name }} {{ $rating->user->last_name }}</div>
                                <div class="text-muted small">{{ $rating->created_at->format('d/m/Y') }}</div>
                            </div>
                            <div class="ratings mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <p class="mb-0">{{ $rating->comment }}</p>
                        </div>
                    @empty
                        <div class="text-center py-2">
                            Aucune évaluation pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Revenue -->
            <div class="dashboard-card">
                <h5>Revenus</h5>
                
                @php
                    $totalAmount = $course->enrollments
                        ->filter(function($enrollment) {
                            return $enrollment->payment && $enrollment->payment->status === 'completed';
                        })
                        ->sum(function($enrollment) {
                            return $enrollment->payment ? $enrollment->payment->amount : 0;
                        });
                @endphp
                
                <div class="text-center mb-3">
                    <div class="stats-number">{{ number_format($totalAmount, 0, ',', ' ') }} XOF</div>
                    <div class="text-muted">Revenu total</div>
                </div>
                
                <div class="text-center">
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-chart-line me-1"></i> Voir les détails des revenus
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un module -->
<div class="modal fade" id="addModuleModal" tabindex="-1" aria-labelledby="addModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModuleModalLabel">Ajouter un nouveau module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route('admin.modules.store') }}" method="POST">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="module-title" class="form-label">Titre du module</label>
                        <input type="text" class="form-control" id="module-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="module-description" class="form-label">Description</label>
                        <textarea class="form-control" id="module-description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="module-order" class="form-label">Ordre</label>
                        <input type="number" class="form-control" id="module-order" name="order" value="{{ $course->modules->count() + 1 }}" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter le module</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
