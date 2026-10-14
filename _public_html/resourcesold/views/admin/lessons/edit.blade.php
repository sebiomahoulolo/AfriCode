@extends('admin.layouts.app')

@section('breadcrumb', 'Modifier la leçon')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-edit"></i> Modifier la leçon: {{ $lesson->title }}
                </h1>
                <p class="admin-card-subtitle">
                    Module: {{ $module->title }} - Cours: {{ $course->title }}
                </p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('admin.courses.show', $course->id) }}" 
                   style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-block;">
                    <i class="fas fa-arrow-left"></i> Retour au cours
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-card-body">
            <form action="{{ route('admin.lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data" id="lessonForm">
                @csrf
                @method('PUT')

                <!-- Informations générales -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="admin-card-title" style="font-size: 1.2rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #E9ECEF;">
                        <i class="fas fa-info-circle"></i> Informations générales
                    </h3>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label for="title" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                            Titre de la leçon <span style="color: #fa1c39;">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $lesson->title) }}" required
                               style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;"
                               onfocus="this.style.borderColor='#11c06d'; this.style.boxShadow='0 0 0 3px rgba(17, 192, 109, 0.1)';"
                               onblur="this.style.borderColor='#E9ECEF'; this.style.boxShadow='none';">
                        @error('title')
                            <div style="color: #fa1c39; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                        <div>
                            <label for="type" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Type de contenu <span style="color: #fa1c39;">*</span>
                            </label>
                            <select id="type" name="type" required
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; background: white; transition: all 0.3s;"
                                    onfocus="this.style.borderColor='#11c06d'; this.style.boxShadow='0 0 0 3px rgba(17, 192, 109, 0.1)';"
                                    onblur="this.style.borderColor='#E9ECEF'; this.style.boxShadow='none';">
                                <option value="video" {{ old('type', $lesson->type) === 'video' ? 'selected' : '' }}>Vidéo</option>
                                <option value="text" {{ old('type', $lesson->type) === 'text' ? 'selected' : '' }}>Texte</option>
                                <option value="pdf" {{ old('type', $lesson->type) === 'pdf' ? 'selected' : '' }}>PDF</option>
                            </select>
                            @error('type')
                                <div style="color: #fa1c39; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="order" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Ordre</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $lesson->order) }}" min="1"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;"
                                   onfocus="this.style.borderColor='#11c06d'; this.style.boxShadow='0 0 0 3px rgba(17, 192, 109, 0.1)';"
                                   onblur="this.style.borderColor='#E9ECEF'; this.style.boxShadow='none';">
                            <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Position dans le module</div>
                            @error('order')
                                <div style="color: #fa1c39; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="duration" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Durée (minutes)</label>
                            <input type="number" id="duration" name="duration" value="{{ old('duration', $lesson->duration) }}" min="1"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;"
                                   onfocus="this.style.borderColor='#11c06d'; this.style.boxShadow='0 0 0 3px rgba(17, 192, 109, 0.1)';"
                                   onblur="this.style.borderColor='#E9ECEF'; this.style.boxShadow='none';">
                            @error('duration')
                                <div style="color: #fa1c39; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" id="is_previewable" name="is_previewable" {{ old('is_previewable', $lesson->is_previewable) ? 'checked' : '' }}
                                   style="width: 18px; height: 18px; cursor: pointer;">
                            <span style="font-weight: 500; color: #333;">Prévisualisable gratuitement</span>
                        </label>
                        <div style="font-size: 0.85rem; color: #6C757D; margin-top: 0.25rem; margin-left: 1.75rem;">
                            Si coché, cette leçon sera visible sans inscription ou paiement
                        </div>
                    </div>
                </div>

                <!-- Contenu de la leçon -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="admin-card-title" style="font-size: 1.2rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #E9ECEF;">
                        <i class="fas fa-file-alt"></i> Contenu de la leçon
                    </h3>
                    
                    <!-- Contenu Vidéo -->
                    <div id="video-content" class="content-section" style="display: {{ old('type', $lesson->type) === 'video' ? 'block' : 'none' }};">
                        <div style="background: #F8F9FA; border-radius: 8px; padding: 1.5rem; border-left: 4px solid #fa1c39;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                                <i class="fas fa-video" style="color: #fa1c39; font-size: 1.5rem;"></i>
                                <h4 style="margin: 0; color: #333;">Contenu Vidéo</h4>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label for="video_url" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                    URL de la vidéo (YouTube ou Vimeo) <span style="color: #fa1c39;">*</span>
                                </label>
                                <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $lesson->type === 'video' ? $lesson->content : '') }}" 
                                       placeholder="https://www.youtube.com/watch?v=..."
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;"
                                       onfocus="this.style.borderColor='#11c06d'; this.style.boxShadow='0 0 0 3px rgba(17, 192, 109, 0.1)';"
                                       onblur="this.style.borderColor='#E9ECEF'; this.style.boxShadow='none';">
                                <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Collez le lien direct vers la vidéo YouTube ou Vimeo</div>
                                @error('video_url')
                                    <div style="color: #fa1c39; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div id="video-preview" style="display: none; margin-top: 1rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Aperçu de la vidéo:</label>
                                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; background: #000;">
                                    <iframe id="video-iframe" src="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu Texte -->
                    <div id="text-content" class="content-section" style="display: {{ old('type', $lesson->type) === 'text' ? 'block' : 'none' }};">
                        <div style="background: #F8F9FA; border-radius: 8px; padding: 1.5rem; border-left: 4px solid #239e79;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                                <i class="fas fa-file-alt" style="color: #239e79; font-size: 1.5rem;"></i>
                                <h4 style="margin: 0; color: #333;">Contenu Texte</h4>
                            </div>
                            <div>
                                <label for="text_content" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                    Contenu texte <span style="color: #fa1c39;">*</span>
                                </label>
                                <textarea id="text_content" name="text_content" rows="10"
                                          style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s; resize: vertical;"
                                          onfocus="this.style.borderColor='#11c06d'; this.style.boxShadow='0 0 0 3px rgba(17, 192, 109, 0.1)';"
                                          onblur="this.style.borderColor='#E9ECEF'; this.style.boxShadow='none';">{{ old('text_content', $lesson->type === 'text' ? $lesson->content : '') }}</textarea>
                                @error('text_content')
                                    <div style="color: #fa1c39; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contenu PDF -->
                    <div id="pdf-content" class="content-section" style="display: {{ old('type', $lesson->type) === 'pdf' ? 'block' : 'none' }};">
                        <div style="background: #F8F9FA; border-radius: 8px; padding: 1.5rem; border-left: 4px solid #fea837;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                                <i class="fas fa-file-pdf" style="color: #fea837; font-size: 1.5rem;"></i>
                                <h4 style="margin: 0; color: #333;">Document PDF</h4>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label for="pdf_file" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Fichier PDF</label>
                                <div style="display: flex; gap: 0.5rem;">
                                    <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf"
                                           style="flex: 1; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s;"
                                           onfocus="this.style.borderColor='#11c06d'; this.style.boxShadow='0 0 0 3px rgba(17, 192, 109, 0.1)';"
                                           onblur="this.style.borderColor='#E9ECEF'; this.style.boxShadow='none';">
                                    <button type="button" id="clear-pdf-btn" 
                                            style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                                        Effacer
                                    </button>
                                </div>
                                <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Laissez vide pour conserver le fichier PDF existant</div>
                                <div id="pdf-file-feedback" style="font-size: 0.85rem; margin-top: 0.25rem;"></div>
                                @error('pdf_file')
                                    <div style="color: #fa1c39; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            @if($lesson->type === 'pdf' && $lesson->content)
                                <div style="background: white; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; border: 1px solid #E9ECEF;">
                                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                                        <i class="fas fa-file-pdf" style="color: #fa1c39; font-size: 2rem;"></i>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">Document PDF actuel</div>
                                            <div style="font-size: 0.85rem; color: #6C757D;">{{ basename($lesson->content) }}</div>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ Storage::url($lesson->content) }}" target="_blank" 
                                           style="background: linear-gradient(135deg, #11c06d, #239e79); color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                                            <i class="fas fa-eye"></i> Visualiser
                                        </a>
                                        <a href="{{ Storage::url($lesson->content) }}" download 
                                           style="background: #6C757D; color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            <div id="pdf-preview" style="display: none; background: white; border-radius: 8px; padding: 1rem; border: 1px solid #E9ECEF;">
                                <div style="font-weight: 600; color: #333; margin-bottom: 0.75rem;">Nouveau fichier PDF</div>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <i class="fas fa-file-pdf" style="color: #fa1c39; font-size: 2rem;"></i>
                                    <div>
                                        <div style="font-weight: 500; color: #333; margin-bottom: 0.25rem;" id="pdf-filename">document.pdf</div>
                                        <div style="font-size: 0.85rem; color: #6C757D;" id="pdf-filesize">0 Ko</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 2px solid #E9ECEF;">
                    <div style="display: flex; gap: 0.75rem;">
                        <button type="submit" 
                                style="background: linear-gradient(135deg, #11c06d, #239e79); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.courses.show', $course->id) }}" 
                           style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-block;">
                            Annuler
                        </a>
                    </div>
                    <button type="button" onclick="openModal('deleteLessonModal')" 
                            style="background: linear-gradient(135deg, #fa1c39, #FF6B6B); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                        <i class="fas fa-trash"></i> Supprimer la leçon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div id="deleteLessonModal" class="admin-confirm-modal" style="display: none;">
    <div class="admin-confirm-backdrop" onclick="closeModal('deleteLessonModal')"></div>
    <div class="admin-confirm-dialog" style="max-width: 500px;">
        <div class="admin-confirm-header">
            <h6>Confirmation de suppression</h6>
        </div>
        <div class="admin-confirm-body">
            <div style="background: #FFEBEE; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #fa1c39;">
                <div style="font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 0.25rem;">{{ $lesson->title }}</div>
                <div style="font-size: 0.85rem; color: #6C757D;">Êtes-vous sûr de vouloir supprimer cette leçon ?</div>
            </div>
            <div style="color: #fa1c39; font-size: 0.9rem; font-weight: 500;">
                <i class="fas fa-exclamation-triangle"></i> Cette action est irréversible.
            </div>
        </div>
        <div class="admin-confirm-footer">
            <button type="button" onclick="closeModal('deleteLessonModal')" class="admin-btn admin-btn-secondary">Annuler</button>
            <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #fa1c39, #FF6B6B);">Supprimer définitivement</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const contentTypeSelect = document.getElementById('type');
        const initialContentType = contentTypeSelect.value;
        
        // Initialiser l'affichage
        updateContentDisplay(initialContentType);
        
        // Gestionnaire de changement du type de contenu
        contentTypeSelect.addEventListener('change', function() {
            updateContentDisplay(this.value);
        });
        
        function updateContentDisplay(contentType) {
            const videoContent = document.getElementById('video-content');
            const textContent = document.getElementById('text-content');
            const pdfContent = document.getElementById('pdf-content');
            
            // Masquer tous les contenus
            videoContent.style.display = 'none';
            textContent.style.display = 'none';
            pdfContent.style.display = 'none';
            
            // Afficher le contenu approprié
            switch(contentType) {
                case 'video':
                    videoContent.style.display = 'block';
                    const videoUrl = document.getElementById('video_url').value;
                    if (videoUrl) {
                        updateVideoPreview(videoUrl);
                    }
                    break;
                case 'text':
                    textContent.style.display = 'block';
                    break;
                case 'pdf':
                    pdfContent.style.display = 'block';
                    break;
            }
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
                pdfFileFeedback.style.color = '#fa1c39';
                pdfPreview.style.display = 'none';
                return;
            }
            
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                pdfFileFeedback.textContent = 'Le fichier est trop volumineux. Taille maximale: 10MB';
                pdfFileFeedback.style.color = '#fa1c39';
                pdfPreview.style.display = 'none';
                return;
            }
            
            pdfFilename.textContent = file.name;
            pdfFilesize.textContent = formatFileSize(file.size);
            pdfPreview.style.display = 'block';
            pdfFileFeedback.textContent = 'Fichier PDF valide';
            pdfFileFeedback.style.color = '#239e79';
        }
        
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' octets';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(2) + ' Ko';
            else return (bytes / 1048576).toFixed(2) + ' Mo';
        }
        
        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.admin-confirm-modal').forEach(modal => {
                    if (modal.style.display === 'flex') {
                        closeModal(modal.id);
                    }
                });
            }
        });
        
        // Hover effects pour les boutons
        document.querySelectorAll('button[style*="background: linear-gradient"], a[style*="background: linear-gradient"]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
@endpush
