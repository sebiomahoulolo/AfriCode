@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Ajouter une leçon')
@section('page-title', 'Ajouter une nouvelle leçon')
@section('page-subtitle', 'Module: ' . $module->title)

@section('header-actions')
    <a href="{{ route('formateur.manage.module', $module) }}" class="btn btn-outline-secondary me-2">
        <i class="fas fa-arrow-left me-2"></i>Retour au module
    </a>
@endsection

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
    
    /* Responsive amélioré */
    @media (max-width: 992px) {
        .content-type-card i {
            font-size: 1.75rem;
        }
        
        .ck-editor__editable {
            min-height: 250px;
        }
    }
    
    @media (max-width: 768px) {
        .content-type-card {
            margin-bottom: 1rem;
        }
        
        .content-type-card:hover {
            transform: none;
        }
        
        .content-type-card i {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }
        
        .content-type-card h6 {
            font-size: 0.9rem;
        }
        
        .content-type-card p {
            font-size: 0.8rem;
        }
        
        .ck-editor__editable {
            min-height: 200px;
        }
        
        .form-label {
            font-size: 0.9rem;
        }
        
        .form-control {
            font-size: 0.9rem;
        }
        
        .form-text {
            font-size: 0.75rem;
        }
        
        .btn {
            font-size: 0.875rem;
            padding: 0.625rem 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .content-type-card {
            text-align: center;
            padding: 1rem;
        }
        
        .content-type-card i {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        
        .content-type-card h6 {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
        
        .content-type-card p {
            font-size: 0.75rem;
        }
        
        .ck-editor__editable {
            min-height: 150px;
        }
        
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.375rem;
        }
        
        .form-control {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }
        
        .form-text {
            font-size: 0.7rem;
            margin-top: 0.25rem;
        }
        
        .btn {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
        
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        
        /* Amélioration des sélecteurs de type de contenu */
        .row .col-md-4 {
            margin-bottom: 0.75rem;
        }
    }
    
    @media (max-width: 480px) {
        .content-type-card {
            padding: 0.75rem;
        }
        
        .content-type-card i {
            font-size: 1rem;
        }
        
        .ck-editor__editable {
            min-height: 120px;
        }
        
        .form-control {
            font-size: 0.8rem;
            padding: 0.45rem 0.65rem;
        }
        
        .btn {
            font-size: 0.75rem;
            padding: 0.45rem 0.65rem;
        }
        
        .card-body {
            padding: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
    <div class="card-modern" data-aos="fade-up">
        <div class="card-body-modern">
            <form id="create-lesson-form" method="POST" action="{{ route('formateur.lessons.store', ['moduleId' => $module->id]) }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Titre de la leçon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    <div class="form-text">Donnez un titre clair et concis pour cette leçon.</div>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label d-block">Type de contenu <span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="content-type-card card text-center p-3 {{ old('content_type') == 'video' ? 'selected' : '' }}" data-type="video">
                                <div>
                                    <i class="fas fa-video text-primary"></i>
                                    <h6 class="mt-2">Vidéo</h6>
                                    <p class="text-muted small mb-0">Lien YouTube, Vimeo, etc.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="content-type-card card text-center p-3 {{ old('content_type') == 'text' ? 'selected' : '' }}" data-type="text">
                                <div>
                                    <i class="fas fa-file-alt text-info"></i>
                                    <h6 class="mt-2">Texte</h6>
                                    <p class="text-muted small mb-0">Contenu textuel avec mise en forme</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="content-type-card card text-center p-3 {{ old('content_type') == 'pdf' ? 'selected' : '' }}" data-type="pdf">
                                <div>
                                    <i class="fas fa-file-pdf text-danger"></i>
                                    <h6 class="mt-2">PDF</h6>
                                    <p class="text-muted small mb-0">Document PDF à télécharger</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="content-type-card card text-center p-3 {{ old('content_type') == 'external' ? 'selected' : '' }}" data-type="external">
                                <div>
                                    <i class="fas fa-link text-success"></i>
                                    <h6 class="mt-2">Lien externe</h6>
                                    <p class="text-muted small mb-0">Contenu hébergé ailleurs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="content_type" id="content_type" value="{{ old('content_type') }}" required>
                    @error('content_type')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Video Content Section -->
                <div id="video-section" class="content-type-section {{ old('content_type') == 'video' ? 'active' : '' }}">
                    <div class="mb-3">
                        <label for="video_url" class="form-label">URL de la vidéo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('video_url') is-invalid @enderror" id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                        <div class="form-text">URL YouTube, Vimeo ou autre plateforme de partage vidéo.</div>
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Text Content Section -->
                <div id="text-section" class="content-type-section {{ old('content_type') == 'text' ? 'active' : '' }}">
                    <div class="mb-3">
                        <label for="text_content" class="form-label">Contenu texte <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('text_content') is-invalid @enderror" id="text_content" name="text_content" rows="10">{{ old('text_content') }}</textarea>
                        <div class="form-text">Rédigez le contenu de votre leçon avec mise en forme.</div>
                        @error('text_content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- PDF Content Section -->
                <div id="pdf-section" class="content-type-section {{ old('content_type') == 'pdf' ? 'active' : '' }}">
                    <div class="mb-3">
                        <label for="pdf_file" class="form-label">Fichier PDF <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf">
                        <div class="form-text">Téléchargez un fichier PDF (max. 10 MB).</div>
                        @error('pdf_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- External Content Section -->
                <div id="external-section" class="content-type-section {{ old('content_type') == 'external' ? 'active' : '' }}">
                    <div class="mb-3">
                        <label for="external_url" class="form-label">URL externe <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('external_url') is-invalid @enderror" id="external_url" name="external_url" value="{{ old('external_url') }}" placeholder="https://...">
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
                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes') }}" min="1">
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
                                <input class="form-check-input" type="checkbox" id="is_previewable" name="is_previewable" {{ old('is_previewable') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_previewable">
                                    Prévisualisable (accessible sans inscription)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('formateur.manage.module', $module) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer la leçon
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    let textEditorInstance = null; // To hold the CKEditor instance

    // Initialize content type selection
    const contentTypeCards = document.querySelectorAll('.content-type-card');
    const contentTypeSections = document.querySelectorAll('.content-type-section');
    const contentTypeInput = document.getElementById('content_type');
    
    // Set initial state if coming from validation error
    const initialContentType = contentTypeInput.value;
    if (initialContentType) {
        const selectedCard = document.querySelector(`.content-type-card[data-type="${initialContentType}"]`);
        if (selectedCard) {
            contentTypeCards.forEach(c => c.classList.remove('selected'));
            selectedCard.classList.add('selected');
        }
        // Ensure the section is shown AND CKEditor is initialized if 'text' is the initial type
        showSection(initialContentType, true); 
    }
    
    contentTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            contentTypeCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            const contentType = this.getAttribute('data-type');
            contentTypeInput.value = contentType;
            showSection(contentType);
        });
    });
    
    function showSection(contentType, isInitialLoad = false) {
        contentTypeSections.forEach(section => section.classList.remove('active'));
        
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
                                console.log('CKEditor initialized for #text_content');
                            })
                            .catch(error => {
                                console.error('Erreur CKEditor pour #text_content:', error);
                                textEditorInstance = null; 
                            });
                    }
                } else {
                    // If switching away from text, and you prefer to destroy the editor:
                    // if (textEditorInstance) {
                    //     textEditorInstance.destroy()
                    //         .then(() => {
                    //             textEditorInstance = null;
                    //             console.log('CKEditor instance destroyed.');
                    //         })
                    //         .catch(error => console.error('Error destroying CKEditor:', error));
                    // }
                }
            } else {
                console.warn(`Section non trouvée pour le type de contenu: ${contentType}-section`);
            }
        }
    }

    // Handle form submission to ensure CKEditor data is updated
    document.getElementById('create-lesson-form').addEventListener('submit', function(e) {
        // Update CKEditor data if it exists
        if (textEditorInstance) {
            try {
                const editorData = textEditorInstance.getData();
                document.getElementById('text_content').value = editorData;
            } catch (error) {
                console.error('Error getting CKEditor data:', error);
            }
        }

        // Validate content type selection
        if (!contentTypeInput.value) {
            e.preventDefault();
            alert('Veuillez sélectionner un type de contenu.');
            return false;
        }

        // Additional validation based on content type
        const contentType = contentTypeInput.value;
        let isValid = true;
        let errorMessage = '';

        switch(contentType) {
            case 'video':
                const videoUrl = document.getElementById('video_url').value.trim();
                if (!videoUrl) {
                    isValid = false;
                    errorMessage = 'Veuillez saisir l\'URL de la vidéo.';
                }
                break;
            case 'text':
                const textContent = document.getElementById('text_content').value.trim();
                if (!textContent) {
                    isValid = false;
                    errorMessage = 'Veuillez saisir le contenu texte.';
                }
                break;
            case 'pdf':
                const pdfFile = document.getElementById('pdf_file').files[0];
                if (!pdfFile) {
                    isValid = false;
                    errorMessage = 'Veuillez sélectionner un fichier PDF.';
                }
                break;
            case 'external':
                const externalUrl = document.getElementById('external_url').value.trim();
                if (!externalUrl) {
                    isValid = false;
                    errorMessage = 'Veuillez saisir l\'URL externe.';
                }
                break;
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }

        // Show loading state on submit button
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
        }
    });
</script>
@endsection
