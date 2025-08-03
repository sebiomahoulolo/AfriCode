@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Mon Profil')
@section('page-title', 'Mon Profil')
@section('page-subtitle', 'Gérez vos informations personnelles et paramètres de compte')

@section('styles')
<style>
    .profile-section {
        background: white;
        border-radius: var(--africode-border-radius);
        box-shadow: var(--africode-shadow-sm);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--africode-gray-light);
    }
    
    .profile-avatar {
        position: relative;
        margin-right: 2rem;
    }
    
    .profile-avatar img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--africode-primary);
    }
    
    .profile-avatar .avatar-upload {
        position: absolute;
        bottom: 0;
        right: 0;
        background: var(--africode-primary);
        color: white;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid white;
        transition: var(--africode-transition);
    }
    
    .profile-avatar .avatar-upload:hover {
        background: var(--africode-primary-dark);
    }
    
    .profile-info h2 {
        color: var(--africode-dark-text);
        margin-bottom: 0.5rem;
        font-weight: 700;
    }
    
    .profile-role {
        color: var(--africode-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .profile-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1rem;
    }
    
    .profile-stat {
        text-align: center;
    }
    
    .profile-stat .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--africode-primary);
        display: block;
    }
    
    .profile-stat .stat-label {
        font-size: 0.875rem;
        color: var(--africode-gray-dark);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--africode-dark-text);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title i {
        color: var(--africode-primary);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--africode-dark-text);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        border: 2px solid var(--africode-gray-light);
        border-radius: var(--africode-border-radius);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: var(--africode-transition);
    }
    
    .form-control:focus {
        border-color: var(--africode-primary);
        box-shadow: 0 0 0 0.2rem rgba(30, 163, 139, 0.25);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .btn-save {
        background: var(--africode-primary);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: var(--africode-border-radius);
        font-weight: 600;
        transition: var(--africode-transition);
    }
    
    .btn-save:hover {
        background: var(--africode-primary-dark);
        color: white;
    }
    
    .alert-success-custom {
        background: rgba(39, 179, 113, 0.1);
        border: 1px solid rgba(39, 179, 113, 0.2);
        color: #27B371;
        border-radius: var(--africode-border-radius);
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .danger-zone {
        border: 2px solid #dc3545;
        border-radius: var(--africode-border-radius);
        padding: 1.5rem;
        background: rgba(220, 53, 69, 0.05);
    }
    
    .danger-zone h3 {
        color: #dc3545;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: var(--africode-border-radius);
        font-weight: 600;
        transition: var(--africode-transition);
    }
    
    .btn-danger:hover {
        background: #c82333;
        color: white;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-avatar {
            margin-right: 0;
            margin-bottom: 1.5rem;
        }
        
        .profile-stats {
            justify-content: center;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .profile-section {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .profile-section {
            padding: 1rem;
        }
        
        .profile-avatar img {
            width: 100px;
            height: 100px;
        }
        
        .profile-stats {
            gap: 1rem;
        }
    }
</style>
@endsection

@section('content')
    <!-- En-tête du profil -->
    <div class="profile-section" data-aos="fade-up">
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="{{ Auth::user()->profile_image_path ? asset(Auth::user()->profile_image_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) . '&background=1EA38B&color=fff&size=120' }}" 
                     alt="Photo de profil" id="profileImage">
                <label for="avatarUpload" class="avatar-upload" title="Changer la photo de profil">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" id="avatarUpload" accept="image/*" style="display: none;">
            </div>
            <div class="profile-info">
                <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                <div class="profile-role">Formateur AfriCode</div>
                <div class="text-muted">
                    <i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}
                </div>
                <div class="profile-stats">
                    <div class="profile-stat">
                        <span class="stat-number">{{ Auth::user()->coursesInstructed()->count() }}</span>
                        <span class="stat-label">Cours créés</span>
                    </div>
                    <div class="profile-stat">
                        <span class="stat-number">{{ Auth::user()->coursesInstructed()->where('status', 'published')->count() }}</span>
                        <span class="stat-label">Cours publiés</span>
                    </div>
                    <div class="profile-stat">
                        <span class="stat-number">{{ Auth::user()->coursesInstructed()->withCount('students')->get()->sum('students_count') ?? 0 }}</span>
                        <span class="stat-label">Étudiants</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations personnelles -->
    <div class="profile-section" data-aos="fade-up" data-aos-delay="100">
        <h3 class="section-title">
            <i class="fas fa-user"></i>
            Informations personnelles
        </h3>
        
        @if(session('profile-updated'))
            <div class="alert-success-custom">
                <i class="fas fa-check-circle me-2"></i>
                Vos informations ont été mises à jour avec succès.
            </div>
        @endif
        
        <form method="POST" action="{{ route('formateur.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name" class="form-label">Prénom</label>
                    <input type="text" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name', Auth::user()->first_name) }}" 
                           required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="last_name" class="form-label">Nom</label>
                    <input type="text" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name', Auth::user()->last_name) }}" 
                           required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', Auth::user()->email) }}" 
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Votre adresse e-mail n'est pas vérifiée.
                            <a href="{{ route('verification.send') }}" class="fw-bold text-primary">Cliquez ici pour renvoyer l'e-mail de vérification.</a>
                        </p>
                    </div>
                @endif
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="tel" 
                           class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', Auth::user()->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="date_of_birth" class="form-label">Date de naissance</label>
                    <input type="date" 
                           class="form-control @error('date_of_birth') is-invalid @enderror" 
                           id="date_of_birth" 
                           name="date_of_birth" 
                           value="{{ old('date_of_birth', Auth::user()->date_of_birth?->format('Y-m-d')) }}">
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="city" class="form-label">Ville</label>
                    <input type="text" 
                           class="form-control @error('city') is-invalid @enderror" 
                           id="city" 
                           name="city" 
                           value="{{ old('city', Auth::user()->city) }}">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="country" class="form-label">Pays</label>
                    <input type="text" 
                           class="form-control @error('country') is-invalid @enderror" 
                           id="country" 
                           name="country" 
                           value="{{ old('country', Auth::user()->country) }}">
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="bio" class="form-label">Biographie</label>
                <textarea class="form-control @error('bio') is-invalid @enderror" 
                          id="bio" 
                          name="bio" 
                          rows="4" 
                          placeholder="Parlez-nous de vous, votre expertise, votre parcours...">{{ old('bio', Auth::user()->bio) }}</textarea>
                @error('bio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;">
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <!-- Changer le mot de passe -->
    <div class="profile-section" data-aos="fade-up" data-aos-delay="200">
        <h3 class="section-title">
            <i class="fas fa-lock"></i>
            Sécurité du compte
        </h3>
        
        @if(session('password-updated'))
            <div class="alert-success-custom">
                <i class="fas fa-check-circle me-2"></i>
                Votre mot de passe a été mis à jour avec succès.
            </div>
        @endif
        
        <form method="POST" action="{{ route('formateur.profile.password.update') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <input type="password" 
                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                       id="current_password" 
                       name="current_password" 
                       required>
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" 
                           class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required>
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" 
                           class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required>
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-save">
                    <i class="fas fa-key me-2"></i>Mettre à jour le mot de passe
                </button>
            </div>
        </form>
    </div>

    <!-- Zone de danger -->
    <div class="profile-section" data-aos="fade-up" data-aos-delay="300">
        <div class="danger-zone">
            <h3>
                <i class="fas fa-exclamation-triangle me-2"></i>
                Zone de danger
            </h3>
            <p class="mb-3">Les actions suivantes sont irréversibles. Procédez avec prudence.</p>
            
            <button type="button" class="btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                <i class="fas fa-trash me-2"></i>Supprimer mon compte
            </button>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer le compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Attention :</strong> Cette action est irréversible.</p>
                    <p>La suppression de votre compte entraînera :</p>
                    <ul>
                        <li>La suppression définitive de tous vos cours</li>
                        <li>La perte de toutes vos données personnelles</li>
                        <li>L'impossibilité de récupérer votre compte</li>
                    </ul>
                    <p>Veuillez saisir votre mot de passe pour confirmer :</p>
                    
                    <form method="POST" action="{{ route('formateur.profile.destroy') }}" id="deleteAccountForm">
                        @csrf
                        @method('DELETE')
                        
                        <div class="form-group">
                            <input type="password" 
                                   class="form-control" 
                                   name="password" 
                                   placeholder="Votre mot de passe" 
                                   required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="deleteAccountForm" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Gestion de l'upload d'avatar
    document.getElementById('avatarUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            // Assigner le fichier au champ caché du formulaire principal
            document.getElementById('profile_image').files = e.target.files;
        }
    });
    
    // Animation des sections
    AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true
    });
    
    // Auto-hide success messages
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-success-custom');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 3000);
</script>
@endsection
