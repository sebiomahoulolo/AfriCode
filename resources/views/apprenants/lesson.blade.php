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
        height: calc(100vh - 120px);
        transition: grid-template-columns 0.3s ease;
    }

    .lesson-container.sidebar-hidden {
        grid-template-columns: 0 1fr;
    }

    .lesson-sidebar {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        overflow-y: auto;
        transition: all 0.3s ease;
    }

    .lesson-sidebar.hidden {
        transform: translateX(-100%);
        opacity: 0;
        pointer-events: none;
    }

    .sidebar-toggle {
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        color: white !important;
        border: 2px solid white !important;
        border-radius: 8px !important;
        width: 45px !important;
        height: 45px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1.1rem !important;
        box-shadow: 0 4px 15px rgba(30, 163, 139, 0.3) !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        opacity: 1 !important;
        visibility: visible !important;
        z-index: 1000 !important;
    }

    .sidebar-toggle:hover {
        transform: scale(1.1) !important;
        box-shadow: 0 6px 20px rgba(30, 163, 139, 0.4) !important;
        background: linear-gradient(135deg, #27B371 0%, #1EA38B 100%) !important;
    }

    .sidebar-toggle:hover::after {
        opacity: 1 !important;
        transform: translateY(-50%) scale(1) !important;
    }

    .sidebar-toggle::after {
        content: attr(data-tooltip) !important;
        position: absolute !important;
        left: 55px !important;
        top: 50% !important;
        transform: translateY(-50%) scale(0.8) !important;
        background: var(--gray-800) !important;
        color: white !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 6px !important;
        font-size: 0.75rem !important;
        white-space: nowrap !important;
        opacity: 0 !important;
        pointer-events: none !important;
        transition: all 0.3s ease !important;
        z-index: 1001 !important;
    }

    .sidebar-toggle::before {
        content: '';
        position: absolute;
        left: 42px;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-right-color: var(--gray-800);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1000;
    }

    .sidebar-toggle:hover::before {
        opacity: 1;
    }

    .lesson-header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .lesson-title-section {
        flex: 1;
    }

    .lesson-controls {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        margin-top: 0.25rem;
    }

    .sidebar-control-hint {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
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
        padding: 1rem 2rem;
        border-bottom: 1px solid var(--gray-200);
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    }

    .lesson-body {
        flex: 1;
        padding: 1.5rem 2rem;
        overflow-y: auto;
        max-height: calc(100vh - 220px);
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
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        color: white !important;
        padding: 1rem 1.25rem !important;
        border-radius: var(--africode-border-radius-sm) !important;
        cursor: pointer !important;
        transition: var(--africode-transition) !important;
        font-weight: 600 !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 0.5rem !important;
        box-shadow: var(--africode-shadow-sm) !important;
        border: 1px solid rgba(30, 163, 139, 0.3) !important;
    }

    .module-header:hover {
        background: linear-gradient(135deg, #17896E 0%, #229A63 100%) !important;
        transform: translateY(-2px) !important;
        box-shadow: var(--africode-shadow-md) !important;
    }

    .module-lessons {
        padding: 0.75rem 0 !important;
        background: rgba(30, 163, 139, 0.02) !important;
        border-radius: 0 0 var(--africode-border-radius-sm) var(--africode-border-radius-sm) !important;
        margin-bottom: 1rem !important;
        border: 1px solid rgba(30, 163, 139, 0.1) !important;
        border-top: none !important;
    }

    .lesson-item {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0.875rem 1.25rem !important;
        margin: 0.25rem 0.5rem !important;
        border-radius: var(--africode-border-radius-sm) !important;
        transition: var(--africode-transition) !important;
        cursor: pointer !important;
        text-decoration: none !important;
        color: var(--africode-text-primary) !important;
        border: 1px solid transparent !important;
        background: white !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
    }

    .lesson-item:hover {
        background: rgba(30, 163, 139, 0.08) !important;
        color: #1EA38B !important;
        transform: translateX(4px) !important;
        text-decoration: none !important;
        box-shadow: var(--africode-shadow-sm) !important;
        border-color: rgba(30, 163, 139, 0.2) !important;
    }

    .lesson-item.disabled {
        cursor: not-allowed !important;
        background-color: var(--gray-100) !important;
        color: var(--gray-500) !important;
        opacity: 0.8 !important;
        pointer-events: none !important;
    }
    .lesson-item.disabled:hover {
        background: var(--gray-100) !important;
        transform: none !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        border-color: transparent !important;
    }

    .lesson-item.active {
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.15) 0%, rgba(39, 179, 113, 0.15) 100%) !important;
        color: #1EA38B !important;
        border-color: #1EA38B !important;
        font-weight: 600 !important;
        box-shadow: var(--africode-shadow-md) !important;
        border-left: 4px solid #1EA38B !important;
    }

    .lesson-item.completed {
        color: #27B371 !important;
        border-left: 3px solid #27B371 !important;
    }

    .lesson-item.attempted {
        color: #f0ad4e !important;
        border-left: 3px solid #f0ad4e !important;
    }

    .quiz-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        font-size: 0.6rem !important;
        padding: 2px 6px !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
    }

    .quiz-badges {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin-left: auto;
    }

    .certification-badge {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
        color: white !important;
        font-size: 0.65rem !important;
        padding: 3px 6px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        animation: pulse-golden 2s infinite;
    }

    .required-quiz {
        border-left: 3px solid #f39c12 !important;
    }

    .required-quiz.completed {
        border-left: 3px solid #27B371 !important;
    }

    .required-indicator {
        color: #f39c12 !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
    }

    @keyframes pulse-golden {
        0%, 100% {
            box-shadow: 0 0 5px rgba(243, 156, 18, 0.3);
        }
        50% {
            box-shadow: 0 0 15px rgba(243, 156, 18, 0.6);
        }
    }

    .lesson-icon {
        width: 24px;
        margin-right: 0.75rem;
        text-align: center;
    }

    .video-container {
        position: relative;
        width: 100%;
        height: 60vh;
        min-height: 450px;
        max-height: 600px;
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
        gap: 1.5rem;
        margin-bottom: 0;
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
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(102, 126, 234, 0.2);
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
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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

    /* Video responsive styles */
    .video-wrapper {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        margin-bottom: 2rem;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .video-container-large {
        position: relative;
        width: 100%;
        height: 75vh;
        min-height: 550px;
        background: var(--gray-900);
        border-radius: var(--border-radius-sm);
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .video-container-large iframe {
        width: 100%;
        height: 100%;
        border: none;
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
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            height: 100vh;
            z-index: 999;
            transform: translateX(-100%);
        }

        .lesson-sidebar.visible {
            transform: translateX(0);
        }

        .lesson-content {
            order: 1;
        }

        .sidebar-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
        }

        .sidebar-toggle::after {
            content: attr(data-tooltip);
            position: absolute;
            right: 65px;
            top: 50%;
            transform: translateY(-50%) scale(0.8);
            background: var(--gray-800);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar-toggle::before {
            content: '';
            position: absolute;
            right: 57px;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-left-color: var(--gray-800);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        .sidebar-toggle:hover::after,
        .sidebar-toggle:hover::before {
            opacity: 1;
        }

        .lesson-header-top {
            margin-bottom: 1rem;
        }

        .lesson-controls {
            display: none;
        }

        .sidebar-control-hint {
            display: none;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.visible {
            opacity: 1;
            pointer-events: auto;
        }

        .lesson-content {
            margin-top: 0;
        }
    }

    @media (max-width: 768px) {
        .lesson-header,
        .lesson-body,
        .lesson-footer {
            padding: 1rem;
        }

        .lesson-content {
            margin-top: 0;
        }

        .video-wrapper {
            padding-bottom: 56.25%; /* Maintenir le ratio 16:9 sur mobile */
        }

        .video-container-large {
            height: 50vh;
            min-height: 350px;
        }

        .lesson-body {
            max-height: calc(100vh - 160px);
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
            <small class="fw-bold">{{ $courseStructure['stats']['completed_lessons'] }} / {{ $courseStructure['stats']['total_lessons'] }} leçons</small>
        </div>
        
        <div class="progress-modern">
            <div class="progress-bar-modern" 
                 style="width: {{ $courseStructure['stats']['progress_percentage'] }}%"></div>
        </div>
    </div>

    <!-- Main Lesson Container -->
    <div class="lesson-container" id="lessonContainer" data-aos="fade-up" data-aos-delay="100">
        <!-- Sidebar Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <!-- Sidebar with Course Structure -->
        <div class="lesson-sidebar" id="lessonSidebar">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 text-primary fw-bold">
                    <i class="fas fa-list me-2"></i>
                    Structure du cours
                </h6>
                <button class="btn btn-sm btn-outline-primary d-lg-none" onclick="closeSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            @foreach($courseStructure['modules'] as $moduleData)
            <div class="module-group mb-3">
                <div class="module-header" onclick="toggleModule({{ $moduleData['module']->id }})">
                    <span>{{ $moduleData['module']->title }}</span>
                    <i class="fas fa-chevron-down" id="icon-{{ $moduleData['module']->id }}"></i>
                </div>
                <div class="module-lessons" id="lessons-{{ $moduleData['module']->id }}">
                    {{-- Afficher les leçons du module --}}
                    @foreach($moduleData['lessons'] as $lessonData)
                        @php
                            $l = $lessonData['item'];
                            $isLocked = $lessonData['status'] === 'locked';
                            $url = $isLocked ? '#' : route('apprenant.lesson', ['lessonId' => $l->id]);
                            $class = 'lesson-item';
                            if ($l->id === $lesson->id) $class .= ' active';
                            if ($lessonData['is_completed']) $class .= ' completed';
                            if ($isLocked) $class .= ' disabled'; // CSS `disabled` class might be needed
                        @endphp
                        <a href="{{ $url }}" class="{{ $class }}">
                            <div class="d-flex align-items-center">
                                <i class="lesson-icon fas {{ $isLocked ? 'fa-lock text-muted' : ($lessonData['is_completed'] ? 'fa-check-circle text-success' : ($l->id === $lesson->id ? 'fa-play-circle' : 'fa-circle')) }}"></i>
                                <span>{{ $l->title }}</span>
                            </div>
                            @if(!$isLocked)
                                <small class="text-muted">{{ $l->duration_minutes }} min</small>
                            @endif
                        </a>
                    @endforeach

                    {{-- Afficher le quiz du module s'il existe --}}
                    @if($moduleData['quiz'])
                        @php
                            $q = $moduleData['quiz']['item'];
                            $isLocked = $moduleData['quiz']['status'] === 'locked';
                            $url = $isLocked ? '#' : route('apprenant.quiz.show', ['quizId' => $q->id]);
                            $class = 'lesson-item quiz-item';
                            if ($moduleData['quiz']['is_completed']) $class .= ' completed';
                            if ($isLocked) $class .= ' disabled';
                            if ($q->is_required) $class .= ' required-quiz';
                        @endphp
                        <a href="{{ $url }}" class="{{ $class }}">
                            <div class="d-flex align-items-center">
                                <i class="lesson-icon fas {{ $isLocked ? 'fa-lock text-muted' : ($moduleData['quiz']['is_completed'] ? 'fa-check-circle text-success' : 'fa-question-circle text-primary') }}"></i>
                                <span>{{ $q->title }}</span>
                                <div class="quiz-badges">
                                    <span class="quiz-badge">QUIZ</span>
                                    @if($q->is_required)
                                        <span class="certification-badge" title="Obligatoire pour le certificat"><i class="fas fa-star"></i></span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            @endforeach

            {{-- Afficher le quiz final s'il existe --}}
            @if($courseStructure['final_quiz'])
                @php
                    $q = $courseStructure['final_quiz']['item'];
                    $isLocked = $courseStructure['final_quiz']['status'] === 'locked';
                    $url = $isLocked ? '#' : route('apprenant.quiz.show', ['quizId' => $q->id]);
                    $class = 'lesson-item quiz-item final-quiz'; // a new class for styling if needed
                    if ($courseStructure['final_quiz']['is_completed']) $class .= ' completed';
                    if ($isLocked) $class .= ' disabled';
                @endphp
                 <div class="module-group mb-3">
                    <div class="module-header">
                        <span>Examen Final</span>
                    </div>
                    <div class="module-lessons">
                        <a href="{{ $url }}" class="{{ $class }}">
                            <div class="d-flex align-items-center">
                                 <i class="lesson-icon fas {{ $isLocked ? 'fa-lock text-muted' : ($courseStructure['final_quiz']['is_completed'] ? 'fa-award text-success' : 'fa-flag-checkered text-primary') }}"></i>
                                <span>{{ $q->title }}</span>
                                <div class="quiz-badges">
                                    <span class="quiz-badge" style="background: linear-gradient(135deg, #d35400 0%, #e67e22 100%) !important;">EXAMEN</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            {{-- Section Certificat --}}
            @if($course->is_certifying)
                <div class="module-group mb-3">
                    <div class="module-header" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important; color: white;">
                        <span><i class="fas fa-certificate me-2"></i>Certificat</span>
                    </div>
                    <div class="module-lessons">
                        @if($certification)
                            <div class="p-3 text-center">
                                <i class="fas fa-award fa-2x text-warning mb-2"></i>
                                <div class="fw-bold mb-2">Certificat obtenu !</div>
                                <a href="{{ route('apprenant.certification.download', $certification->id) }}" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-file-pdf me-2"></i>Voir / Télécharger
                                </a>
                                <div class="small text-muted">Délivré le {{ $certification->issued_at->format('d/m/Y') }}</div>
                                <div class="small mt-1">ID : <span class="fw-bold">{{ $certification->certificate_identifier }}</span></div>
                            </div>
                        @else
                            <div class="p-3 text-center">
                                <i class="fas fa-lock fa-2x text-muted mb-2"></i>
                                <div class="fw-bold mb-2">Certificat verrouillé</div>
                                <div class="small text-muted">Complétez toutes les étapes et réussissez l'examen final pour débloquer votre certificat.</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <!-- Main Content -->
        <div class="lesson-content">
            <!-- Sidebar Toggle Button for mobile -->
        

            <div class="lesson-header">
                <div class="lesson-header-top">
                    <div class="lesson-title-section">
                        <h1 class="h3 mb-0">{{ $lesson->title }}</h1>
                        @if($lesson->description)
                            <p class="text-muted mt-2 mb-0">{{ $lesson->description }}</p>
                        @endif
                    </div>
                    <div class="lesson-controls d-none d-lg-flex">
                        <div class="text-end">
                            <button class="sidebar-toggle" id="sidebarToggleDesktop" onclick="toggleSidebar()" 
                                    data-tooltip="Masquer la structure du cours">
                                <i class="fas fa-eye-slash" id="toggleIconDesktop"></i>
                            </button>
                            <div class="sidebar-control-hint">
                                <i class="fas fa-info-circle"></i>
                                <span id="sidebarControlText">Masquer le menu</span>
                            </div>
                        </div>
                    </div>
                </div>
                
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
            </div>

            <div class="lesson-body">
                @if($lesson->content_type === 'video' && $lesson->video_url)
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
                        <div class="video-container-large">
                            <iframe 
                                src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    @else
                        <div class="video-container">
                            <div class="video-placeholder">
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle mb-3"></i>
                                    <p class="mb-0">Vidéo non disponible</p>
                                </div>
                            </div>
                        </div>
                    @endif
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
                    @if(isset(
                        $previousLesson
                    ))
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
                            <button type="submit" class="btn btn-modern">
                                <i class="fas fa-check-circle me-2"></i>Marquer terminée
                            </button>
                        </form>
                        @endif
                        
                        {{-- Blocage navigation module suivant --}}
                        @if($isLastLessonOfModule && $moduleQuiz)
                            @if(!$moduleQuizPassed)
                                <a href="{{ route('apprenant.quiz.show', ['quizId' => $moduleQuiz->id]) }}" class="btn btn-modern btn-nav">
                                    <i class="fas fa-question-circle me-2"></i>Passer le quiz du module
                                </a>
                            @else
                                @if($firstLessonNextModule)
                                    <a href="{{ route('apprenant.lesson', ['lessonId' => $firstLessonNextModule->id]) }}" class="btn btn-modern btn-nav">
                                        Module suivant <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                @else
                                    <a href="{{ route('apprenant.dashboard') }}" class="btn btn-outline-modern btn-nav">
                                        <i class="fas fa-home me-2"></i>Tableau de bord
                                    </a>
                                @endif
                            @endif
                        @else
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
        const container = document.getElementById('lessonContainer');
        const sidebar = document.getElementById('lessonSidebar');
        const toggleIconDesktop = document.getElementById('toggleIconDesktop');
        const toggleIcon = document.getElementById('toggleIcon');
        const overlay = document.getElementById('sidebarOverlay');
        const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
        const sidebarControlText = document.getElementById('sidebarControlText');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        sidebarVisible = !sidebarVisible;
        
        if (sidebarVisible) {
            // Show sidebar
            container.classList.remove('sidebar-hidden');
            sidebar.classList.remove('hidden');
            
            if (toggleIconDesktop && sidebarToggleDesktop && sidebarControlText) {
                toggleIconDesktop.classList.remove('fa-eye');
                toggleIconDesktop.classList.add('fa-eye-slash');
                sidebarToggleDesktop.setAttribute('data-tooltip', 'Masquer la structure du cours');
                sidebarControlText.textContent = 'Masquer le menu';
            }
            
            // Mobile specific
            if (window.innerWidth <= 1024) {
                sidebar.classList.add('visible');
                overlay.classList.add('visible');
                if (toggleIcon && sidebarToggle) {
                    toggleIcon.classList.remove('fa-times');
                    toggleIcon.classList.add('fa-list');
                    sidebarToggle.setAttribute('data-tooltip', 'Voir la structure du cours');
                }
            }
        } else {
            // Hide sidebar
            container.classList.add('sidebar-hidden');
            sidebar.classList.add('hidden');
            
            if (toggleIconDesktop && sidebarToggleDesktop && sidebarControlText) {
                toggleIconDesktop.classList.remove('fa-eye-slash');
                toggleIconDesktop.classList.add('fa-eye');
                sidebarToggleDesktop.setAttribute('data-tooltip', 'Afficher la structure du cours');
                sidebarControlText.textContent = 'Afficher le menu';
            }
            
            // Mobile specific
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('visible');
                overlay.classList.remove('visible');
                if (toggleIcon && sidebarToggle) {
                    toggleIcon.classList.remove('fa-list');
                    toggleIcon.classList.add('fa-times');
                    sidebarToggle.setAttribute('data-tooltip', 'Fermer la structure du cours');
                }
            }
        }
    }

    function closeSidebar() {
        if (window.innerWidth <= 1024 && sidebarVisible) {
            toggleSidebar();
        }
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('lessonSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (window.innerWidth > 1024) {
            // Desktop mode
            overlay.classList.remove('visible');
            if (sidebarVisible) {
                sidebar.classList.remove('visible');
            }
        } else {
            // Mobile mode
            if (sidebarVisible) {
                sidebar.classList.add('visible');
                overlay.classList.add('visible');
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
            const moduleId = activeLesson.closest('.module-group').querySelector('.module-header').getAttribute('onclick').match(/\d+/)[0];
            const lessons = document.getElementById('lessons-' + moduleId);
            const icon = document.getElementById('icon-' + moduleId);
            
            lessons.style.display = 'block';
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        }

        // Initialize sidebar state for mobile
        if (window.innerWidth <= 1024) {
            const container = document.getElementById('lessonContainer');
            const sidebar = document.getElementById('lessonSidebar');
            container.classList.add('sidebar-hidden');
            sidebar.classList.add('hidden');
            sidebarVisible = false;
            
            const toggleIcon = document.getElementById('toggleIcon');
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (toggleIcon && sidebarToggle) {
                toggleIcon.classList.remove('fa-list');
                toggleIcon.classList.add('fa-times');
                sidebarToggle.setAttribute('data-tooltip', 'Fermer la structure du cours');
            }
        }
    });
</script>
@endpush
