@extends('admin.layouts.app')

@section('title', 'Modifier le Module')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit fa-fw me-1"></i> Modifier le module
            </h6>
            <div>
                <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left fa-fw"></i> Retour au cours
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.modules.update', $module->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Titre du module <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $module->title) }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $module->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="order" class="form-label">Ordre</label>
                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $module->order) }}" min="1">
                    <div class="form-text">Position du module dans la liste des modules du cours</div>
                    @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="d-flex mt-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save fa-fw"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-secondary">Annuler</a>
                    
                    <button type="button" class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#deleteModuleModal">
                        <i class="fas fa-trash fa-fw"></i> Supprimer le module
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Contenu du module -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Leçons</h6>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                        <i class="fas fa-plus fa-fw"></i> Ajouter une leçon
                    </button>
                </div>
                <div class="card-body">
                    @if($module->lessons->count() > 0)
                        <div class="list-group">
                            @foreach($module->lessons->sortBy('order') as $lesson)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-secondary me-2">{{ $lesson->order }}</span>
                                        {{ $lesson->title }}
                                        <small class="text-muted ms-2">
                                            @if($lesson->type === 'video')
                                                <i class="fas fa-video me-1"></i> Vidéo
                                            @elseif($lesson->type === 'pdf')
                                                <i class="fas fa-file-pdf me-1"></i> PDF
                                            @elseif($lesson->type === 'text')
                                                <i class="fas fa-file-alt me-1"></i> Texte
                                            @endif
                                        </small>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger lesson-delete-btn" 
                                                data-lesson-id="{{ $lesson->id }}" 
                                                data-lesson-title="{{ $lesson->title }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            Ce module n'a pas encore de leçons. Ajoutez-en une en cliquant sur le bouton ci-dessus.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Quiz</h6>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addQuizModal">
                        <i class="fas fa-plus fa-fw"></i> Ajouter un quiz
                    </button>
                </div>
                <div class="card-body">
                    @if($module->quizzes->count() > 0)
                        <div class="list-group">
                            @foreach($module->quizzes as $quiz)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-question-circle text-warning me-2"></i>
                                        {{ $quiz->title }}
                                        <span class="badge bg-info ms-2">{{ $quiz->questions->count() }} question(s)</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger quiz-delete-btn" 
                                                data-quiz-id="{{ $quiz->id }}" 
                                                data-quiz-title="{{ $quiz->title }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            Ce module n'a pas encore de quiz. Ajoutez-en un en cliquant sur le bouton ci-dessus.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression du module -->
<div class="modal fade" id="deleteModuleModal" tabindex="-1" aria-labelledby="deleteModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModuleModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce module et tout son contenu (leçons et quiz) ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.modules.destroy', $module->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une leçon -->
<div class="modal fade" id="addLessonModal" tabindex="-1" aria-labelledby="addLessonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLessonModalLabel">Ajouter une leçon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route('admin.lessons.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="lesson-title" class="form-label">Titre de la leçon</label>
                        <input type="text" class="form-control" id="lesson-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="lesson-description" class="form-label">Description</label>
                        <textarea class="form-control" id="lesson-description" name="description" rows="2"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="lesson-type" class="form-label">Type de contenu</label>
                            <select class="form-select" id="lesson-type" name="type" required>
                                <option value="video">Vidéo</option>
                                <option value="text">Texte</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="lesson-order" class="form-label">Ordre</label>
                            <input type="number" class="form-control" id="lesson-order" name="order" value="{{ $module->lessons->count() + 1 }}" min="1">
                        </div>
                    </div>
                    <div class="mb-3" id="video-content">
                        <label for="lesson-video-url" class="form-label">URL de la vidéo (YouTube ou Vimeo)</label>
                        <input type="url" class="form-control" id="lesson-video-url" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
                        <div class="form-text">Collez le lien direct vers la vidéo YouTube ou Vimeo</div>
                    </div>
                    <div class="mb-3 d-none" id="text-content">
                        <label for="lesson-text-content" class="form-label">Contenu texte (supporte le Markdown)</label>
                        <textarea class="form-control" id="lesson-text-content" name="text_content" rows="6"></textarea>
                    </div>
                    <div class="mb-3 d-none" id="pdf-content">
                        <label for="lesson-pdf" class="form-label">Fichier PDF</label>
                        <input type="file" class="form-control" id="lesson-pdf" name="pdf_file" accept="application/pdf">
                    </div>
                    <div class="mb-3">
                        <label for="lesson-duration" class="form-label">Durée estimée (minutes)</label>
                        <input type="number" class="form-control" id="lesson-duration" name="duration" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter la leçon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un quiz -->
<div class="modal fade" id="addQuizModal" tabindex="-1" aria-labelledby="addQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuizModalLabel">Ajouter un quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route('admin.quizzes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">
                <input type="hidden" name="related_type" value="App\Models\Module">
                <input type="hidden" name="related_id" value="{{ $module->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quiz-title" class="form-label">Titre du quiz</label>
                        <input type="text" class="form-control" id="quiz-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="quiz-description" class="form-label">Description</label>
                        <textarea class="form-control" id="quiz-description" name="description" rows="2"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="quiz-passing-score" class="form-label">Score de réussite (%)</label>
                            <input type="number" class="form-control" id="quiz-passing-score" name="passing_score" value="70" min="0" max="100">
                        </div>
                        <div class="col-md-6">
                            <label for="quiz-time-limit" class="form-label">Limite de temps (minutes)</label>
                            <input type="number" class="form-control" id="quiz-time-limit" name="time_limit" min="0">
                            <div class="form-text">Laisser vide pour aucune limite de temps</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="quiz-is-required" name="is_required" value="1" checked>
                            <label class="form-check-label" for="quiz-is-required">
                                Obligatoire pour compléter le module
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        Après avoir créé le quiz, vous pourrez y ajouter des questions dans l'écran d'édition.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer le quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de suppression de leçon (template) -->
<div class="modal fade" id="deleteLessonModal" tabindex="-1" aria-labelledby="deleteLessonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLessonModalLabel">Supprimer la leçon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la leçon <strong id="lesson-title-to-delete"></strong> ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="delete-lesson-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression de quiz (template) -->
<div class="modal fade" id="deleteQuizModal" tabindex="-1" aria-labelledby="deleteQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteQuizModalLabel">Supprimer le quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le quiz <strong id="quiz-title-to-delete"></strong> ?</p>
                <p class="text-danger">Toutes les questions associées seront également supprimées. Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="delete-quiz-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'affichage des champs selon le type de leçon
        const lessonType = document.getElementById('lesson-type');
        
        if (lessonType) {
            lessonType.addEventListener('change', function() {
                const videoContent = document.getElementById('video-content');
                const textContent = document.getElementById('text-content');
                const pdfContent = document.getElementById('pdf-content');
                
                // Cacher tous les contenus
                videoContent.classList.add('d-none');
                textContent.classList.add('d-none');
                pdfContent.classList.add('d-none');
                
                // Afficher le contenu correspondant au type sélectionné
                if (this.value === 'video') {
                    videoContent.classList.remove('d-none');
                } else if (this.value === 'text') {
                    textContent.classList.remove('d-none');
                } else if (this.value === 'pdf') {
                    pdfContent.classList.remove('d-none');
                }
            });
        }
        
        // Gestion des modals de suppression de leçon
        document.querySelectorAll('.lesson-delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const lessonId = this.getAttribute('data-lesson-id');
                const lessonTitle = this.getAttribute('data-lesson-title');
                
                document.getElementById('lesson-title-to-delete').textContent = lessonTitle;
                document.getElementById('delete-lesson-form').action = `/admin/lessons/${lessonId}`;
                
                new bootstrap.Modal(document.getElementById('deleteLessonModal')).show();
            });
        });
        
        // Gestion des modals de suppression de quiz
        document.querySelectorAll('.quiz-delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const quizId = this.getAttribute('data-quiz-id');
                const quizTitle = this.getAttribute('data-quiz-title');
                
                document.getElementById('quiz-title-to-delete').textContent = quizTitle;
                document.getElementById('delete-quiz-form').action = `/admin/quizzes/${quizId}`;
                
                new bootstrap.Modal(document.getElementById('deleteQuizModal')).show();
            });
        });
    });
</script>
@endsection
