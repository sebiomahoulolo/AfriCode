@extends('admin.layouts.app')

@section('title', 'Créer une Leçon')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-plus fa-fw me-1"></i> Créer une nouvelle leçon
            </h6>
            <div>
                <a href="{{ route('admin.modules.edit', $module->id) }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left fa-fw"></i> Retour au module
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lessons.store') }}" method="POST" enctype="multipart/form-data" id="lessonForm">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">

                <!-- Informations générales de la leçon -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2">Informations générales</h5>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre de la leçon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="content_type" class="form-label">Type de contenu <span class="text-danger">*</span></label>
                            <select class="form-select @error('content_type') is-invalid @enderror" id="content_type" name="content_type" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="video" {{ old('content_type') === 'video' ? 'selected' : '' }}>Vidéo</option>
                                <option value="text" {{ old('content_type') === 'text' ? 'selected' : '' }}>Texte</option>
                                <option value="pdf" {{ old('content_type') === 'pdf' ? 'selected' : '' }}>PDF</option>
                                <option value="external" {{ old('content_type') === 'external' ? 'selected' : '' }}>Lien externe</option>
                            </select>
                            @error('content_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="order" class="form-label">Ordre</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order') }}" min="1">
                            <div class="form-text">Position de la leçon (laissez vide pour ajouter à la fin)</div>
                            @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="duration_minutes" class="form-label">Durée (minutes)</label>
                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes') }}" min="1">
                            @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_previewable" name="is_previewable" {{ old('is_previewable') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_previewable">Prévisualisable gratuitement</label>
                        <div class="form-text">Si coché, cette leçon sera visible sans inscription ou paiement</div>
                    </div>
                </div>

                <!-- Contenu de la leçon - changement dynamique selon le type -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2">Contenu de la leçon</h5>
                    <div class="alert alert-info content-select-info">
                        <i class="fas fa-info-circle"></i> Sélectionnez un type de contenu ci-dessus pour afficher les champs correspondants.
                    </div>
                    
                    <!-- Contenu de type Vidéo -->
                    <div id="video-content" class="content-section" style="display:none;">
                        <div class="card mb-3">
                            <div class="card-header bg-danger text-white">
                                <i class="fas fa-video me-2"></i> Contenu Vidéo
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="video_url" class="form-label">URL de la vidéo (YouTube ou Vimeo) <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('video_url') is-invalid @enderror" id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                                    <div class="form-text">Collez le lien direct vers la vidéo YouTube ou Vimeo</div>
                                    @error('video_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div id="video-preview" class="mt-3" style="display:none;">
                                    <label class="form-label">Aperçu de la vidéo:</label>
                                    <div class="ratio ratio-16x9">
                                        <iframe id="video-iframe" src="" title="Aperçu de la vidéo" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu de type Texte -->
                    <div id="text-content" class="content-section" style="display:none;">
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-file-alt me-2"></i> Contenu Texte
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="text_content" class="form-label">Contenu texte <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce-editor @error('text_content') is-invalid @enderror" id="text_content" name="text_content" rows="10">{{ old('text_content') }}</textarea>
                                    @error('text_content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu de type PDF -->
                    <div id="pdf-content" class="content-section" style="display:none;">
                        <div class="card mb-3">
                            <div class="card-header bg-warning">
                                <i class="fas fa-file-pdf me-2"></i> Document PDF
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="pdf_file" class="form-label">Fichier PDF <span class="text-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept="application/pdf">
                                        <button class="btn btn-outline-secondary" type="button" id="clear-pdf-btn">Effacer</button>
                                    </div>
                                    <div id="pdf-file-feedback" class="form-text"></div>
                                    @error('pdf_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div id="pdf-preview" style="display:none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                                <div>
                                                    <h6 class="mb-1" id="pdf-filename">document.pdf</h6>
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
                    <div id="external-content" class="content-section" style="display:none;">
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-external-link-alt me-2"></i> Lien Externe
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="external_url" class="form-label">Lien externe <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('external_url') is-invalid @enderror" id="external_url" name="external_url" value="{{ old('external_url') }}" placeholder="https://...">
                                    <div class="form-text">URL d'une ressource externe (documentation, article, etc.)</div>
                                    @error('external_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div id="external-preview" style="display:none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title" id="external-title">Titre de la page</h6>
                                            <p class="card-text small" id="external-description">Chargement de l'aperçu du lien...</p>
                                            <p class="small text-muted" id="external-url-display"></p>
                                            <a href="#" id="external-preview-link" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-external-link-alt fa-fw"></i> Visiter le lien
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex mt-4">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="fas fa-plus fa-fw"></i> Créer la leçon
                    </button>
                    <a href="{{ route('admin.modules.edit', $module->id) }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Inclure TinyMCE depuis CDN -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables pour le contenu dynamique
        const contentTypeSelect = document.getElementById('content_type');
        const contentSelectInfo = document.querySelector('.content-select-info');
        const contentSections = document.querySelectorAll('.content-section');
        
        // Gestion de l'affichage des sections de contenu
        if (contentTypeSelect) {
            // Affichage initial
            const initialContentType = contentTypeSelect.value;
            updateContentSection(initialContentType);
            
            // Gestion du changement
            contentTypeSelect.addEventListener('change', function() {
                console.log("Type de contenu sélectionné:", this.value);
                updateContentSection(this.value);
            });
        }
        
        function updateContentSection(type) {
            // Masquer toutes les sections et afficher l'info de sélection
            contentSections.forEach(section => {
                section.style.display = 'none';
            });
            
            // Si aucun type n'est sélectionné, afficher le message d'info
            if (!type) {
                contentSelectInfo.style.display = 'block';
                return;
            }
            
            // Cacher le message d'info
            contentSelectInfo.style.display = 'none';
            
            // Afficher la section correspondante
            const sectionToShow = document.getElementById(type + '-content');
            if (sectionToShow) {
                sectionToShow.style.display = 'block';
                
                // Si c'est du texte, initialiser TinyMCE
                if (type === 'text') {
                    initTinyMCE();
                }
            }
        }
        
        // Initialisation de TinyMCE
        function initTinyMCE() {
            if (tinymce.get('text_content')) {
                tinymce.remove('#text_content');
            }
            
            tinymce.init({
                selector: '.tinymce-editor',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code',
                toolbar: 'undo redo | blocks | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | code | removeformat',
                height: 400,
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
                        editor.save(); // Sauvegarde le contenu dans le textarea
                    });
                },
                images_upload_handler: function (blobInfo, success, failure) {
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ url("admin/upload/image") }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    
                    xhr.onload = function() {
                        var json;
                        
                        if (xhr.status != 200) {
                            failure('Erreur HTTP: ' + xhr.status);
                            return;
                        }
                        
                        try {
                            json = JSON.parse(xhr.responseText);
                        } catch (e) {
                            failure('Réponse invalide: ' + xhr.responseText);
                            return;
                        }
                        
                        if (!json || typeof json.location != 'string') {
                            failure('Réponse invalide: ' + xhr.responseText);
                            return;
                        }
                        
                        success(json.location);
                    };
                    
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    
                    xhr.send(formData);
                }
            });
        }

        // Gestion de la prévisualisation des vidéos
        const videoUrlInput = document.getElementById('video_url');
        const videoPreview = document.getElementById('video-preview');
        const videoIframe = document.getElementById('video-iframe');
        
        if (videoUrlInput) {
            videoUrlInput.addEventListener('input', function() {
                updateVideoPreview(this.value);
            });
            
            // Vérifier s'il y a une URL initiale
            if (videoUrlInput.value) {
                updateVideoPreview(videoUrlInput.value);
            }
        }
        
        function updateVideoPreview(url) {
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
            // Handle various YouTube URL formats
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : null;
        }
        
        function getVimeoId(url) {
            // Handle various Vimeo URL formats
            let regExp = /(?:vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?))/;
            let match = url.match(regExp);
            
            if (match && match[2]) {
                return match[2];
            }
            
            // Try another pattern for player.vimeo.com URLs
            regExp = /player\.vimeo\.com\/video\/(\d+)/;
            match = url.match(regExp);
            return match ? match[1] : null;
        }

        // Gestion des fichiers PDF
        const pdfFileInput = document.getElementById('pdf_file');
        const pdfPreview = document.getElementById('pdf-preview');
        const pdfFilename = document.getElementById('pdf-filename');
        const pdfFilesize = document.getElementById('pdf-filesize');
        const pdfFileFeedback = document.getElementById('pdf-file-feedback');
        const clearPdfBtn = document.getElementById('clear-pdf-btn');
        
        if (pdfFileInput) {
            pdfFileInput.addEventListener('change', function() {
                updatePdfPreview(this);
            });
            
            clearPdfBtn.addEventListener('click', function() {
                pdfFileInput.value = '';
                pdfPreview.style.display = 'none';
                pdfFileFeedback.textContent = '';
                pdfFileFeedback.className = 'form-text';
            });
        }
        
        function updatePdfPreview(input) {
            if (!input.files || input.files.length === 0) {
                pdfPreview.style.display = 'none';
                pdfFileFeedback.textContent = '';
                return;
            }
            
            const file = input.files[0];
            
            // Validate file type
            if (file.type !== 'application/pdf') {
                pdfFileFeedback.textContent = 'Le fichier doit être au format PDF.';
                pdfFileFeedback.className = 'text-danger small';
                pdfPreview.style.display = 'none';
                return;
            }
            
            // Validate file size (10MB max)
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                pdfFileFeedback.textContent = 'Le fichier est trop volumineux. Taille maximale: 10MB';
                pdfFileFeedback.className = 'text-danger small';
                pdfPreview.style.display = 'none';
                return;
            }
            
            // Display file info
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

        // Gestion des liens externes
        const externalUrlInput = document.getElementById('external_url');
        const externalPreview = document.getElementById('external-preview');
        const externalTitle = document.getElementById('external-title');
        const externalDescription = document.getElementById('external-description');
        const externalUrlDisplay = document.getElementById('external-url-display');
        const externalPreviewLink = document.getElementById('external-preview-link');
        
        if (externalUrlInput) {
            externalUrlInput.addEventListener('input', function() {
                updateExternalPreview(this.value);
            });
            
            if (externalUrlInput.value) {
                updateExternalPreview(externalUrlInput.value);
            }
        }
        
        function updateExternalPreview(url) {
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
    });
</script>
@endsection
