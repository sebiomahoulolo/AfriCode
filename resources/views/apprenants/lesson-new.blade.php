@extends('apprenants.layouts.app')

@section('title', $lesson->title . ' | AfriCode')
@section('page-title', $lesson->title)

@push('styles')
<style>
    /* Lesson page specific styles */
    .lesson-container {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
        height: calc(100vh - 140px);
        transition: grid-template-columns 0.3s ease;
    }

    .lesson-container.sidebar-hidden {
        grid-template-columns: 0px 1fr;
    }

    .lesson-sidebar {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .lesson-sidebar.hidden {
        transform: translateX(-100%);
        opacity: 0;
        pointer-events: none;
    }

    .sidebar-toggle {
        position: fixed !important;
        top: 50% !important;
        left: 20px !important;
        transform: translateY(-50%) !important;
        z-index: 9999 !important;
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        color: white !important;
        border: 3px solid white !important;
        border-radius: 50% !important;
        width: 55px !important;
        height: 55px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 6px 20px rgba(30, 163, 139, 0.4) !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        font-size: 1.3rem !important;
        outline: none !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    .sidebar-toggle:hover {
        transform: translateY(-50%) scale(1.15) !important;
        box-shadow: 0 8px 25px rgba(30, 163, 139, 0.5) !important;
        background: linear-gradient(135deg, #27B371 0%, #1EA38B 100%) !important;
    }

    .sidebar-toggle:focus {
        outline: 3px solid rgba(30, 163, 139, 0.5) !important;
        outline-offset: 2px !important;
    }

    .sidebar-toggle.sidebar-visible {
        left: 370px !important;
    }

    /* Styles pour le bouton dans l'en-tête (mobile) */
    .header-sidebar-toggle {
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        border: 2px solid #1EA38B !important;
        color: white !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    .header-sidebar-toggle:hover {
        background: #5a67d8 !important;
        border-color: #5a67d8 !important;
        color: white !important;
    }

    .course-header-sticky {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        color: white;
        padding: 1.5rem;
        border-radius: 8px 8px 0 0;
        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .course-progress-mini {
        background: rgba(255,255,255,0.2);
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 0.75rem;
    }

    .course-progress-mini .progress-bar {
        background: white;
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .modules-content {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
    }

    .lesson-link {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .lesson-link:hover {
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.05), rgba(39, 179, 113, 0.05));
        color: #1EA38B;
        transform: translateX(4px);
        text-decoration: none;
        border-color: #1EA38B;
    }

    .lesson-link.active {
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1));
        color: #1EA38B;
        border-color: #1EA38B;
        font-weight: 600;
    }

    .lesson-content {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .lesson-header {
        padding: 2rem;
        border-bottom: 1px solid var(--gray-200);
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    }

    .lesson-body {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
    }

    .lesson-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--gray-200);
        background: var(--gray-50);
    }

    .module-group {
        margin-bottom: 1rem;
    }

    .module-header {
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%);
        color: white;
        padding: 1.2rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #1EA38B;
        box-shadow: 0 2px 8px rgba(30, 163, 139, 0.15);
    }

    .module-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 163, 139, 0.25);
        background: linear-gradient(135deg, #27B371 0%, #1EA38B 100%);
    }

    .module-lessons {
        padding: 0.75rem 0;
        background: #f8fffe;
        border: 1px solid #e6f7f5;
        border-top: none;
        border-radius: 0 0 8px 8px;
    }

    .lesson-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.875rem 1rem;
        margin: 0.25rem 0.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: #374151;
        background: white;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .lesson-item:hover {
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.05), rgba(39, 179, 113, 0.05));
        color: #1EA38B;
        transform: translateX(4px);
        text-decoration: none;
        border-color: #1EA38B;
        box-shadow: 0 2px 8px rgba(30, 163, 139, 0.15);
    }

    .lesson-item.active {
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1));
        color: #1EA38B;
        border-color: #1EA38B;
        font-weight: 600;
        box-shadow: 0 2px 12px rgba(30, 163, 139, 0.2);
    }

    .lesson-item.completed {
        color: var(--success-color);
    }

    .lesson-icon {
        width: 24px;
        margin-right: 0.75rem;
        text-align: center;
    }

    .video-container {
        position: relative;
        width: 100%;
        height: 400px;
        background: var(--gray-900);
        border-radius: var(--border-radius-sm);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .video-container iframe {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: var(--border-radius-sm);
    }

    .video-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: white;
        font-size: 4rem;
        background: linear-gradient(135deg, var(--gray-800), var(--gray-900));
    }

    .lesson-meta {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .lesson-progress {
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1));
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(30, 163, 139, 0.2);
    }

    .breadcrumb-modern {
        background: none;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        content: "→";
        color: var(--gray-400);
    }

    .navigation-buttons {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .btn-nav {
        flex: 1;
        max-width: 200px;
    }

    .resources-section {
        background: var(--gray-50);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .resource-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 0.5rem;
        background: white;
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .resource-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .resource-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%);
        color: white;
        border-radius: 50%;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .text-content {
        line-height: 1.8;
        font-size: 1.1rem;
        color: var(--gray-700);
    }

    .text-content h1, .text-content h2, .text-content h3, .text-content h4, .text-content h5, .text-content h6 {
        color: var(--gray-800);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .text-content p {
        margin-bottom: 1.5rem;
    }

    .text-content code {
        background: var(--gray-100);
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-family: 'Monaco', 'Consolas', monospace;
    }

    .text-content pre {
        background: var(--gray-900);
        color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius-sm);
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    @media (max-width: 1024px) {
        .lesson-container {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .lesson-container.sidebar-hidden {
            grid-template-columns: 1fr;
        }

        .lesson-sidebar {
            order: 2;
            max-height: 300px;
        }

        .lesson-content {
            order: 1;
        }

        .sidebar-toggle {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .lesson-header,
        .lesson-body,
        .lesson-footer {
            padding: 1rem;
        }

        .video-container {
            height: 250px;
        }

        .lesson-meta {
            gap: 1rem;
        }

        .navigation-buttons {
            flex-direction: column;
        }

        .btn-nav {
            max-width: none;
        }
    }
</style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-modern mb-3" data-aos="fade-right">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('apprenant.dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Tableau de bord
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="#" class="text-decoration-none">{{ $lesson->module->course->title }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $lesson->title }}</li>
        </ol>
    </nav>

    <!-- Course Progress -->
    <div class="lesson-progress" data-aos="fade-up">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="fas fa-book-open me-2"></i>
                {{ $lesson->module->course->title }}
            </h5>
            <span class="badge-modern {{ $isCompleted ? 'badge-success' : 'badge-primary' }}">
                {{ $isCompleted ? 'Terminée' : 'En cours' }}
            </span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">Progression du cours</small>
            <small class="fw-bold">{{ count($completedLessons) }} / {{ $lesson->module->course->getLessonsCount() }} leçons</small>
        </div>
        
        <div class="progress-modern">
            <div class="progress-bar-modern" 
                 style="width: {{ count($completedLessons) / $lesson->module->course->getLessonsCount() * 100 }}%"></div>
        </div>
    </div>

    <!-- Bouton de basculement de la sidebar -->
    <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
        <i class="fas fa-bars" id="toggleIcon"></i>
    </button>

    <!-- Main Lesson Container -->
    <div class="lesson-container" id="lessonContainer" data-aos="fade-up" data-aos-delay="100">
        <!-- Sidebar with Course Structure -->
        <div class="lesson-sidebar" id="lessonSidebar">
            <!-- En-tête du cours fixe -->
            <div class="course-header-sticky">
                <h5 class="mb-2">
                    <i class="fas fa-graduation-cap me-2"></i>
                    {{ $lesson->module->course->title }}
                </h5>
                <div class="d-flex justify-content-between align-items-center">
                    <small>
                        @php
                            $totalLessons = $lesson->module->course->modules->sum(function($module) { 
                                return $module->lessons->count(); 
                            });
                            $currentLessonNumber = 1;
                            $lessonCounter = 0;
                            foreach($lesson->module->course->modules as $module) {
                                foreach($module->lessons as $lessonItem) {
                                    $lessonCounter++;
                                    if($lessonItem->id == $lesson->id) {
                                        $currentLessonNumber = $lessonCounter;
                                        break 2;
                                    }
                                }
                            }
                            $progressPercent = isset($completedLessons) ? (count($completedLessons) / max(1, $totalLessons)) * 100 : 0;
                        @endphp
                        Leçon {{ $currentLessonNumber }} / {{ $totalLessons }}
                    </small>
                    <small>
                        {{ number_format($progressPercent, 0) }}% complété
                    </small>
                </div>
                <div class="course-progress-mini">
                    <div class="progress-bar" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>

            <!-- Contenu des modules scrollable -->
            <div class="modules-content">
                @foreach($lesson->course->modules as $module)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-folder-open me-2"></i>
                            {{ $module->title }}
                        </h6>
                        
                        @foreach($module->lessons as $lessonItem)
                            <a href="{{ route('apprenant.lesson', $lessonItem->id) }}" 
                               class="lesson-link d-flex align-items-center justify-content-between text-decoration-none mb-2 p-2 rounded {{ $lessonItem->id == $lesson->id ? 'active' : '' }}">
                                <div class="d-flex align-items-center">
                                    @if($lessonItem->lessonType->name == 'Vidéo')
                                        <i class="fas fa-play-circle me-2 text-primary"></i>
                                    @elseif($lessonItem->lessonType->name == 'Quiz')
                                        <i class="fas fa-question-circle me-2 text-warning"></i>
                                    @else
                                        <i class="fas fa-file-alt me-2 text-info"></i>
                                    @endif
                                    <span class="small">{{ $lessonItem->title }}</span>
                                </div>
                                
                                <!-- Status indicator -->
                                @php
                                    $lessonProgress = auth()->user()->progressLessons()->where('lesson_id', $lessonItem->id)->first();
                                    $isCompleted = $lessonProgress && $lessonProgress->completed_at;
                                @endphp
                                
                                @if($isCompleted)
                                    <i class="fas fa-check-circle text-success"></i>
                                @elseif($lessonItem->id == $lesson->id)
                                    <i class="fas fa-circle text-primary"></i>
                                @else
                                    <i class="far fa-circle text-muted"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Content -->
        <div class="lesson-content">
            <div class="lesson-header">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="lesson-meta">
                        <div class="meta-item">
                            <i class="fas fa-play-circle text-primary"></i>
                            <span>{{ $lesson->duration_minutes }} minutes</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-layer-group text-info"></i>
                            <span>{{ $lesson->module->title }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-user text-warning"></i>
                            <span>{{ $lesson->module->course->formateur->first_name }} {{ $lesson->module->course->formateur->last_name }}</span>
                        </div>
                    </div>
                    <!-- Bouton de basculement de la sidebar - visible sur toutes les tailles -->
                    <button class="btn btn-primary btn-sm" onclick="toggleSidebar()" title="Afficher/Masquer la structure du cours">
                        <i class="fas fa-list" id="headerToggleIcon"></i>
                        <span class="ms-1">Structure</span>
                    </button>
                </div>
                
                <h1 class="h3 mb-0">{{ $lesson->title }}</h1>
                @if($lesson->description)
                    <p class="text-muted mt-2 mb-0">{{ $lesson->description }}</p>
                @endif
            </div>

            <div class="lesson-body">
                @if($lesson->content_type === 'video' && $lesson->video_url)
                    <div class="video-container">
                        @php
                            // Traitement des URL YouTube pour obtenir l'ID de la vidéo
                            $videoId = '';
                            if (strpos($lesson->video_url, 'youtube.com') !== false) {
                                parse_str(parse_url($lesson->video_url, PHP_URL_QUERY), $params);
                                $videoId = $params['v'] ?? '';
                            } elseif (strpos($lesson->video_url, 'youtu.be') !== false) {
                                $videoId = substr(parse_url($lesson->video_url, PHP_URL_PATH), 1);
                            }
                        @endphp

                        @if($videoId)
                            <iframe 
                                src="https://www.youtube.com/embed/{{ $videoId }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        @else
                            <div class="video-placeholder">
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle mb-3"></i>
                                    <p class="mb-0">Vidéo non disponible</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                
                @if($lesson->content_type === 'text' || $lesson->text_content)
                    <div class="text-content">
                        {!! $lesson->text_content !!}
                    </div>
                @endif

                @if($lesson->content_type === 'pdf' && $lesson->pdf_path)
                    <div class="text-center mb-4">
                        <a href="{{ asset($lesson->pdf_path) }}" class="btn btn-modern" target="_blank">
                            <i class="fas fa-file-pdf me-2"></i> Ouvrir le PDF
                        </a>
                    </div>
                    <embed src="{{ asset($lesson->pdf_path) }}" type="application/pdf" width="100%" height="600px" 
                           style="border-radius: var(--border-radius-sm);" />
                @endif
                
                @if($lesson->content_type === 'external' && $lesson->external_url)
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-external-link-alt display-3 text-primary"></i>
                        </div>
                        <h5 class="mb-3">Contenu externe</h5>
                        <p class="text-muted mb-4">Cette leçon est hébergée sur une plateforme externe.</p>
                        <a href="{{ $lesson->external_url }}" class="btn btn-modern" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i> Accéder au contenu
                        </a>
                    </div>
                @endif
                
                @if(isset($resources) && $resources->isNotEmpty())
                    <div class="resources-section">
                        <h5 class="mb-3">
                            <i class="fas fa-download me-2"></i>
                            Ressources supplémentaires
                        </h5>
                        
                        @foreach($resources as $resource)
                        <div class="resource-item">
                            <div class="resource-icon">
                                @switch($resource->type)
                                    @case('pdf')
                                        <i class="fas fa-file-pdf"></i>
                                        @break
                                    @case('archive')
                                        <i class="fas fa-file-archive"></i>
                                        @break
                                    @case('image')
                                        <i class="fas fa-file-image"></i>
                                        @break
                                    @case('code')
                                        <i class="fas fa-file-code"></i>
                                        @break
                                    @default
                                        <i class="fas fa-file"></i>
                                @endswitch
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $resource->title }}</h6>
                                <p class="mb-0 text-muted small">{{ $resource->description }}</p>
                            </div>
                            <a href="{{ asset($resource->file_path) }}" class="btn btn-outline-modern btn-sm" target="_blank">
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="lesson-footer">
                <div class="navigation-buttons">
                    @if(isset($previousLesson))
                    <a href="{{ route('apprenant.lesson', ['lessonId' => $previousLesson->id]) }}" 
                       class="btn btn-outline-modern btn-nav">
                        <i class="fas fa-arrow-left me-2"></i>Précédent
                    </a>
                    @else
                    <div></div>
                    @endif
                    
                    <div class="d-flex gap-2">
                        @if(!$isCompleted)
                        <form action="{{ route('apprenant.lesson.complete', ['lessonId' => $lesson->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @if(isset($nextLesson))
                            <input type="hidden" name="next_lesson_id" value="{{ $nextLesson->id }}">
                            @endif
                            <button type="submit" class="btn btn-modern">
                                <i class="fas fa-check-circle me-2"></i>Marquer terminée
                            </button>
                        </form>
                        @endif
                        
                        @if(isset($nextLesson))
                        <a href="{{ route('apprenant.lesson', ['lessonId' => $nextLesson->id]) }}" 
                           class="btn btn-modern btn-nav">
                            Suivant<i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        @else
                        <a href="{{ route('apprenant.dashboard') }}" class="btn btn-outline-modern btn-nav">
                            <i class="fas fa-home me-2"></i>Tableau de bord
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let sidebarVisible = true;

    function toggleSidebar() {
        console.log('toggleSidebar() appelée, état actuel:', sidebarVisible);
        
        const sidebar = document.getElementById('lessonSidebar');
        const container = document.getElementById('lessonContainer');
        const toggleButton = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        const headerToggleIcon = document.getElementById('headerToggleIcon');
        
        console.log('Éléments trouvés:', {
            sidebar: !!sidebar,
            container: !!container,
            toggleButton: !!toggleButton,
            toggleIcon: !!toggleIcon,
            headerToggleIcon: !!headerToggleIcon
        });
        
        sidebarVisible = !sidebarVisible;
        
        if (sidebarVisible) {
            // Afficher la sidebar
            if (sidebar) sidebar.classList.remove('hidden');
            if (container) container.classList.remove('sidebar-hidden');
            if (toggleButton) toggleButton.classList.add('sidebar-visible');
            if (toggleIcon) {
                toggleIcon.classList.remove('fa-times');
                toggleIcon.classList.add('fa-bars');
            }
            if (headerToggleIcon) {
                headerToggleIcon.classList.remove('fa-times');
                headerToggleIcon.classList.add('fa-list');
            }
            console.log('Sidebar affichée');
        } else {
            // Masquer la sidebar
            if (sidebar) sidebar.classList.add('hidden');
            if (container) container.classList.add('sidebar-hidden');
            if (toggleButton) toggleButton.classList.remove('sidebar-visible');
            if (toggleIcon) {
                toggleIcon.classList.remove('fa-bars');
                toggleIcon.classList.add('fa-times');
            }
            if (headerToggleIcon) {
                headerToggleIcon.classList.remove('fa-list');
                headerToggleIcon.classList.add('fa-times');
            }
            console.log('Sidebar masquée');
        }
        
        // Sauvegarder l'état dans localStorage
        localStorage.setItem('sidebarVisible', sidebarVisible);
    }

    // Restaurer l'état de la sidebar au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM chargé, initialisation de la sidebar');
        
        // S'assurer que le bouton est visible
        const toggleButton = document.getElementById('sidebarToggle');
        if (toggleButton) {
            toggleButton.style.display = 'flex';
            toggleButton.style.visibility = 'visible';
            console.log('Bouton sidebar rendu visible');
        } else {
            console.error('Bouton sidebar non trouvé dans le DOM');
        }
        
        // Vérifier tous les éléments requis
        const elements = {
            sidebar: document.getElementById('lessonSidebar'),
            container: document.getElementById('lessonContainer'),
            toggleIcon: document.getElementById('toggleIcon'),
            headerToggleIcon: document.getElementById('headerToggleIcon')
        };
        
        console.log('État des éléments au chargement:', elements);
        
        const savedState = localStorage.getItem('sidebarVisible');
        if (savedState !== null) {
            const savedVisible = JSON.parse(savedState);
            console.log('État sauvegardé récupéré:', savedVisible);
            if (savedVisible !== sidebarVisible) {
                sidebarVisible = !savedVisible; // Inverser pour que toggleSidebar() le remette dans le bon état
                toggleSidebar();
            }
        }
    });

    function toggleModule(moduleId) {
        const lessons = document.getElementById('lessons-' + moduleId);
        const icon = document.getElementById('icon-' + moduleId);
        
        if (lessons.style.display === 'none') {
            lessons.style.display = 'block';
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        } else {
            lessons.style.display = 'none';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        }
    }

    // Auto-expand the module containing the current lesson
    document.addEventListener('DOMContentLoaded', function() {
        const activeLesson = document.querySelector('.lesson-item.active');
        if (activeLesson) {
            const moduleGroup = activeLesson.closest('.module-group');
            if (moduleGroup) {
                const moduleHeader = moduleGroup.querySelector('.module-header');
                if (moduleHeader && moduleHeader.getAttribute('onclick')) {
                    const moduleId = moduleHeader.getAttribute('onclick').match(/\d+/)[0];
                    const lessons = document.getElementById('lessons-' + moduleId);
                    const icon = document.getElementById('icon-' + moduleId);
                    
                    if (lessons && icon) {
                        lessons.style.display = 'block';
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-down');
                    }
                }
            }
        }
    });
</script>
@endpush
