@extends('admin.layouts.app')

@section('breadcrumb', 'Modifier le cours')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-edit me-2"></i>Modifier le cours
                </h1>
                <p class="admin-card-subtitle">{{ $course->title }}</p>
            </div>
            <div class="admin-card-actions">
                <a href="{{ route('admin.courses.show', $course) }}" 
                   style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; margin-right: 0.5rem;">
                    <i class="fas fa-eye me-2"></i>Voir le cours
                </a>
                <a href="{{ route('admin.courses.index') }}" 
                   style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Information Message -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="50" style="margin-bottom: 1rem;">
            <div class="admin-card-body" style="padding: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1)); border-radius: 8px; border-left: 4px solid #1EA38B;">
                    <i class="fas fa-info-circle" style="color: #1EA38B; font-size: 1.2rem;"></i>
                    <div>
                        <p style="margin: 0; font-weight: 600; color: #333; font-size: 0.95rem;">Mise à jour flexible</p>
                        <p style="margin: 0; color: #6C757D; font-size: 0.85rem;">Seuls le titre et le slug sont obligatoires. Vous pouvez modifier uniquement les champs souhaités.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="admin-grid admin-grid-3">
            <!-- Main Information -->
            <div class="admin-card admin-span-2" data-aos="fade-up" data-aos-delay="100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <i class="fas fa-info-circle me-2"></i>Informations principales
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="title" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            Titre du cours <span style="color: #E32D31;">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $course->title) }}" 
                               required
                               style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                        @error('title')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="slug" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            Slug (URL) <span style="color: #E32D31;">*</span>
                        </label>
                        <div style="display: flex; align-items: center; border: 2px solid #E9ECEF; border-radius: 8px; overflow: hidden;">
                            <span style="background: #F8F9FA; padding: 0.75rem 1rem; border-right: 1px solid #E9ECEF; color: #6C757D; font-weight: 500;">/cours/</span>
                            <input type="text" 
                                   class="@error('slug') is-invalid @enderror" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug', $course->slug) }}" 
                                   required
                                   style="flex: 1; padding: 0.75rem 1rem; border: none; outline: none; font-size: 0.95rem;">
                        </div>
                        <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">L'URL unique du cours (ex: php-pour-debutants)</div>
                        @error('slug')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="short_description" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            Description courte
                        </label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                  id="short_description" 
                                  name="short_description" 
                                  rows="3" 
                                  placeholder="Description courte du cours..."
                                  style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; resize: vertical; transition: all 0.3s;">{{ old('short_description', $course->short_description) }}</textarea>
                        <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">Maximum 500 caractères</div>
                        @error('short_description')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="full_description" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            Description complète
                        </label>
                        <textarea class="form-control @error('full_description') is-invalid @enderror" 
                                  id="full_description" 
                                  name="full_description" 
                                  rows="6" 
                                  placeholder="Description détaillée du cours..."
                                  style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; resize: vertical; transition: all 0.3s;">{{ old('full_description', $course->full_description) }}</textarea>
                        @error('full_description')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Objectifs d'apprentissage -->
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="learning_objectives" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            <i class="fas fa-target me-2"></i>Objectifs d'apprentissage
                        </label>
                        <textarea class="form-control @error('learning_objectives') is-invalid @enderror" 
                                  id="learning_objectives" 
                                  name="learning_objectives" 
                                  rows="4" 
                                  placeholder="Décrivez ce que les étudiants apprendront dans ce cours..."
                                  style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; resize: vertical; transition: all 0.3s;">{{ old('learning_objectives', $course->learning_objectives) }}</textarea>
                        <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">Séparez chaque objectif par une nouvelle ligne</div>
                        @error('learning_objectives')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prérequis -->
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="prerequisites" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            <i class="fas fa-list-check me-2"></i>Prérequis
                        </label>
                        <textarea class="form-control @error('prerequisites') is-invalid @enderror" 
                                  id="prerequisites" 
                                  name="prerequisites" 
                                  rows="3" 
                                  placeholder="Quels sont les prérequis pour suivre ce cours..."
                                  style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; resize: vertical; transition: all 0.3s;">{{ old('prerequisites', $course->prerequisites) }}</textarea>
                        <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">Séparez chaque prérequis par une nouvelle ligne</div>
                        @error('prerequisites')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-grid admin-grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="category_id" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                Catégorie
                            </label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="level" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                Niveau
                            </label>
                            <select class="form-control @error('level') is-invalid @enderror" 
                                    id="level" 
                                    name="level" 
                                    style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                                <option value="">Sélectionner un niveau</option>
                                <option value="débutant" {{ old('level', $course->level) == 'débutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermédiaire" {{ old('level', $course->level) == 'intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="avancé" {{ old('level', $course->level) == 'avancé' ? 'selected' : '' }}>Avancé</option>
                                <option value="expert" {{ old('level', $course->level) == 'expert' ? 'selected' : '' }}>Expert</option>
                            </select>
                            @error('level')
                                <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="admin-grid admin-grid-2" style="gap: 1.5rem;">
                        <div class="form-group">
                            <label for="price" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                Prix
                            </label>
                            <div style="display: flex; align-items: center; border: 2px solid #E9ECEF; border-radius: 8px; overflow: hidden;">
                                <input type="number" 
                                       class="@error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $course->price) }}" 
                                       min="0" 
                                       step="0.01" 
                                       placeholder="0"
                                       style="flex: 1; padding: 0.75rem 1rem; border: none; outline: none; font-size: 0.95rem;">
                                <span style="background: #F8F9FA; padding: 0.75rem 1rem; border-left: 1px solid #E9ECEF; color: #6C757D; font-weight: 500;">{{ $course->currency ?? 'EUR' }}</span>
                            </div>
                            <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">Mettre 0 pour un cours gratuit</div>
                            @error('price')
                                <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="currency" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                Devise
                            </label>
                            <select class="form-control @error('currency') is-invalid @enderror" 
                                    id="currency" 
                                    name="currency"
                                    style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                                <option value="EUR" {{ old('currency', $course->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="USD" {{ old('currency', $course->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="XOF" {{ old('currency', $course->currency) == 'XOF' ? 'selected' : '' }}>XOF (FCFA)</option>
                            </select>
                            @error('currency')
                                <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations additionnelles -->
                    <div class="admin-grid admin-grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="duration" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-clock me-2"></i>Durée estimée (en heures)
                            </label>
                            <input type="number" 
                                   class="form-control @error('duration') is-invalid @enderror" 
                                   id="duration" 
                                   name="duration" 
                                   value="{{ old('duration', $course->duration) }}" 
                                   min="1" 
                                   step="0.5"
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                            <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">Durée totale du cours</div>
                            @error('duration')
                                <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="language" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-language me-2"></i>Langue
                            </label>
                            <select class="form-control @error('language') is-invalid @enderror" 
                                    id="language" 
                                    name="language" 
                                    style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                                <option value="">Sélectionner une langue</option>
                                <option value="fr" {{ old('language', $course->language) == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ old('language', $course->language) == 'en' ? 'selected' : '' }}>Anglais</option>
                                <option value="ar" {{ old('language', $course->language) == 'ar' ? 'selected' : '' }}>Arabe</option>
                                <option value="wo" {{ old('language', $course->language) == 'wo' ? 'selected' : '' }}>Wolof</option>
                            </select>
                            @error('language')
                                <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Contenu détaillé -->
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="content" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            <i class="fas fa-file-text me-2"></i>Contenu complet
                        </label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="8" 
                                  placeholder="Contenu détaillé du cours (Markdown supporté)..."
                                  style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; resize: vertical; transition: all 0.3s;">{{ old('content', $course->content) }}</textarea>
                        <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">Vous pouvez utiliser la syntaxe Markdown pour formater le contenu</div>
                        @error('content')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SEO et métadonnées -->
                    <div class="admin-grid admin-grid-2" style="gap: 1.5rem;">
                        <div class="form-group">
                            <label for="meta_title" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-search me-2"></i>Titre SEO
                            </label>
                            <input type="text" 
                                   class="form-control @error('meta_title') is-invalid @enderror" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   value="{{ old('meta_title', $course->meta_title) }}" 
                                   placeholder="Titre pour les moteurs de recherche..."
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                            <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">Si vide, le titre du cours sera utilisé</div>
                            @error('meta_title')
                                <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tags" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                                <i class="fas fa-tags me-2"></i>Tags (mots-clés)
                            </label>
                            <input type="text" 
                                   class="form-control @error('tags') is-invalid @enderror" 
                                   id="tags" 
                                   name="tags" 
                                   value="{{ old('tags', $course->tags) }}" 
                                   placeholder="Séparés par des virgules..."
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                            @error('tags')
                                <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <i class="fas fa-cog me-2"></i>Paramètres
                    </h3>
                </div>
                <div class="admin-card-body">
                    <!-- Course Image -->
                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 1rem;">
                            <i class="fas fa-image me-2"></i>Image de couverture
                        </label>
                        
                        @if($course->cover_image_path)
                            <div style="margin-bottom: 1rem;">
                                <img src="{{ asset($course->cover_image_path) }}" 
                                     alt="Image actuelle" 
                                     style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #E9ECEF;">
                                <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem; text-align: center;">Image actuelle</div>
                            </div>
                        @endif
                        
                        <input type="file" 
                               class="form-control @error('cover_image') is-invalid @enderror" 
                               id="cover_image" 
                               name="cover_image" 
                               accept="image/*"
                               style="width: 100%; padding: 0.75rem; border: 2px dashed #E9ECEF; border-radius: 8px; font-size: 0.95rem; background: #F8F9FA;">
                        <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">JPG, PNG, GIF - Max 2MB</div>
                        @error('cover_image')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Video Preview -->
                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label for="preview_video" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            <i class="fas fa-video me-2"></i>Vidéo de présentation
                        </label>
                        <input type="url" 
                               class="form-control @error('preview_video') is-invalid @enderror" 
                               id="preview_video" 
                               name="preview_video" 
                               value="{{ old('preview_video', $course->preview_video) }}" 
                               placeholder="https://www.youtube.com/watch?v=..."
                               style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                        <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">Lien YouTube ou Vimeo</div>
                        @error('preview_video')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label for="status" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            <i class="fas fa-flag me-2"></i>Statut
                        </label>
                        <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status"
                                style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                            <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Publié</option>
                        </select>
                        @error('status')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Certification -->
                    <div class="form-group" style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #F8F9FA; border-radius: 8px; border-left: 4px solid #1EA38B;">
                            <input type="checkbox" 
                                   id="is_certifying" 
                                   name="is_certifying" 
                                   value="1" 
                                   {{ old('is_certifying', $course->is_certifying) ? 'checked' : '' }}
                                   style="width: 18px; height: 18px; accent-color: #1EA38B;">
                            <label for="is_certifying" style="font-weight: 600; color: #333; margin: 0; cursor: pointer;">
                                <i class="fas fa-certificate me-2 text-success"></i>Cours certifiant
                            </label>
                        </div>
                        <div style="font-size: 0.875rem; color: #6C757D; margin-top: 0.5rem;">Les étudiants recevront un certificat à la fin</div>
                    </div>

                    <!-- Formateur -->
                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label for="formateur_id" style="display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                            <i class="fas fa-user me-2"></i>Formateur
                        </label>
                        <select class="form-control @error('formateur_id') is-invalid @enderror" 
                                id="formateur_id" 
                                name="formateur_id"
                                style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E9ECEF; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s;">
                            @foreach(\App\Models\User::where('role', 'formateur')->get() as $formateur)
                                <option value="{{ $formateur->id }}" {{ old('formateur_id', $course->formateur_id) == $formateur->id ? 'selected' : '' }}>
                                    {{ $formateur->first_name }} {{ $formateur->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('formateur_id')
                            <div style="color: #E32D31; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Paramètres avancés -->
                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 1rem;">
                            <i class="fas fa-cogs me-2"></i>Options avancées
                        </label>
                        
                        <!-- Date de publication -->
                        <div style="margin-bottom: 1rem;">
                            <label for="published_at" style="display: block; font-weight: 500; color: #555; margin-bottom: 0.5rem;">
                                Date de publication
                            </label>
                            <input type="datetime-local" 
                                   class="form-control @error('published_at') is-invalid @enderror" 
                                   id="published_at" 
                                   name="published_at" 
                                   value="{{ old('published_at', $course->published_at ? date('Y-m-d\TH:i', strtotime($course->published_at)) : '') }}"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #E9ECEF; border-radius: 6px; font-size: 0.9rem;">
                            @error('published_at')
                                <div style="color: #E32D31; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Options checkboxes -->
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: #F8F9FA; border-radius: 6px;">
                                <input type="checkbox" 
                                       id="is_featured" 
                                       name="is_featured" 
                                       value="1" 
                                       {{ old('is_featured', $course->is_featured) ? 'checked' : '' }}
                                       style="width: 16px; height: 16px; accent-color: #1EA38B;">
                                <label for="is_featured" style="font-weight: 500; color: #333; margin: 0; cursor: pointer; font-size: 0.9rem;">
                                    <i class="fas fa-star me-1 text-warning"></i>Cours mis en avant
                                </label>
                            </div>
                            
                            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: #F8F9FA; border-radius: 6px;">
                                <input type="checkbox" 
                                       id="is_premium" 
                                       name="is_premium" 
                                       value="1" 
                                       {{ old('is_premium', $course->is_premium) ? 'checked' : '' }}
                                       style="width: 16px; height: 16px; accent-color: #1EA38B;">
                                <label for="is_premium" style="font-weight: 500; color: #333; margin: 0; cursor: pointer; font-size: 0.9rem;">
                                    <i class="fas fa-crown me-1 text-warning"></i>Cours premium
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Prix et promotions -->
                    <div class="form-group">
                        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 1rem;">
                            <i class="fas fa-money-bill me-2"></i>Prix et promotions
                        </label>
                        
                        <div style="margin-bottom: 1rem;">
                            <label for="price_fcfa" style="display: block; font-weight: 500; color: #555; margin-bottom: 0.5rem;">
                                Prix (FCFA)
                            </label>
                            <input type="number" 
                                   class="form-control @error('price_fcfa') is-invalid @enderror" 
                                   id="price_fcfa" 
                                   name="price_fcfa" 
                                   value="{{ old('price_fcfa', $course->price_fcfa) }}" 
                                   min="0"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #E9ECEF; border-radius: 6px; font-size: 0.9rem;">
                            @error('price_fcfa')
                                <div style="color: #E32D31; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label for="discounted_price" style="display: block; font-weight: 500; color: #555; margin-bottom: 0.5rem;">
                                Prix promotionnel (FCFA)
                            </label>
                            <input type="number" 
                                   class="form-control @error('discounted_price') is-invalid @enderror" 
                                   id="discounted_price" 
                                   name="discounted_price" 
                                   value="{{ old('discounted_price', $course->discounted_price) }}" 
                                   min="0"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #E9ECEF; border-radius: 6px; font-size: 0.9rem;">
                            <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">Laisser vide si pas de promotion</div>
                            @error('discounted_price')
                                <div style="color: #E32D31; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                            <div>
                                <label for="discount_starts_at" style="display: block; font-weight: 500; color: #555; margin-bottom: 0.25rem; font-size: 0.85rem;">
                                    Début promotion
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('discount_starts_at') is-invalid @enderror" 
                                       id="discount_starts_at" 
                                       name="discount_starts_at" 
                                       value="{{ old('discount_starts_at', $course->discount_starts_at ? date('Y-m-d\TH:i', strtotime($course->discount_starts_at)) : '') }}"
                                       style="width: 100%; padding: 0.4rem 0.5rem; border: 1px solid #E9ECEF; border-radius: 4px; font-size: 0.8rem;">
                                @error('discount_starts_at')
                                    <div style="color: #E32D31; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="discount_ends_at" style="display: block; font-weight: 500; color: #555; margin-bottom: 0.25rem; font-size: 0.85rem;">
                                    Fin promotion
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('discount_ends_at') is-invalid @enderror" 
                                       id="discount_ends_at" 
                                       name="discount_ends_at" 
                                       value="{{ old('discount_ends_at', $course->discount_ends_at ? date('Y-m-d\TH:i', strtotime($course->discount_ends_at)) : '') }}"
                                       style="width: 100%; padding: 0.4rem 0.5rem; border: 1px solid #E9ECEF; border-radius: 4px; font-size: 0.8rem;">
                                @error('discount_ends_at')
                                    <div style="color: #E32D31; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques (optionnel) -->
        @if(isset($course) && $course->id)
        <div class="admin-card" data-aos="fade-up" data-aos-delay="300" style="margin-top: 1rem;">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-chart-bar me-2"></i>Statistiques du cours
                </h3>
            </div>
            <div class="admin-card-body">
                <div class="admin-grid admin-grid-4" style="gap: 1rem;">
                    <div style="text-align: center; padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                        <h3 style="margin: 0; color: #1EA38B; font-size: 1.5rem;">{{ $course->enrollments_count ?? 0 }}</h3>
                        <p style="margin: 0; color: #6C757D; font-size: 0.9rem;">Inscrits</p>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                        <h3 style="margin: 0; color: #FFC107; font-size: 1.5rem;">{{ number_format($course->average_rating ?? 0, 1) }}/5</h3>
                        <p style="margin: 0; color: #6C757D; font-size: 0.9rem;">Note moyenne</p>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                        <h3 style="margin: 0; color: #28A745; font-size: 1.5rem;">{{ $course->completion_rate ?? 0 }}%</h3>
                        <p style="margin: 0; color: #6C757D; font-size: 0.9rem;">Taux de complétion</p>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                        <h3 style="margin: 0; color: #17A2B8; font-size: 1.5rem;">{{ $course->views_count ?? 0 }}</h3>
                        <p style="margin: 0; color: #6C757D; font-size: 0.9rem;">Vues</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="300" style="margin-top: 2rem;">
            <div class="admin-card-body">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" 
                                style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.courses.show', $course) }}" 
                           style="background: #6C757D; color: white; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s;">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                    
                    <button type="button" 
                            onclick="confirmDelete()" 
                            style="background: linear-gradient(135deg, #E32D31, #FF6B6B); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form -->
    <form id="deleteForm" action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

@push('scripts')
<script>
    // Animation
    AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true
    });

    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        const title = this.value;
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .trim();
        document.getElementById('slug').value = slug;
    });

    // Focus effects
    document.querySelectorAll('input, textarea, select').forEach(element => {
        element.addEventListener('focus', function() {
            this.style.borderColor = '#1EA38B';
            this.style.boxShadow = '0 0 0 0.2rem rgba(30, 163, 139, 0.25)';
        });
        
        element.addEventListener('blur', function() {
            this.style.borderColor = '#E9ECEF';
            this.style.boxShadow = 'none';
        });
    });

    // Delete confirmation
    function confirmDelete() {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible.')) {
            document.getElementById('deleteForm').submit();
        }
    }

    // Image preview
    document.getElementById('cover_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create or update preview
                let preview = document.getElementById('imagePreview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'imagePreview';
                    preview.style.cssText = 'width: 100%; height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #E9ECEF; margin-top: 1rem;';
                    document.getElementById('cover_image').parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
</div>
@endsection
