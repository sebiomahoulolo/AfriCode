@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Modifier une leçon')

@section('page-heading', 'Modifier une leçon')
@section('page-subheading', 'Module: ' . $lesson->module->title)

@section('styles')
<style>
    .content-type-section {
        display: none;
    }
    
    .content-type-section.active {
        display: block;
    }
    
    .content-type-card {
        cursor: pointer;
        transition: all 0.3s;
        height: 100%;
    }
    
    .content-type-card:hover {
        transform: translateY(-5px);
    }
    
    .content-type-card.selected {
        border: 2px solid var(--primary-color);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .content-type-card i {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    
    .ck-editor__editable {
        min-height: 300px;
    }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('formateur.lessons.update', ['lessonId' => $lesson->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">Titre de la leçon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $lesson->title) }}" required>
                    <div class="form-text">Donnez un titre clair et concis pour cette leçon.</div>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label d-block">Type de contenu <span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="content-type-card card text-center p-3 {{ old('content_type', $lesson->content_type) == 'video' ? 'selected' : '' }}" data-type="video">
                                <div>
                                    <i class="fas fa-video text-primary"></i>
                                    <h6 class="mt-2">Vidéo</h6>
                                    <p class="text-muted small mb-0">Lien YouTube, Vimeo, etc.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="content-type-card card text-center p-3 {{ old('content_type', $lesson->content_type) == 'text' ? 'selected' : '' }}" data-type="text">
                                <div>
                                    <i class="fas fa-file-alt text-info"></i>
                                    <h6 class="mt-2">Texte</h6>
                                    <p class="text-muted small mb-0">Contenu textuel avec mise en forme</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="content-type-card card text-center p-3 {{ old('content_type', $lesson->content_type) == 'pdf' ? 'selected' : '' }}" data-type="pdf">
                                <div>
                                    <i class="fas fa-file-pdf text-danger"></i>
                                    <h6 class="mt-2">PDF</h6>
                                    <p class="text-muted small mb-0">Document PDF à télécharger</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="content-type-card card text-center p-3 {{ old('content_type', $lesson->content_type) == 'external' ? 'selected' : '' }}" data-type="external">
                                <div>
                                    <i class="fas fa-link text-success"></i>
                                    <h6 class="mt-2">Lien externe</h6>
                                    <p class="text-muted small mb-0">Contenu hébergé ailleurs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="content_type" id="content_type" value="{{ old('content_type', $lesson->content_type) }}" required>
                    @error('content_type')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Video Content Section -->
                <div id="video-section" class="content-type-section {{ old('content_type', $lesson->content_type) == 'video' ? 'active' : '' }}">
                    <div class="mb-3">
                        <label for="video_url" class="form-label">URL de la vidéo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('video_url') is-invalid @enderror" id="video_url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                        <div class="form-text">URL YouTube, Vimeo ou autre plateforme de partage vidéo.</div>
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Text Content Section -->
                <div id="text-section" class="content-type-section {{ old('content_type', $lesson->content_type) == 'text' ? 'active' : '' }}">
                    <div class="mb-3">
                        <label for="text_content" class="form-label">Contenu texte <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('text_content') is-invalid @enderror" id="text_content" name="text_content" rows="10">{{ old('text_content', $lesson->text_content) }}</textarea>
                        <div class="form-text">Rédigez le contenu de votre leçon avec mise en forme.</div>
                        @error('text_content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- PDF Content Section -->
                <div id="pdf-section" class="content-type-section {{ old('content_type', $lesson->content_type) == 'pdf' ? 'active' : '' }}">
                    <div class="mb-3">
                        <label for="pdf_file" class="form-label">Fichier PDF</label>
                        @if($lesson->pdf_path)
                            <div class="mb-2">
                                <span class="badge bg-success">Fichier PDF actuel : {{ basename($lesson->pdf_path) }}</span>
                                <a href="{{ asset($lesson->pdf_path) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </div>
                            <div class="form-text mb-2">Téléchargez un nouveau PDF uniquement si vous souhaitez remplacer l'actuel.</div>
                        @endif
                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf">
                        <div class="form-text">Téléchargez un fichier PDF (max. 10 MB).</div>
                        @error('pdf_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- External Content Section -->
                <div id="external-section" class="content-type-section {{ old('content_type', $lesson->content_type) == 'external' ? 'active' : '' }}">
                    <div class="mb-3">
                        <label for="external_url" class="form-label">URL externe <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('external_url') is-invalid @enderror" id="external_url" name="external_url" value="{{ old('external_url', $lesson->external_url) }}" placeholder="https://...">
                        <div class="form-text">Lien vers une ressource externe comme un document Google, GitHub, etc.</div>
                        @error('external_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="duration_minutes" class="form-label">Durée (en minutes)</label>
                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" min="1">
                            <div class="form-text">Durée estimée pour compléter cette leçon.</div>
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label d-block">Options</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_previewable" name="is_previewable" {{ old('is_previewable', $lesson->is_previewable) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_previewable">
                                    Prévisualisable (accessible sans inscription)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <a href="{{ route('formateur.manage.module', ['moduleId' => $lesson->module_id]) }}" class="btn btn-outline-secondary me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour la leçon</button>
                    </div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteLessonModal">
                        <i class="fas fa-trash-alt"></i> Supprimer la leçon
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteLessonModal" tabindex="-1" aria-labelledby="deleteLessonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteLessonModalLabel">Confirmation de suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette leçon ? Cette action est irréversible.</p>
                    <p class="fw-bold">{{ $lesson->title }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('formateur.lessons.destroy', ['lessonId' => $lesson->id]) }}" method="POST" class="d-inline">
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
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    let textEditorInstance = null; // Pour stocker l'instance CKEditor

    // Initialisation des cartes de type de contenu
    const contentTypeCards = document.querySelectorAll('.content-type-card');
    const contentTypeSections = document.querySelectorAll('.content-type-section');
    const contentTypeInput = document.getElementById('content_type');
    
    // Définir l'état initial
    const initialContentType = contentTypeInput.value;
    if (initialContentType) {
        const selectedCard = document.querySelector(`.content-type-card[data-type="${initialContentType}"]`);
        if (selectedCard) {
            contentTypeCards.forEach(c => c.classList.remove('selected'));
            selectedCard.classList.add('selected');
        }
        showSection(initialContentType, true);
    }
    
    contentTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            // Retirer la classe selected de toutes les cartes
            contentTypeCards.forEach(c => c.classList.remove('selected'));
            
            // Ajouter la classe selected à la carte cliquée
            this.classList.add('selected');
            
            // Récupérer le type de contenu depuis l'attribut data
            const contentType = this.getAttribute('data-type');
            
            // Mettre à jour la valeur de l'input caché
            contentTypeInput.value = contentType;
            
            // Afficher la section correspondante
            showSection(contentType);
        });
    });
    
    function showSection(contentType, isInitialLoad = false) {
        // Cacher toutes les sections
        contentTypeSections.forEach(section => section.classList.remove('active'));
        
        // Afficher la section sélectionnée si le contentType est valide et que l'élément existe
        if (contentType) {
            const sectionToShow = document.getElementById(`${contentType}-section`);
            if (sectionToShow) {
                sectionToShow.classList.add('active');
                
                if (contentType === 'text') {
                    const textContentElement = document.querySelector('#text_content');
                    if (textContentElement && !textEditorInstance) {
                        ClassicEditor
                            .create(textContentElement)
                            .then(editor => {
                                textEditorInstance = editor;
                                console.log('CKEditor initialisé pour #text_content');
                            })
                            .catch(error => {
                                console.error('Erreur CKEditor pour #text_content:', error);
                                textEditorInstance = null;
                            });
                    }
                }
            } else {
                console.warn(`Section non trouvée pour le type de contenu: ${contentType}-section`);
            }
        }
    }
</script>
@endsection
