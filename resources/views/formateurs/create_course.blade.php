@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Créer un cours')
@section('page-title', 'Créer un nouveau cours')
@section('page-subtitle', 'Renseignez les informations de base du cours')

@section('header-actions')
    <a href="{{ route('formateur.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('styles')
<style>
    .course-form label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .course-form .form-text {
        font-size: 0.8rem;
    }
    
    .image-preview {
        width: 100%;
        height: 200px;
        border-radius: var(--border-radius);
        background-size: cover;
        background-position: center;
        margin-top: 1rem;
        border: 2px dashed #ccc;
    }

    .ck-editor__editable {
        min-height: 200px;
    }
    
    /* Responsive amélioré */
    @media (max-width: 992px) {
        .image-preview {
            height: 150px;
        }
        
        .ck-editor__editable {
            min-height: 150px;
        }
    }
    
    @media (max-width: 768px) {
        .course-form h5 {
            font-size: 1.1rem;
        }
        
        .course-form label {
            font-size: 0.9rem;
        }
        
        .form-control {
            font-size: 0.9rem;
        }
        
        .form-text {
            font-size: 0.75rem;
        }
        
        .image-preview {
            height: 120px;
        }
        
        .ck-editor__editable {
            min-height: 120px;
        }
        
        .btn {
            font-size: 0.875rem;
            padding: 0.625rem 1rem;
        }
        
        .card-body-modern {
            padding: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .course-form h5 {
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        
        .course-form label {
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
        
        .image-preview {
            height: 100px;
            margin-top: 0.5rem;
        }
        
        .ck-editor__editable {
            min-height: 100px;
        }
        
        .btn {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
        
        .card-body-modern {
            padding: 0.75rem;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
        
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        
        /* Amélioration des champs de sélection */
        .form-select {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }
        
        /* Amélioration des badges de validation */
        .text-danger {
            font-size: 0.75rem;
        }
        
        .invalid-feedback {
            font-size: 0.7rem;
        }
    }
    
    @media (max-width: 480px) {
        .card-body-modern {
            padding: 0.5rem;
        }
        
        .course-form h5 {
            font-size: 0.95rem;
        }
        
        .form-control,
        .form-select {
            font-size: 0.8rem;
            padding: 0.45rem 0.65rem;
        }
        
        .image-preview {
            height: 80px;
        }
        
        .btn {
            font-size: 0.75rem;
            padding: 0.45rem 0.65rem;
        }
        
        /* Réduction des marges pour économiser l'espace */
        .row .col-md-6,
        .row .col-md-4,
        .row .col-md-8 {
            margin-bottom: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
    <div class="card-modern" data-aos="fade-up">
        <div class="card-body-modern">
            <form id="create-course-form" class="course-form" method="POST" action="{{ route('formateur.courses.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <h5 class="text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informations générales
                    </h5>
                    <hr>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre du cours <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            <div class="form-text">Choisissez un titre clair et attrayant qui résume votre cours.</div>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Description courte <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="2" required>{{ old('short_description') }}</textarea>
                            <div class="form-text">Une brève description qui apparaîtra dans les résultats de recherche (max 500 caractères).</div>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Image de couverture</label>
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
                            <div class="form-text">Format recommandé : 1200 x 600 pixels (16:9), max 2 MB.</div>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="imagePreview" class="image-preview"></div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="full_description" class="form-label">Description complète <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('full_description') is-invalid @enderror" id="full_description" name="full_description" rows="6" required>{{ old('full_description') }}</textarea>
                    <div class="form-text">Décrivez en détail ce que les étudiants apprendront dans votre cours.</div>
                    @error('full_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="learning_objectives" class="form-label">Objectifs d'apprentissage</label>
                            <textarea class="form-control @error('learning_objectives') is-invalid @enderror" id="learning_objectives" name="learning_objectives" rows="4" placeholder="Un objectif par ligne">{{ old('learning_objectives') }}</textarea>
                            <div class="form-text">Ce que les apprenants maîtriseront à la fin du cours (un objectif par ligne).</div>
                            @error('learning_objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="prerequisites" class="form-label">Prérequis</label>
                            <textarea class="form-control @error('prerequisites') is-invalid @enderror" id="prerequisites" name="prerequisites" rows="4" placeholder="Un prérequis par ligne">{{ old('prerequisites') }}</textarea>
                            <div class="form-text">Connaissances ou compétences requises pour suivre ce cours (un prérequis par ligne).</div>
                            @error('prerequisites')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Paramètres du cours</h5>
                    <hr>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="level" class="form-label">Niveau <span class="text-danger">*</span></label>
                            <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
                                <option value="">Sélectionner un niveau</option>
                                <option value="débutant" {{ old('level') == 'débutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermédiaire" {{ old('level') == 'intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="avancé" {{ old('level') == 'avancé' ? 'selected' : '' }}>Avancé</option>
                                <option value="expert" {{ old('level') == 'expert' ? 'selected' : '' }}>Expert</option>
                            </select>
                            @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', 0) }}" min="0" step="0.01" required>
                                <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                    <option value="EUR" {{ old('currency', 'EUR') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="XOF" {{ old('currency') == 'XOF' ? 'selected' : '' }}>XOF</option>
                                </select>
                            </div>
                            <div class="form-text">Mettez 0 pour un cours gratuit.</div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input @error('is_certifying') is-invalid @enderror" type="checkbox" 
                                   value="1" id="is_certifying" name="is_certifying" {{ old('is_certifying') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_certifying">
                                <strong>Cours certifiant</strong>
                            </label>
                            <div class="form-text">
                                Cochez cette case si ce cours délivre un certificat de réussite après validation de tous les modules et du quiz final.
                            </div>
                            @error('is_certifying')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('formateur.dashboard') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Créer le cours</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    // Image preview
    document.getElementById('cover_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('imagePreview').style.backgroundImage = `url('${event.target.result}')`;
                document.getElementById('imagePreview').style.border = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Initialize CKEditor for full description
    ClassicEditor
        .create(document.querySelector('#full_description'))
        .catch(error => {
            console.error('Erreur CKEditor pour #full_description:', error);
        });
        
    // Handle learning objectives and prerequisites as JSON
    const courseForm = document.querySelector('form.course-form');
    if (courseForm) {
        courseForm.addEventListener('submit', function(e) {
            try {
                const learningObjectivesEl = document.getElementById('learning_objectives');
                if (learningObjectivesEl) {
                    const learningObjectives = learningObjectivesEl.value
                        .split('\\n')
                        .map(line => line.trim()) // Trim each line
                        .filter(line => line !== ''); // Filter out empty lines
                    learningObjectivesEl.value = JSON.stringify(learningObjectives);
                }

                const prerequisitesEl = document.getElementById('prerequisites');
                if (prerequisitesEl) {
                    const prerequisites = prerequisitesEl.value
                        .split('\\n')
                        .map(line => line.trim()) // Trim each line
                        .filter(line => line !== ''); // Filter out empty lines
                    prerequisitesEl.value = JSON.stringify(prerequisites);
                }
            } catch (error) {
                console.error('Erreur lors de la préparation des données du formulaire (cours) avant soumission:', error);
                // Décommentez les lignes suivantes pour empêcher la soumission et alerter en cas d'erreur critique
                // e.preventDefault();
                // alert('Une erreur est survenue lors de la préparation des données du formulaire. Veuillez vérifier la console.');
            }
        });
    } else {
        console.error('Le formulaire .course-form n\'a pas été trouvé.');
    }
</script>
@endsection
