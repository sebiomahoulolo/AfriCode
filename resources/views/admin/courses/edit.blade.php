@extends('admin.layouts.app')

@section('title', 'Modifier un Cours')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit fa-fw me-1"></i> Modifier le cours
            </h6>
            <div>
                <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-info me-2">
                    <i class="fas fa-eye fa-fw"></i> Voir le cours
                </a>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left fa-fw"></i> Retour à la liste
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Informations générales du cours -->
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Informations générales</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titre du cours <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $course->title) }}" required>
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug (URL personnalisée) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">/cours/</span>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $course->slug) }}" required>
                                    </div>
                                    <div class="form-text">L'URL unique du cours (ex: introduction-a-php)</div>
                                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $course->description) }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">Sélectionnez une catégorie</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="level" class="form-label">Niveau <span class="text-danger">*</span></label>
                                        <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
                                            <option value="">Sélectionnez un niveau</option>
                                            <option value="debutant" {{ old('level', $course->level) == 'debutant' ? 'selected' : '' }}>Débutant</option>
                                            <option value="intermediaire" {{ old('level', $course->level) == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                                            <option value="avance" {{ old('level', $course->level) == 'avance' ? 'selected' : '' }}>Avancé</option>
                                            <option value="expert" {{ old('level', $course->level) == 'expert' ? 'selected' : '' }}>Expert</option>
                                        </select>
                                        @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="duration" class="form-label">Durée estimée (en heures)</label>
                                        <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $course->duration) }}" min="1" step="0.5">
                                        @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="language" class="form-label">Langue <span class="text-danger">*</span></label>
                                        <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                            <option value="fr" {{ old('language', $course->language) == 'fr' ? 'selected' : '' }}>Français</option>
                                            <option value="en" {{ old('language', $course->language) == 'en' ? 'selected' : '' }}>Anglais</option>
                                            <option value="ar" {{ old('language', $course->language) == 'ar' ? 'selected' : '' }}>Arabe</option>
                                            <option value="wo" {{ old('language', $course->language) == 'wo' ? 'selected' : '' }}>Wolof</option>
                                        </select>
                                        @error('language') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contenu du cours -->
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Contenu détaillé</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="content" class="form-label">Contenu complet (Markdown supporté)</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $course->content) }}</textarea>
                                    <div class="form-text">Vous pouvez utiliser la syntaxe Markdown pour formater le contenu</div>
                                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="objectives" class="form-label">Objectifs d'apprentissage</label>
                                    <textarea class="form-control @error('objectives') is-invalid @enderror" id="objectives" name="objectives" rows="3" placeholder="Séparez chaque objectif par une nouvelle ligne">{{ old('objectives', $course->objectives) }}</textarea>
                                    @error('objectives') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Prérequis</label>
                                    <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements" rows="3" placeholder="Séparez chaque prérequis par une nouvelle ligne">{{ old('requirements', $course->requirements) }}</textarea>
                                    @error('requirements') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- SEO et Métadonnées -->
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">SEO et Métadonnées</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Titre SEO</label>
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $course->meta_title) }}">
                                    <div class="form-text">Si vide, le titre du cours sera utilisé</div>
                                    @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Description SEO</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $course->meta_description) }}</textarea>
                                    <div class="form-text">Si vide, la description du cours sera utilisée</div>
                                    @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags (mots-clés)</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags', $course->tags) }}" placeholder="Séparés par des virgules">
                                    @error('tags') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Images et médias -->
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Images et médias</h6>
                            </div>
                            <div class="card-body">
                                @if($course->thumbnail)
                                <div class="mb-3">
                                    <label class="form-label">Image de couverture actuelle</label>
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="img-fluid rounded" style="max-height: 150px;">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="remove_thumbnail" name="remove_thumbnail" value="1">
                                        <label class="form-check-label" for="remove_thumbnail">
                                            Supprimer l'image actuelle
                                        </label>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">{{ $course->thumbnail ? 'Nouvelle image de couverture' : 'Image de couverture' }}</label>
                                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*" {{ !$course->thumbnail ? 'required' : '' }}>
                                    <div class="form-text">Formats: JPG, PNG. Taille recommandée: 1280x720px</div>
                                    @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="preview_video" class="form-label">Vidéo de présentation (URL)</label>
                                    <input type="url" class="form-control @error('preview_video') is-invalid @enderror" id="preview_video" name="preview_video" value="{{ old('preview_video', $course->preview_video) }}" placeholder="https://www.youtube.com/watch?v=...">
                                    <div class="form-text">Lien YouTube ou Vimeo</div>
                                    @error('preview_video') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Paramètres de publication -->
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Paramètres de publication</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="instructor_id" class="form-label">Formateur <span class="text-danger">*</span></label>
                                    <select class="form-select @error('instructor_id') is-invalid @enderror" id="instructor_id" name="instructor_id" required>
                                        <option value="">Sélectionnez un formateur</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }} {{ $instructor->surname }}</option>
                                        @endforeach
                                    </select>
                                    @error('instructor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                        <option value="pending" {{ old('status', $course->status) == 'pending' ? 'selected' : '' }}>En attente de révision</option>
                                        <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Publié</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="published_at" class="form-label">Date de publication</label>
                                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', $course->published_at ? date('Y-m-d\TH:i', strtotime($course->published_at)) : '') }}">
                                    @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="is_featured" class="form-check-label">Mise en avant</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $course->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Afficher ce cours en page d'accueil
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="is_premium" class="form-check-label">Accès premium</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_premium" name="is_premium" value="1" {{ old('is_premium', $course->is_premium) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_premium">
                                            Cours payant (Premium)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Prix et promotions -->
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Prix et promotions</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Prix (FCFA)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $course->price) }}" min="0">
                                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="discounted_price" class="form-label">Prix promotionnel (FCFA)</label>
                                    <input type="number" class="form-control @error('discounted_price') is-invalid @enderror" id="discounted_price" name="discounted_price" value="{{ old('discounted_price', $course->discounted_price) }}" min="0">
                                    <div class="form-text">Laisser vide si pas de promotion</div>
                                    @error('discounted_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="discount_starts_at" class="form-label">Début promotion</label>
                                        <input type="datetime-local" class="form-control @error('discount_starts_at') is-invalid @enderror" id="discount_starts_at" name="discount_starts_at" value="{{ old('discount_starts_at', $course->discount_starts_at ? date('Y-m-d\TH:i', strtotime($course->discount_starts_at)) : '') }}">
                                        @error('discount_starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="discount_ends_at" class="form-label">Fin promotion</label>
                                        <input type="datetime-local" class="form-control @error('discount_ends_at') is-invalid @enderror" id="discount_ends_at" name="discount_ends_at" value="{{ old('discount_ends_at', $course->discount_ends_at ? date('Y-m-d\TH:i', strtotime($course->discount_ends_at)) : '') }}">
                                        @error('discount_ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistiques -->
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <div class="stat-card p-2 border rounded bg-light text-center">
                                            <h3 class="m-0">{{ $course->enrollments_count ?? 0 }}</h3>
                                            <p class="text-muted mb-0">Inscrits</p>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stat-card p-2 border rounded bg-light text-center">
                                            <h3 class="m-0">{{ $course->average_rating ?? 0 }}/5</h3>
                                            <p class="text-muted mb-0">Note moyenne</p>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stat-card p-2 border rounded bg-light text-center">
                                            <h3 class="m-0">{{ $course->completion_rate ?? 0 }}%</h3>
                                            <p class="text-muted mb-0">Taux de complétion</p>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stat-card p-2 border rounded bg-light text-center">
                                            <h3 class="m-0">{{ $course->views_count ?? 0 }}</h3>
                                            <p class="text-muted mb-0">Vues</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex mt-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save fa-fw"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Script pour générer automatiquement le slug à partir du titre
    document.getElementById('title').addEventListener('keyup', function() {
        // Ne génère le slug que si l'utilisateur n'a pas déjà modifié le champ slug
        if (document.getElementById('slug').dataset.userModified !== 'true') {
            const title = this.value;
            const slug = title.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            document.getElementById('slug').value = slug;
        }
    });

    // Marquer le champ slug comme modifié manuellement
    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.userModified = 'true';
    });
</script>
@endsection
