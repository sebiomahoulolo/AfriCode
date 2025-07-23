 @extends('admin.layouts.app')

@section('title', 'Créer un Cours')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="admin-content-header">
        <div class="admin-content-header-content">
            <h1 class="admin-content-title">
                <i class="fas fa-plus-circle"></i> Créer un nouveau cours
            </h1>
            <nav class="admin-breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.courses.index') }}">Cours</a>
                <span>/</span>
                <span>Nouveau</span>
            </nav>
        </div>
        <div class="admin-content-actions">
            <a href="{{ route('admin.courses.index') }}" class="admin-button admin-button-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Main Form -->
    <div class="admin-form-container">
        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf

            <div class="admin-form-grid">
                <!-- Left Column -->
                <div class="admin-form-column">
                    <!-- Informations de base -->
                    <div class="admin-form-section">
                        <h3 class="admin-form-section-title">
                            <i class="fas fa-info-circle"></i> Informations de base
                        </h3>
                        
                        <div class="admin-form-group">
                            <label for="title" class="admin-form-label">Titre du cours *</label>
                            <input type="text" class="admin-form-input @error('title') error @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="slug" class="admin-form-label">Slug (URL personnalisée)</label>
                            <input type="text" class="admin-form-input @error('slug') error @enderror" 
                                   id="slug" name="slug" value="{{ old('slug') }}" 
                                   placeholder="sera-generé-automatiquement">
                            <div class="admin-form-hint">Le slug sera généré automatiquement à partir du titre si laissé vide</div>
                            @error('slug') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="description" class="admin-form-label">Description *</label>
                            <textarea class="admin-form-textarea @error('description') error @enderror" 
                                      id="description" name="description" required>{{ old('description') }}</textarea>
                            @error('description') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Contenu du cours -->
                    <div class="admin-form-section">
                        <h3 class="admin-form-section-title">
                            <i class="fas fa-book-open"></i> Contenu détaillé
                        </h3>
                        
                        <div class="admin-form-group">
                            <label for="content" class="admin-form-label">Contenu complet (Markdown supporté)</label>
                            <textarea class="admin-form-textarea @error('content') error @enderror" 
                                      id="content" name="content" rows="10">{{ old('content') }}</textarea>
                            <div class="admin-form-hint">Vous pouvez utiliser la syntaxe Markdown pour formater le contenu</div>
                            @error('content') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="objectives" class="admin-form-label">Objectifs d'apprentissage</label>
                            <textarea class="admin-form-textarea @error('objectives') error @enderror" 
                                      id="objectives" name="objectives" rows="3" 
                                      placeholder="Séparez chaque objectif par une nouvelle ligne">{{ old('objectives') }}</textarea>
                            @error('objectives') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="requirements" class="admin-form-label">Prérequis</label>
                            <textarea class="admin-form-textarea @error('requirements') error @enderror" 
                                      id="requirements" name="requirements" rows="3" 
                                      placeholder="Séparez chaque prérequis par une nouvelle ligne">{{ old('requirements') }}</textarea>
                            @error('requirements') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- SEO et Métadonnées -->
                    <div class="admin-form-section">
                        <h3 class="admin-form-section-title">
                            <i class="fas fa-search"></i> SEO et Métadonnées
                        </h3>
                        
                        <div class="admin-form-group">
                            <label for="meta_title" class="admin-form-label">Titre SEO</label>
                            <input type="text" class="admin-form-input @error('meta_title') error @enderror" 
                                   id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                            <div class="admin-form-hint">Si vide, le titre du cours sera utilisé</div>
                            @error('meta_title') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="meta_description" class="admin-form-label">Description SEO</label>
                            <textarea class="admin-form-textarea @error('meta_description') error @enderror" 
                                      id="meta_description" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                            <div class="admin-form-hint">Si vide, la description du cours sera utilisée</div>
                            @error('meta_description') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="tags" class="admin-form-label">Tags (mots-clés)</label>
                            <input type="text" class="admin-form-input @error('tags') error @enderror" 
                                   id="tags" name="tags" value="{{ old('tags') }}" 
                                   placeholder="Séparés par des virgules">
                            @error('tags') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="admin-form-column">
                    <!-- Images et médias -->
                    <div class="admin-form-section">
                        <h3 class="admin-form-section-title">
                            <i class="fas fa-images"></i> Images et médias
                        </h3>
                        
                        <div class="admin-form-group">
                            <label for="thumbnail" class="admin-form-label">Image de couverture *</label>
                            <input type="file" class="admin-form-input @error('thumbnail') error @enderror" 
                                   id="thumbnail" name="thumbnail" accept="image/*" required>
                            <div class="admin-form-hint">Formats: JPG, PNG. Taille recommandée: 1280x720px</div>
                            @error('thumbnail') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="preview_video" class="admin-form-label">Vidéo de présentation (URL)</label>
                            <input type="url" class="admin-form-input @error('preview_video') error @enderror" 
                                   id="preview_video" name="preview_video" value="{{ old('preview_video') }}" 
                                   placeholder="https://www.youtube.com/watch?v=...">
                            <div class="admin-form-hint">Lien YouTube ou Vimeo</div>
                            @error('preview_video') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Catégorie et niveau -->
                    <div class="admin-form-section">
                        <h3 class="admin-form-section-title">
                            <i class="fas fa-tags"></i> Catégorie et niveau
                        </h3>
                        
                        <div class="admin-form-group">
                            <label for="category_id" class="admin-form-label">Catégorie *</label>
                            <select class="admin-form-select @error('category_id') error @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="level" class="admin-form-label">Niveau *</label>
                            <select class="admin-form-select @error('level') error @enderror" 
                                    id="level" name="level" required>
                                <option value="">Sélectionnez un niveau</option>
                                <option value="debutant" {{ old('level') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermediaire" {{ old('level') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="avance" {{ old('level') == 'avance' ? 'selected' : '' }}>Avancé</option>
                                <option value="expert" {{ old('level') == 'expert' ? 'selected' : '' }}>Expert</option>
                            </select>
                            @error('level') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="language" class="admin-form-label">Langue *</label>
                            <select class="admin-form-select @error('language') error @enderror" 
                                    id="language" name="language" required>
                                <option value="fr" {{ old('language') == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>Anglais</option>
                                <option value="ar" {{ old('language') == 'ar' ? 'selected' : '' }}>Arabe</option>
                                <option value="wo" {{ old('language') == 'wo' ? 'selected' : '' }}>Wolof</option>
                            </select>
                            @error('language') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Paramètres de publication -->
                    <div class="admin-form-section">
                        <h3 class="admin-form-section-title">
                            <i class="fas fa-cog"></i> Paramètres de publication
                        </h3>
                        
                        <div class="admin-form-group">
                            <label for="instructor_id" class="admin-form-label">Formateur *</label>
                            <select class="admin-form-select @error('instructor_id') error @enderror" 
                                    id="instructor_id" name="instructor_id" required>
                                <option value="">Sélectionnez un formateur</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->name }} {{ $instructor->surname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructor_id') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="status" class="admin-form-label">Statut *</label>
                            <select class="admin-form-select @error('status') error @enderror" 
                                    id="status" name="status" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>En attente de révision</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publié</option>
                            </select>
                            @error('status') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="published_at" class="admin-form-label">Date de publication</label>
                            <input type="datetime-local" class="admin-form-input @error('published_at') error @enderror" 
                                   id="published_at" name="published_at" value="{{ old('published_at') }}">
                            <div class="admin-form-hint">Laisser vide pour publier immédiatement</div>
                            @error('published_at') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group admin-form-checkbox-col" style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                            <label class="admin-form-checkbox" style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                Mise en avant (Afficher en page d'accueil)
                            </label>
                            <label class="admin-form-checkbox" style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="is_premium" name="is_premium" value="1" {{ old('is_premium') ? 'checked' : '' }}>
                                Accès premium (Cours payant)
                            </label>
                            <label class="admin-form-checkbox" style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="is_certifying" name="is_certifying" value="1" {{ old('is_certifying') ? 'checked' : '' }}>
                                Donne droit à une certification
                            </label>
                        </div>
                    </div>

                    <!-- Prix et promotions -->
                    <div class="admin-form-section">
                        <h3 class="admin-form-section-title">
                            <i class="fas fa-money-bill-wave"></i> Prix et promotions
                        </h3>
                        <div class="admin-form-group">
                            <label for="price" class="admin-form-label">Prix (FCFA)</label>
                            <input type="number" class="admin-form-input @error('price') error @enderror" 
                                   id="price" name="price" value="{{ old('price', 0) }}" min="0">
                            @error('price') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="discounted_price" class="admin-form-label">Prix promotionnel (FCFA)</label>
                            <input type="number" class="admin-form-input @error('discounted_price') error @enderror" 
                                   id="discounted_price" name="discounted_price" value="{{ old('discounted_price') }}" min="0">
                            <div class="admin-form-hint">Laisser vide si pas de promotion</div>
                            @error('discounted_price') 
                                <span class="admin-form-error">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="admin-form-grid">
                            <div class="admin-form-group">
                                <label for="discount_starts_at" class="admin-form-label">Début promotion</label>
                                <input type="datetime-local" class="admin-form-input @error('discount_starts_at') error @enderror" 
                                       id="discount_starts_at" name="discount_starts_at" value="{{ old('discount_starts_at') }}">
                                @error('discount_starts_at') 
                                    <span class="admin-form-error">{{ $message }}</span> 
                                @enderror
                            </div>
                            
                            <div class="admin-form-group">
                                <label for="discount_ends_at" class="admin-form-label">Fin promotion</label>
                                <input type="datetime-local" class="admin-form-input @error('discount_ends_at') error @enderror" 
                                       id="discount_ends_at" name="discount_ends_at" value="{{ old('discount_ends_at') }}">
                                @error('discount_ends_at') 
                                    <span class="admin-form-error">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="admin-form-actions">
                <button type="submit" class="admin-button admin-button-primary">
                    <i class="fas fa-save"></i> Enregistrer le cours
                </button>
                <button type="submit" name="save_draft" value="1" class="admin-button admin-button-secondary">
                    <i class="fas fa-save"></i> Enregistrer comme brouillon
                </button>
                <a href="{{ route('admin.courses.index') }}" class="admin-button admin-button-light">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script pour générer automatiquement le slug à partir du titre
    document.getElementById('title').addEventListener('keyup', function() {
        const title = this.value;
        const slug = title.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('slug').value = slug;
    });

    // Afficher/masquer les champs de prix selon le type de cours
    const premiumCheckbox = document.getElementById('is_premium');
    const priceFields = document.querySelectorAll('[id^="price"], [id^="discount"]');
    
    function togglePriceFields() {
        priceFields.forEach(field => {
            field.disabled = !premiumCheckbox.checked;
            field.closest('.admin-form-group').style.opacity = premiumCheckbox.checked ? '1' : '0.6';
        });
    }
    
    premiumCheckbox.addEventListener('change', togglePriceFields);
    togglePriceFields(); // Initial state
</script>
@endpush