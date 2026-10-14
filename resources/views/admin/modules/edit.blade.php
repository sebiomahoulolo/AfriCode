@extends('admin.layouts.app')

@section('breadcrumb', 'Modifier le module')
@section('title', 'Modifier le module')

@section('content')
<div class="fade-in">
    <!-- En-tête : titre + retour -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.25rem;">
                    <i class="fas fa-edit" style="color: #1EA38B;"></i> Modifier le module
                </h1>
                <p class="admin-card-subtitle mb-0">Cours : {{ $course->title }}</p>
            </div>
            <a href="{{ route('admin.courses.show', $course->id) }}" style="background: linear-gradient(135deg, #6C757D, #95A5A6); color: white; padding: 0.6rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-arrow-left me-1"></i> Retour au cours
            </a>
        </div>
    </div>

    <!-- Formulaire de modification (style formateur : une carte, champs clairs) -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="50" style="margin-bottom: 1.5rem;">
        <div class="admin-card-body">
            <div style="background: linear-gradient(135deg, rgba(30, 163, 139, 0.08), rgba(39, 179, 113, 0.08)); padding: 1rem 1.25rem; border-radius: 8px; border-left: 4px solid #1EA38B; margin-bottom: 1.5rem;">
                <i class="fas fa-info-circle" style="color: #1EA38B;"></i>
                <strong>Module {{ $module->order }}</strong> — Modifiez les informations du module ci-dessous.
            </div>

            <form action="{{ route('admin.modules.update', $module->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="admin-form-group" style="margin-bottom: 1.25rem;">
                    <label for="title" class="admin-form-label">Titre du module <span class="text-danger">*</span></label>
                    <input type="text" class="admin-form-input @error('title') error @enderror" id="title" name="title" value="{{ old('title', $module->title) }}" required>
                    <div class="form-text" style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Donnez un titre clair qui décrit le contenu du module.</div>
                    @error('title') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-form-group" style="margin-bottom: 1.25rem;">
                    <label for="description" class="admin-form-label">Description</label>
                    <textarea class="admin-form-input @error('description') error @enderror" id="description" name="description" rows="4">{{ old('description', $module->description) }}</textarea>
                    <div class="form-text" style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Une brève description du contenu et des objectifs du module.</div>
                    @error('description') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-form-group" style="margin-bottom: 1.25rem;">
                    <label for="order" class="admin-form-label">Ordre</label>
                    <input type="number" class="admin-form-input @error('order') error @enderror" id="order" name="order" value="{{ old('order', $module->order) }}" min="1">
                    <div class="form-text" style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Position du module dans la liste. Plus le nombre est petit, plus le module apparaît tôt.</div>
                    @error('order') <span class="admin-form-error">{{ $message }}</span> @enderror
                </div>

                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <h6 style="margin: 0 0 0.5rem 0; color: #1EA38B;"><i class="fas fa-info-circle me-1"></i> Informations du module</h6>
                    <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                        <li><strong>Cours :</strong> {{ $course->title }}</li>
                        <li><strong>Nombre de leçons :</strong> {{ $module->lessons->count() }}</li>
                        <li><strong>Quiz :</strong> {{ $module->quizzes->count() > 0 ? 'Oui' : 'Non' }}</li>
                        <li><strong>Créé le :</strong> {{ $module->created_at->format('d/m/Y à H:i') }}</li>
                        <li><strong>Modifié le :</strong> {{ $module->updated_at->format('d/m/Y à H:i') }}</li>
                    </ul>
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
                    <button type="submit" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.6rem 1.25rem; border: none; border-radius: 8px; font-weight: 500;">
                        <i class="fas fa-save me-1"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.courses.show', $course->id) }}" style="color: #6C757D; padding: 0.6rem 1.25rem; text-decoration: none;">Annuler</a>
                    <button type="button" style="margin-left: auto; background: linear-gradient(135deg, #E32D31, #FF6B6B); color: white; padding: 0.6rem 1.25rem; border: none; border-radius: 8px; font-weight: 500;" data-bs-toggle="modal" data-bs-target="#deleteModuleModal">
                        <i class="fas fa-trash me-1"></i> Supprimer le module
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leçons et Quiz (2 colonnes, style formateur / admin) -->
    <div class="admin-grid admin-grid-2" style="gap: 1.25rem;">
        <div class="admin-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="admin-card-title" style="font-size: 1.1rem; margin: 0;"><i class="fas fa-book me-2" style="color: #1EA38B;"></i> Leçons</h3>
                <button type="button" class="admin-button admin-button-primary" style="padding: 0.4rem 0.9rem; font-size: 0.9rem;" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                    <i class="fas fa-plus me-1"></i> Ajouter une leçon
                </button>
            </div>
            <div class="admin-card-body">
                @if($module->lessons->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($module->lessons->sortBy('order') as $lesson)
                            <div class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span style="background: #E9ECEF; color: #495057; padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.8rem; margin-right: 0.5rem;">{{ $lesson->order }}</span>
                                    {{ $lesson->title }}
                                    <small class="text-muted ms-2">
                                        @if(($lesson->content_type ?? $lesson->type ?? '') === 'video') <i class="fas fa-video"></i> Vidéo
                                        @elseif(($lesson->content_type ?? $lesson->type ?? '') === 'pdf') <i class="fas fa-file-pdf"></i> PDF
                                        @elseif(($lesson->content_type ?? $lesson->type ?? '') === 'text') <i class="fas fa-file-alt"></i> Texte
                                        @endif
                                    </small>
                                </div>
                                <div>
                                    <a href="{{ route('admin.lessons.edit', $lesson->id) }}" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.35rem 0.7rem; border-radius: 6px; text-decoration: none; font-size: 0.85rem;"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger ms-1 lesson-delete-btn" data-lesson-id="{{ $lesson->id }}" data-lesson-title="{{ $lesson->title }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Aucune leçon. Cliquez sur « Ajouter une leçon ».</p>
                @endif
            </div>
        </div>

        <div class="admin-card" data-aos="fade-up" data-aos-delay="150">
            <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="admin-card-title" style="font-size: 1.1rem; margin: 0;"><i class="fas fa-question-circle me-2" style="color: #FF8E2A;"></i> Quiz</h3>
                <button type="button" class="admin-button" style="background: linear-gradient(135deg, #FF8E2A, #FFB366); color: white; padding: 0.4rem 0.9rem; font-size: 0.9rem; border: none; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#addQuizModal">
                    <i class="fas fa-plus me-1"></i> Ajouter un quiz
                </button>
            </div>
            <div class="admin-card-body">
                @if($module->quizzes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($module->quizzes as $quiz)
                            <div class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <i class="fas fa-question-circle text-warning me-2"></i>
                                    {{ $quiz->title }}
                                    <span style="background: #E3F2FD; color: #1976D2; padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.8rem; margin-left: 0.5rem;">{{ $quiz->questions->count() }} question(s)</span>
                                </div>
                                <div>
                                    <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.35rem 0.7rem; border-radius: 6px; text-decoration: none; font-size: 0.85rem;"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger ms-1 quiz-delete-btn" data-quiz-id="{{ $quiz->id }}" data-quiz-title="{{ $quiz->title }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Aucun quiz. Cliquez sur « Ajouter un quiz ».</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal suppression module -->
<div class="modal fade" id="deleteModuleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce module et tout son contenu (leçons et quiz) ?</p>
                <p class="text-danger mb-0">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.modules.destroy', $module->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal ajouter leçon -->
<div class="modal fade" id="addLessonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une leçon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.lessons.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="lesson-title" class="form-label">Titre de la leçon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lesson-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="lesson-description" class="form-label">Description</label>
                        <textarea class="form-control" id="lesson-description" name="description" rows="2"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="lesson-type" class="form-label">Type de contenu <span class="text-danger">*</span></label>
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
                    </div>
                    <div class="mb-3 d-none" id="text-content">
                        <label for="lesson-text-content" class="form-label">Contenu texte (Markdown)</label>
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
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #1EA38B, #27B371); border: none;">Ajouter la leçon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal ajouter quiz -->
<div class="modal fade" id="addQuizModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.quizzes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">
                <input type="hidden" name="related_type" value="App\Models\Module">
                <input type="hidden" name="related_id" value="{{ $module->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quiz-title" class="form-label">Titre du quiz <span class="text-danger">*</span></label>
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
                            <input type="number" class="form-control" id="quiz-time-limit" name="time_limit" min="0" placeholder="0 = illimitée">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="quiz-is-required" name="is_required" value="1" checked>
                        <label class="form-check-label" for="quiz-is-required">Obligatoire pour compléter le module</label>
                    </div>
                    <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i> Vous pourrez ajouter les questions après la création.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #1EA38B, #27B371); border: none;">Créer le quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal suppression leçon -->
<div class="modal fade" id="deleteLessonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer la leçon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la leçon <strong id="lesson-title-to-delete"></strong> ?</p>
                <p class="text-danger mb-0">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="delete-lesson-form" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal suppression quiz -->
<div class="modal fade" id="deleteQuizModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer le quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le quiz <strong id="quiz-title-to-delete"></strong> ?</p>
                <p class="text-danger mb-0">Toutes les questions seront supprimées. Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="delete-quiz-form" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var lessonType = document.getElementById('lesson-type');
    if (lessonType) {
        lessonType.addEventListener('change', function() {
            document.getElementById('video-content').classList.add('d-none');
            document.getElementById('text-content').classList.add('d-none');
            document.getElementById('pdf-content').classList.add('d-none');
            if (this.value === 'video') document.getElementById('video-content').classList.remove('d-none');
            else if (this.value === 'text') document.getElementById('text-content').classList.remove('d-none');
            else if (this.value === 'pdf') document.getElementById('pdf-content').classList.remove('d-none');
        });
    }
    document.querySelectorAll('.lesson-delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('lesson-title-to-delete').textContent = this.getAttribute('data-lesson-title');
            document.getElementById('delete-lesson-form').action = '/admin/lessons/' + this.getAttribute('data-lesson-id');
            new bootstrap.Modal(document.getElementById('deleteLessonModal')).show();
        });
    });
    document.querySelectorAll('.quiz-delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('quiz-title-to-delete').textContent = this.getAttribute('data-quiz-title');
            document.getElementById('delete-quiz-form').action = '/admin/quizzes/' + this.getAttribute('data-quiz-id');
            new bootstrap.Modal(document.getElementById('deleteQuizModal')).show();
        });
    });
});
</script>
@endpush
