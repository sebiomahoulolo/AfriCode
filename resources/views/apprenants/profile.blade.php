@extends('apprenants.layouts.app')

@section('title', 'AfriCode')
@section('page-title', 'Mon Profil')

@push('styles')
<style>
    /* Profil - Application harmonieuse de la charte AfriCode */
    .profile-hero {
        background: var(--africode-gradient-primary);
        border-radius: var(--africode-border-radius-lg);
        padding: 3rem 2rem;
        text-align: center;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--africode-shadow-lg);
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50px, -50px);
    }

    .profile-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 142, 42, 0.2);
        border-radius: 50%;
        transform: translate(-30px, 30px);
    }

    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 6px solid white;
        box-shadow: var(--africode-shadow-lg);
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
        transition: var(--africode-transition);
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 32px rgba(30, 163, 139, 0.3);
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .stat-item {
        background: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        text-align: center;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--gray-600);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.875rem;
    }

    .profile-form {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label-modern {
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: block;
    }

    .form-control-modern {
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-sm);
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--gray-50);
        width: 100%;
    }

    .form-control-modern:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
        outline: none;
    }

    .avatar-upload {
        position: relative;
        display: inline-block;
    }

    .avatar-upload-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: var(--primary-color);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
    }

    .avatar-upload-btn:hover {
        background: var(--primary-dark);
        transform: scale(1.1);
    }

    .security-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: var(--gray-50);
        border-radius: var(--border-radius-sm);
        margin-bottom: 1rem;
        border: 1px solid var(--gray-200);
    }

    .security-info h6 {
        margin: 0 0 0.25rem 0;
        color: var(--gray-800);
    }

    .security-info p {
        margin: 0;
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .activity-timeline {
        position: relative;
        padding-left: 2rem;
    }

    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gray-200);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 3px solid white;
        box-shadow: var(--shadow-sm);
    }

    .timeline-content {
        background: white;
        padding: 1.5rem;
        border-radius: var(--border-radius-sm);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .timeline-date {
        color: var(--gray-500);
        font-size: 0.875rem;
        font-weight: 600;
    }

    .achievement-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--warning-color), #ffd700);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
        margin: 0.25rem;
    }

    @media (max-width: 768px) {
        .profile-hero {
            padding: 2rem 1rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
        }

        .profile-stats {
            grid-template-columns: 1fr;
        }

        .profile-form {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
    <!-- Profile Hero Section -->
    <div class="profile-hero" data-aos="fade-up">
        <div class="avatar-upload">
            <img src="{{ $user->profile_image_path ? asset($user->profile_image_path) : asset('assets/images/default-avatar.png') }}" 
                 alt="Profile Picture" class="profile-avatar" id="profilePreview">
            <label for="profile_image" class="avatar-upload-btn">
                <i class="fas fa-camera"></i>
            </label>
        </div>
        
        <h2 class="mb-3">{{ $user->first_name }} {{ $user->last_name }}</h2>
        <p class="mb-4 opacity-75">{{ $user->bio ?? 'Apprenant passionné par la technologie' }}</p>
        
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <span class="badge-modern badge-primary">
                <i class="fas fa-calendar-alt me-2"></i>
                Inscrit {{ $user->created_at->diffForHumans() }}
            </span>
            <span class="badge-modern badge-success">
                <i class="fas fa-check-circle me-2"></i>
                Profil vérifié
            </span>
        </div>
    </div>

    <!-- Statistics -->
    <div class="profile-stats" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-item">
            <div class="stat-value">{{ $enrollments->count() }}</div>
            <div class="stat-label">Cours suivis</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-value">{{ $completedCourses }}</div>
            <div class="stat-label">Cours terminés</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-value">{{ $certifications->count() }}</div>
            <div class="stat-label">Certifications</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-value">{{ round($averageProgress) }}%</div>
            <div class="stat-label">Progression moyenne</div>
        </div>
    </div>

    <!-- Notifications -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Form -->
        <div class="col-lg-8 mb-4">
            <div class="profile-form" data-aos="fade-up" data-aos-delay="200">
                <form action="{{ route('apprenant.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            Informations personnelles
                        </div>
                        
                        <input type="file" id="profile_image" name="profile_image" class="d-none" 
                               accept="image/*" onchange="previewImage(this)">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name" class="form-label-modern">Prénom</label>
                                    <input type="text" id="first_name" name="first_name" 
                                           class="form-control-modern @error('first_name') is-invalid @enderror"
                                           value="{{ old('first_name', $user->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name" class="form-label-modern">Nom</label>
                                    <input type="text" id="last_name" name="last_name" 
                                           class="form-control-modern @error('last_name') is-invalid @enderror"
                                           value="{{ old('last_name', $user->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label-modern">Adresse email</label>
                            <input type="email" id="email" name="email" 
                                   class="form-control-modern @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="bio" class="form-label-modern">Bio / Description</label>
                            <textarea id="bio" name="bio" rows="4" 
                                      class="form-control-modern @error('bio') is-invalid @enderror"
                                      placeholder="Parlez-nous de vous, vos objectifs, vos passions...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            Sécurité
                        </div>
                        
                        <div class="form-group">
                            <label for="current_password" class="form-label-modern">Mot de passe actuel</label>
                            <input type="password" id="current_password" name="current_password" 
                                   class="form-control-modern">
                            <small class="text-muted">Laissez vide si vous ne souhaitez pas changer votre mot de passe</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label-modern">Nouveau mot de passe</label>
                                    <input type="password" id="password" name="password" 
                                           class="form-control-modern @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label-modern">Confirmer mot de passe</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                           class="form-control-modern">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-modern">
                            <i class="fas fa-save me-2"></i>Sauvegarder les modifications
                        </button>
                        <button type="reset" class="btn btn-outline-modern">
                            <i class="fas fa-undo me-2"></i>Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Achievements -->
            <div class="modern-card mb-4" data-aos="fade-up" data-aos-delay="300">
                <h5 class="mb-4">
                    <i class="fas fa-trophy me-2 text-warning"></i>
                    Mes succès
                </h5>
                
                <div class="d-flex flex-wrap">
                    @if($completedCourses > 0)
                        <div class="achievement-badge">
                            <i class="fas fa-graduation-cap"></i>
                            Premier cours terminé
                        </div>
                    @endif
                    
                    @if($completedCourses >= 5)
                        <div class="achievement-badge">
                            <i class="fas fa-medal"></i>
                            Apprenant assidu
                        </div>
                    @endif
                    
                    @if($certifications->count() > 0)
                        <div class="achievement-badge">
                            <i class="fas fa-certificate"></i>
                            Première certification
                        </div>
                    @endif
                    
                    @if($certifications->count() >= 3)
                        <div class="achievement-badge">
                            <i class="fas fa-star"></i>
                            Expert certifié
                        </div>
                    @endif
                    
                    @if($enrollments->count() >= 10)
                        <div class="achievement-badge">
                            <i class="fas fa-fire"></i>
                            Passionné d'apprentissage
                        </div>
                    @endif
                </div>
                
                @if($completedCourses == 0 && $certifications->count() == 0 && $enrollments->count() < 5)
                    <div class="text-center py-4">
                        <i class="fas fa-trophy display-4 text-muted opacity-50"></i>
                        <p class="text-muted mt-3">Vos premiers succès apparaîtront ici !</p>
                    </div>
                @endif
            </div>

            <!-- Security Status -->
            <div class="modern-card" data-aos="fade-up" data-aos-delay="400">
                <h5 class="mb-4">
                    <i class="fas fa-shield-check me-2 text-success"></i>
                    Statut de sécurité
                </h5>
                
                <div class="security-item">
                    <div class="security-info">
                        <h6>Authentification à deux facteurs</h6>
                        <p>Protection supplémentaire de votre compte</p>
                    </div>
                    <button class="btn btn-outline-modern btn-sm">
                        Activer
                    </button>
                </div>
                
                <div class="security-item">
                    <div class="security-info">
                        <h6>Sessions actives</h6>
                        <p>Gérez vos connexions</p>
                    </div>
                    <button class="btn btn-outline-modern btn-sm">
                        Voir
                    </button>
                </div>
                
                <div class="security-item">
                    <div class="security-info">
                        <h6>Mot de passe</h6>
                        <p>Dernière modification {{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                    <span class="badge-modern badge-success">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        if (password && password !== passwordConfirmation) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            return false;
        }
    });
</script>
@endpush
