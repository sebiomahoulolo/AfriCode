@extends('admin.layouts.app')

@section('title', 'Modifier une Leçon')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit fa-fw me-1"></i> Modifier la leçon: {{ $lesson->title }}
            </h6>
            <div>
                <a href="{{ route('admin.modules.edit', $module->id) }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left fa-fw"></i> Retour au module
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data" id="lessonForm">
                @csrf
                @method('PUT')

                <!-- Informations générales de la leçon -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2">Informations générales</h5>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre de la leçon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $lesson->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="content_type" class="form-label">Type de contenu <span class="text-danger">*</span></label>
                            <select class="form-select @error('content_type') is-invalid @enderror" id="content_type" name="content_type" required>
                                <option value="video" {{ old('content_type', $lesson->content_type) === 'video' ? 'selected' : '' }}>Vidéo</option>
                                <option value="text" {{ old('content_type', $lesson->content_type) === 'text' ? 'selected' : '' }}>Texte</option>
                                <option value="pdf" {{ old('content_type', $lesson->content_type) === 'pdf' ? 'selected' : '' }}>PDF</option>
                                <option value="external" {{ old('content_type', $lesson->content_type) === 'external' ? 'selected' : '' }}>Lien externe</option>
                            </select>
                            @error('content_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="order" class="form-label">Ordre</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $lesson->order) }}" min="1">
                            <div class="form-text">Position de la leçon dans le module</div>
                            @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="duration_minutes" class="form-label">Durée (minutes)</label>
                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" min="1">
                            @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_previewable" name="is_previewable" {{ old('is_previewable', $lesson->is_previewable) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_previewable">Prévisualisable gratuitement</label>
                        <div class="form-text">Si coché, cette leçon sera visible sans inscription ou paiement</div>
                    </div>
                </div>

                <!-- Contenu de la leçon - changement dynamique selon le type -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2">Contenu de la leçon</h5>
                    
                    <!-- Contenu de type Vidéo -->
                    <div id="video-content" class="content-section">
                        <div class="card mb-3">
                            <div class="card-header bg-danger text-white">
                                <i class="fas fa-video me-2"></i> Contenu Vidéo
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="video_url" class="form-label">URL de la vidéo (YouTube ou Vimeo) <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('video_url') is-invalid @enderror" id="video_url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                                    <div class="form-text">Collez le lien direct vers la vidéo YouTube ou Vimeo</div>
                                    @error('video_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div id="video-preview" style="display:none;">
                                    <label class="form-label">Aperçu de la vidéo:</label>
                                    <div class="ratio ratio-16x9">
                                        <iframe id="video-iframe" src="" title="Aperçu de la vidéo" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu de type Texte -->
                    <div id="text-content" class="content-section">
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-file-alt me-2"></i> Contenu Texte
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="text_content" class="form-label">Contenu texte <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce-editor @error('text_content') is-invalid @enderror" id="text_content" name="text_content" rows="10">{{ old('text_content', $lesson->text_content) }}</textarea>
                                    @error('text_content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu de type PDF -->
                    <div id="pdf-content" class="content-section">
                        <div class="card mb-3">
                            <div class="card-header bg-warning">
                                <i class="fas fa-file-pdf me-2"></i> Document PDF
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="pdf_file" class="form-label">Fichier PDF</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept="application/pdf">
                                        <button class="btn btn-outline-secondary" type="button" id="clear-pdf-btn">Effacer</button>
                                    </div>
                                    <div class="form-text">Laissez vide pour conserver le fichier PDF existant</div>
                                    <div id="pdf-file-feedback" class="form-text"></div>
                                    @error('pdf_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                @if($lesson->content_type === 'pdf' && $lesson->pdf_path)
                                <div class="card mt-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-file-pdf text-danger me-2"></i>Document PDF actuel</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                                <div>
                                                    <h5 class="mb-1">{{ basename($lesson->pdf_path) }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ Storage::url($lesson->pdf_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye fa-fw"></i> Visualiser
                                        </a>
                                        <a href="{{ Storage::url($lesson->pdf_path) }}" download class="btn btn-sm btn-secondary">
                                            <i class="fas fa-download fa-fw"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                                @endif
                                
                                <div id="pdf-preview" class="mt-3" style="display:none;">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Nouveau fichier PDF</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf fa-3x text-danger me-3"></i>
                                                <div>
                                                    <h5 class="mb-1" id="pdf-filename">document.pdf</h5>
                                                    <div class="small text-muted" id="pdf-filesize">0 Ko</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenu de type Lien externe -->
                    <div id="external-content" class="content-section">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-external-link-alt me-2"></i> Lien externe
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="external_url" class="form-label">Lien externe <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('external_url') is-invalid @enderror" id="external_url" name="external_url" value="{{ old('external_url', $lesson->external_url) }}" placeholder="https://...">
                                    <div class="form-text">URL d'une ressource externe (documentation, article, etc.)</div>
                                    @error('external_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div id="external-preview" style="display:none;">
                                    <label class="form-label">Aperçu du lien:</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title" id="external-title">Titre de la page</h5>
                                            <p class="card-text" id="external-description">Chargement de l'aperçu du lien...</p>
                                            <div class="small text-muted" id="external-url-display"></div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="" id="external-preview-link" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-external-link-alt fa-fw"></i> Visiter le lien
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mt-2 small text-muted">Note: L'aperçu peut être limité selon les restrictions du site externe.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save fa-fw"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.modules.edit', $module->id) }}" class="btn btn-secondary">Annuler</a>
                    
                    <button type="button" class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#deleteLessonModal">
                        <i class="fas fa-trash fa-fw"></i> Supprimer la leçon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de suppression de la leçon -->
<div class="modal fade" id="deleteLessonModal" tabindex="-1" aria-labelledby="deleteLessonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLessonModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette leçon ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST">
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
<!-- Inclure TinyMCE depuis CDN -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Script de gestion des formulaires de leçon chargé');
        
        // Configuration initiale
        const contentTypeSelect = document.getElementById('content_type');
        const initialContentType = contentTypeSelect.value;
        
        console.log('Type de contenu initial:', initialContentType);
        
        // Initialiser l'affichage selon le type de contenu initial
        updateContentDisplay(initialContentType);
        
        // Initialiser TinyMCE si nécessaire
        if (initialContentType === 'text') {
            setTimeout(initTinyMCE, 100);
        }
        
        // Gestionnaire de changement du type de contenu
        contentTypeSelect.addEventListener('change', function() {
            const newType = this.value;
            console.log('Changement du type de contenu vers:', newType);
            updateContentDisplay(newType);
        });
        
        function updateContentDisplay(contentType) {
            console.log('Mise à jour de l\'affichage pour le type:', contentType);
            
            const videoContent = document.getElementById('video-content');
            const textContent = document.getElementById('text-content');
            const pdfContent = document.getElementById('pdf-content');
            const externalContent = document.getElementById('external-content');
            
            // Masquer tous les contenus
            videoContent.style.display = 'none';
            textContent.style.display = 'none';
            pdfContent.style.display = 'none';
            externalContent.style.display = 'none';
            
            // Afficher le contenu approprié
            switch(contentType) {
                case 'video':
                    videoContent.style.display = 'block';
                    console.log('Affichage du contenu vidéo');
                    // Initialiser la prévisualisation vidéo si une URL existe
                    const videoUrl = document.getElementById('video_url').value;
                    if (videoUrl) {
                        updateVideoPreview(videoUrl);
                    }
                    break;
                case 'text':
                    textContent.style.display = 'block';
                    console.log('Affichage du contenu texte');
                    // Réinitialiser TinyMCE après un court délai
                    setTimeout(initTinyMCE, 100);
                    break;
                case 'pdf':
                    pdfContent.style.display = 'block';
                    console.log('Affichage du contenu PDF');
                    break;
                case 'external':
                    externalContent.style.display = 'block';
                    console.log('Affichage du contenu lien externe');
                    // Initialiser la prévisualisation du lien externe si une URL existe
                    const externalUrl = document.getElementById('external_url').value;
                    if (externalUrl) {
                        updateExternalPreview(externalUrl);
                    }
                    break;
                default:
                    console.log('Type de contenu non reconnu:', contentType);
            }
        }
        
        // Initialisation TinyMCE
        function initTinyMCE() {
            console.log('Initialisation de TinyMCE');
            
            // Détruire l'instance existante si elle existe
            tinymce.remove('#text_content');
            
            tinymce.init({
                selector: '#text_content',
                height: 400,
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code',
                toolbar: 'undo redo | blocks | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | code | removeformat',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                codesample_languages: [
                    { text: 'HTML/XML', value: 'markup' },
                    { text: 'JavaScript', value: 'javascript' },
                    { text: 'CSS', value: 'css' },
                    { text: 'PHP', value: 'php' },
                    { text: 'Python', value: 'python' },
                    { text: 'Java', value: 'java' },
                    { text: 'C', value: 'c' },
                    { text: 'C++', value: 'cpp' },
                    { text: 'C#', value: 'csharp' },
                    { text: 'Ruby', value: 'ruby' },
                    { text: 'Bash', value: 'bash' },
                    { text: 'SQL', value: 'sql' }
                ],
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });
        }
        
        // Gestion de la prévisualisation vidéo
        const videoUrlInput = document.getElementById('video_url');
        if (videoUrlInput) {
            videoUrlInput.addEventListener('input', function() {
                updateVideoPreview(this.value);
            });
        }
        
        function updateVideoPreview(url) {
            const videoPreview = document.getElementById('video-preview');
            const videoIframe = document.getElementById('video-iframe');
            
            if (!url) {
                videoPreview.style.display = 'none';
                return;
            }
            
            let embedUrl = '';
            
            // YouTube
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                const youtubeId = getYouTubeId(url);
                if (youtubeId) {
                    embedUrl = 'https://www.youtube.com/embed/' + youtubeId;
                }
            } 
            // Vimeo
            else if (url.includes('vimeo.com')) {
                const vimeoId = getVimeoId(url);
                if (vimeoId) {
                    embedUrl = 'https://player.vimeo.com/video/' + vimeoId;
                }
            }
            
            if (embedUrl) {
                videoIframe.src = embedUrl;
                videoPreview.style.display = 'block';
            } else {
                videoPreview.style.display = 'none';
            }
        }
        
        function getYouTubeId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : null;
        }
        
        function getVimeoId(url) {
            let regExp = /(?:vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?))/;
            let match = url.match(regExp);
            
            if (match && match[2]) {
                return match[2];
            }
            
            regExp = /player\.vimeo\.com\/video\/(\d+)/;
            match = url.match(regExp);
            return match ? match[1] : null;
        }
        
        // Gestion de la prévisualisation des liens externes
        const externalUrlInput = document.getElementById('external_url');
        if (externalUrlInput) {
            externalUrlInput.addEventListener('input', function() {
                updateExternalPreview(this.value);
            });
        }
        
        function updateExternalPreview(url) {
            const externalPreview = document.getElementById('external-preview');
            const externalTitle = document.getElementById('external-title');
            const externalDescription = document.getElementById('external-description');
            const externalUrlDisplay = document.getElementById('external-url-display');
            const externalPreviewLink = document.getElementById('external-preview-link');
            
            if (!url) {
                externalPreview.style.display = 'none';
                return;
            }
            
            externalUrlDisplay.textContent = url;
            externalPreviewLink.href = url;
            externalPreview.style.display = 'block';
            
            try {
                const domain = new URL(url).hostname;
                externalTitle.textContent = domain;
                externalDescription.textContent = "Lien vers une ressource externe sur " + domain;
            } catch (e) {
                externalTitle.textContent = "URL invalide";
                externalDescription.textContent = "Veuillez entrer une URL valide commençant par http:// ou https://";
            }
        }
        
        // Gestion du fichier PDF
        const pdfFileInput = document.getElementById('pdf_file');
        const clearPdfBtn = document.getElementById('clear-pdf-btn');
        
        if (pdfFileInput) {
            pdfFileInput.addEventListener('change', function() {
                updatePdfPreview(this);
            });
        }
        
        if (clearPdfBtn) {
            clearPdfBtn.addEventListener('click', function() {
                pdfFileInput.value = '';
                document.getElementById('pdf-preview').style.display = 'none';
                document.getElementById('pdf-file-feedback').textContent = '';
            });
        }
        
        function updatePdfPreview(input) {
            const pdfPreview = document.getElementById('pdf-preview');
            const pdfFilename = document.getElementById('pdf-filename');
            const pdfFilesize = document.getElementById('pdf-filesize');
            const pdfFileFeedback = document.getElementById('pdf-file-feedback');
            
            if (!input.files || input.files.length === 0) {
                pdfPreview.style.display = 'none';
                pdfFileFeedback.textContent = '';
                return;
            }
            
            const file = input.files[0];
            
            if (file.type !== 'application/pdf') {
                pdfFileFeedback.textContent = 'Le fichier doit être au format PDF.';
                pdfFileFeedback.className = 'text-danger small';
                pdfPreview.style.display = 'none';
                return;
            }
            
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                pdfFileFeedback.textContent = 'Le fichier est trop volumineux. Taille maximale: 10MB';
                pdfFileFeedback.className = 'text-danger small';
                pdfPreview.style.display = 'none';
                return;
            }
            
            pdfFilename.textContent = file.name;
            pdfFilesize.textContent = formatFileSize(file.size);
            pdfPreview.style.display = 'block';
            pdfFileFeedback.textContent = 'Fichier PDF valide';
            pdfFileFeedback.className = 'text-success small';
        }
        
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' octets';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(2) + ' Ko';
            else return (bytes / 1048576).toFixed(2) + ' Mo';
        }
    });
</script>
@endsection
