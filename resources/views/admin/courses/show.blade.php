@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3">{{ $course->title }}</h1>
            <p class="text-muted">
                @if($course->status === 'published')
                    <span class="badge bg-success">Publié</span>
                @else
                    <span class="badge bg-secondary">Brouillon</span>
                @endif
                <span class="ms-2">
                    <i class="fas fa-calendar-alt me-1"></i> Créé le {{ $course->created_at->format('d/m/Y') }}
                </span>
            </p>
        </div>
        <div>
            <a href="{{ route('courses.show', $course->slug) }}" target="_blank" class="btn btn-outline-primary me-2">
                <i class="fas fa-eye me-1"></i> Voir sur le site
            </a>
            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Modifier
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Course Details -->
        <div class="col-md-8">
            <div class="dashboard-card mb-4">
                <div class="row mb-4">
                    <div class="col-md-4">
                        @if($course->cover_image_path)
                            <img src="{{ asset('storage/' . $course->cover_image_path) }}" alt="{{ $course->title }}" class="img-fluid rounded">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 180px;">
                                <i class="fas fa-book fa-4x text-secondary"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h5>Informations générales</h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">Formateur</th>
                                    <td>
                                        <a href="{{ route('admin.users.show', $course->formateur) }}">
                                            {{ $course->formateur->first_name }} {{ $course->formateur->last_name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Catégorie</th>
                                    <td>{{ $course->category->name ?? 'Non catégorisé' }}</td>
                                </tr>
                                <tr>
                                    <th>Prix</th>
                                    <td>
                                        <span class="badge bg-{{ $course->price > 0 ? 'success' : 'warning' }}">
                                            {{ $course->price > 0 ? number_format($course->price, 0, ',', ' ') . ' XOF' : 'Gratuit' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Niveau</th>
                                    <td>
                                        @if($course->level === 'beginner')
                                            <span class="badge bg-info">Débutant</span>
                                        @elseif($course->level === 'intermediate')
                                            <span class="badge bg-primary">Intermédiaire</span>
                                        @elseif($course->level === 'advanced')
                                            <span class="badge bg-danger">Avancé</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Durée</th>
                                    <td>{{ $course->duration ?? 'Non spécifiée' }}</td>
                                </tr>
                            </tbody>
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
