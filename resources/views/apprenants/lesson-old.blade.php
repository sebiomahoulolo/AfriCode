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
    }

    .lesson-sidebar {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        overflow-y: auto;
        position: sticky;
        top: 0;
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
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        border-radius: var(--border-radius-sm);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .module-header:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .module-lessons {
        padding: 0.5rem 0;
    }

    .lesson-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        margin: 0.25rem 0;
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: var(--gray-700);
        border: 1px solid transparent;
    }

    .lesson-item:hover {
        background: var(--gray-100);
        color: var(--primary-color);
        transform: translateX(4px);
    }

    .lesson-item.active {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        color: var(--primary-color);
        border-color: var(--primary-color);
        font-weight: 600;
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
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
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

    @media (max-width: 1024px) {
        .lesson-container {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .lesson-sidebar {
            order: 2;
            position: static;
            max-height: 300px;
        }

        .lesson-content {
            order: 1;
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
        
        .sidebar .logo {
            color: white;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 2rem;
            padding-left: 15px;
        }
        
        .main-content {
            padding: 2rem;
            flex: 1;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        
        .badge-custom {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: normal;
            font-size: 0.8rem;
        }
        
        .badge-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .badge-warning {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .lesson-sidebar {
            height: calc(100vh - 100px);
            overflow-y: auto;
            padding-right: 10px;
        }
        
        .module-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .lesson-item {
            padding: 8px 15px 8px 25px;
            margin-bottom: 3px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .lesson-item:hover {
            background-color: #f0f0f0;
        }
        
        .lesson-item.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .lesson-item.completed {
            border-left: 3px solid #28a745;
        }
        
        .lesson-content {
            min-height: 500px;
        }
        
        .lesson-controls {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        
        .resource-item {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        
        .resource-icon {
            font-size: 1.5rem;
            margin-right: 15px;
            color: var(--primary-color);
        }
        
        .toggle-sidebar {
            margin-bottom: 20px;
            color: white;
            background: none;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 */
            height: 0;
            overflow: hidden;
            max-width: 100%;
            margin-bottom: 20px;
        }
        
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                z-index: 999;
                width: 100%;
                max-width: 100%;
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="d-flex justify-content-between align-items-center mb-4 px-3">
                <div class="logo">
                    <i class="fas fa-code me-2"></i> AfriCode
                </div>
                <button class="toggle-sidebar d-none d-lg-block" id="toggleSidebar">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            
            <ul class="nav flex-column mb-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('apprenant.dashboard') }}">
                        <i class="fas fa-home me-2"></i> Tableau de bord
                    </a>
                </li>
            </ul>
            
            <div class="lesson-sidebar px-3">
                <h6 class="text-white mb-3">{{ $lesson->module->course->title }}</h6>
                
                <div class="d-flex justify-content-between mb-2 text-white">
                    <small>Progression totale</small>
                    <small>{{ count($completedLessons) }} / {{ $lesson->module->course->getLessonsCount() }}</small>
                </div>
                <div class="progress mb-4">
                    <div class="progress-bar bg-light" role="progressbar" 
                         style="width: {{ count($completedLessons) / $lesson->module->course->getLessonsCount() * 100 }}%" 
                         aria-valuenow="{{ count($completedLessons) / $lesson->module->course->getLessonsCount() * 100 }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100"></div>
                </div>
                
                <!-- Modules et leçons -->
                @foreach($modules as $module)
                <div class="module-group mb-3">
                    <div class="module-header d-flex justify-content-between">
                        <span>{{ $module->title }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="module-lessons">
                        @foreach($module->lessons as $lessonItem)
                        <a href="{{ route('apprenant.lesson', ['lessonId' => $lessonItem->id]) }}" 
                           class="lesson-item d-flex justify-content-between align-items-center {{ $lessonItem->id === $lesson->id ? 'active' : '' }} {{ in_array($lessonItem->id, $completedLessons) ? 'completed' : '' }}">
                            <div>
                                <i class="fas {{ in_array($lessonItem->id, $completedLessons) ? 'fa-check-circle' : 'fa-circle' }} me-2"></i>
                                <span>{{ $lessonItem->title }}</span>
                            </div>
                            <small>{{ $lessonItem->duration_minutes }} min</small>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Main content -->
        <main class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">{{ $lesson->module->title }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('apprenant.dashboard') }}">Tableau de bord</a></li>
                            <li class="breadcrumb-item">{{ $lesson->module->course->title }}</li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $lesson->title }}</li>
                        </ol>
                    </nav>
                </div>
                <button class="btn btn-sm d-lg-none" id="toggleMobileSidebar">
                    <i class="fas fa-bars"></i> Menu
                </button>
            </div>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $lesson->title }}</h5>
                    <span class="badge badge-custom {{ $isCompleted ? 'bg-success' : 'badge-primary' }}">
                        {{ $isCompleted ? 'Complété' : 'En cours' }}
                    </span>
                </div>
                <div class="card-body lesson-content">
                    @if($lesson->content_type === 'video' && $lesson->video_url)
                        <div class="video-container mb-4">
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
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i> La vidéo n'est pas disponible
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
                        <div class="pdf-viewer mt-3">
                            <div class="mb-3">
                                <a href="{{ asset($lesson->pdf_path) }}" class="btn btn-outline-primary" target="_blank">
                                    <i class="fas fa-file-pdf me-2"></i> Ouvrir le PDF dans un nouvel onglet
                                </a>
                            </div>
                            <embed src="{{ asset($lesson->pdf_path) }}" type="application/pdf" width="100%" height="600px" />
                        </div>
                    @endif
                    
                    @if($lesson->content_type === 'external' && $lesson->external_url)
                        <div class="text-center my-4">
                            <p class="mb-3">Cette leçon est hébergée sur une plateforme externe.</p>
                            <a href="{{ $lesson->external_url }}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i> Accéder au contenu externe
                            </a>
                        </div>
                    @endif
                    
                    @if($resources->isNotEmpty())
                        <div class="resources-section mt-4">
                            <h5>Ressources supplémentaires</h5>
                            <div class="resources-list">
                                @foreach($resources as $resource)
                                <div class="resource-item">
                                    @switch($resource->type)
                                        @case('pdf')
                                            <i class="fas fa-file-pdf resource-icon"></i>
                                            @break
                                        @case('archive')
                                            <i class="fas fa-file-archive resource-icon"></i>
                                            @break
                                        @case('image')
                                            <i class="fas fa-file-image resource-icon"></i>
                                            @break
                                        @case('code')
                                            <i class="fas fa-file-code resource-icon"></i>
                                            @break
                                        @default
                                            <i class="fas fa-file resource-icon"></i>
                                    @endswitch
                                    <div>
                                        <strong>{{ $resource->title }}</strong>
                                        <p class="mb-0 small">{{ $resource->description }}</p>
                                        <a href="{{ asset($resource->file_path) }}" class="btn btn-sm btn-link p-0" target="_blank">Télécharger</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white lesson-controls">
                    @if(!$isCompleted)
                    <form action="{{ route('apprenant.lesson.complete', ['lessonId' => $lesson->id]) }}" method="POST">
                        @csrf
                        @if($nextLesson)
                        <input type="hidden" name="next_lesson_id" value="{{ $nextLesson->id }}">
                        @endif
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i> Marquer comme terminée
                        </button>
                    </form>
                    @else
                    <div>
                        <span class="text-success"><i class="fas fa-check-circle me-1"></i> Leçon terminée</span>
                    </div>
                    @endif
                    
                    <div>
                        @if($nextLesson)
                        <a href="{{ route('apprenant.lesson', ['lessonId' => $nextLesson->id]) }}" class="btn btn-primary">
                            Leçon suivante <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        @else
                        <a href="{{ route('apprenant.dashboard') }}" class="btn btn-outline-primary">
                            Retour au tableau de bord
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
        </main>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gestion du toggle de la sidebar (version desktop)
        const toggleSidebarBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        
        if (toggleSidebarBtn) {
            toggleSidebarBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                
                // Changer l'icône du bouton
                const icon = this.querySelector('i');
                if (sidebar.classList.contains('collapsed')) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                } else {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-left');
                }
            });
        }
        
        // Gestion du toggle de la sidebar (version mobile)
        const toggleMobileSidebarBtn = document.getElementById('toggleMobileSidebar');
        
        if (toggleMobileSidebarBtn) {
            toggleMobileSidebarBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }
        
        // Toggle pour les modules
        const moduleHeaders = document.querySelectorAll('.module-header');
        
        moduleHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const moduleGroup = this.parentElement;
                const moduleLessons = moduleGroup.querySelector('.module-lessons');
                const icon = this.querySelector('.fas');
                
                // Toggle la visibilité des leçons
                if (moduleLessons.style.display === 'none') {
                    moduleLessons.style.display = 'block';
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-down');
                } else {
                    moduleLessons.style.display = 'none';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-right');
                }
            });
        });
        
        // Assurez-vous que le module de la leçon actuelle est ouvert
        const activeLesson = document.querySelector('.lesson-item.active');
        if (activeLesson) {
            const parentModule = activeLesson.closest('.module-group');
            if (parentModule) {
                parentModule.querySelector('.module-lessons').style.display = 'block';
                const icon = parentModule.querySelector('.module-header .fas');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            }
        }
    });
    </script>
</body>
</html>
