@extends('apprenants.layouts.app')

@section('title', 'AfriCode')
@section('page-title', __('messages.learner_profile'))

@push('styles')
<style>
    /* Page de profil complète avec thème AfriCode */
    .profile-header {
        background: var(--africode-gradient-primary);
        border-radius: var(--africode-border-radius-lg);
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--africode-shadow-lg);
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(100px, -100px);
    }

    .profile-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 142, 42, 0.2);
        border-radius: 50%;
        transform: translate(-50px, 50px);
    }

    .profile-info {
        display: flex;
        align-items: center;
        gap: 2rem;
        position: relative;
        z-index: 2;
        color: white;
    }

    .avatar-container {
        position: relative;
    }

    .profile-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: var(--africode-shadow-lg);
        transition: var(--africode-transition);
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .avatar-upload-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: var(--africode-secondary);
        color: white;
        border: 3px solid white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--africode-transition);
        box-shadow: var(--africode-shadow-md);
    }

    .avatar-upload-btn:hover {
        background: #FF7A2A;
        transform: scale(1.1);
    }

    .profile-details h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .profile-title {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }

    .profile-badges {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .profile-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: var(--africode-border-radius);
        padding: 2rem;
        text-align: center;
        box-shadow: var(--africode-shadow-sm);
        border: 1px solid var(--africode-border);
        transition: var(--africode-transition);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--africode-gradient-primary);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--africode-shadow-lg);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--africode-primary);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        color: var(--africode-text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.9rem;
    }

    .stat-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2rem;
        color: var(--africode-primary);
        opacity: 0.3;
    }

    /* Contenu principal */
    .content-tabs {
        background: white;
        border-radius: var(--africode-border-radius);
        box-shadow: var(--africode-shadow-sm);
        border: 1px solid var(--africode-border);
        overflow: hidden;
    }

    .tab-nav {
        display: flex;
        background: var(--africode-surface);
        border-bottom: 1px solid var(--africode-border);
    }

    .tab-btn {
        flex: 1;
        padding: 1.25rem 1.5rem;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        color: var(--africode-text-secondary);
        transition: var(--africode-transition);
        position: relative;
    }

    .tab-btn.active {
        color: var(--africode-primary);
        background: white;
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--africode-primary);
    }

    .tab-content {
        padding: 2rem;
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Formulaires */
    .form-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--africode-text-primary);
    }

    .section-icon {
        width: 50px;
        height: 50px;
        background: var(--africode-gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label-modern {
        display: block;
        font-weight: 600;
        color: var(--africode-text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control-modern {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--africode-border);
        border-radius: var(--africode-border-radius-sm);
        font-size: 1rem;
        transition: var(--africode-transition);
        background: white;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: var(--africode-primary);
        box-shadow: 0 0 0 3px rgba(30, 163, 139, 0.1);
        transform: translateY(-1px);
    }

    /* Activités récentes */
    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--africode-border);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 50px;
        height: 50px;
        background: var(--africode-gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: var(--africode-text-primary);
        margin-bottom: 0.25rem;
    }

    .activity-description {
        color: var(--africode-text-secondary);
        font-size: 0.9rem;
    }

    .activity-time {
        color: var(--africode-text-muted);
        font-size: 0.85rem;
        white-space: nowrap;
    }

    /* Certifications */
    .certification-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .certification-card {
        background: var(--africode-gradient-primary);
        border-radius: var(--africode-border-radius);
        padding: 2rem;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: var(--africode-shadow-md);
        transition: var(--africode-transition);
    }

    .certification-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--africode-shadow-lg);
    }

    .certification-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: rotate(45deg);
    }

    .certification-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .certification-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .certification-date {
        opacity: 0.9;
        font-size: 0.9rem;
        position: relative;
        z-index: 2;
    }

    /* Boutons modernes */
    .btn-modern {
        background: var(--africode-gradient-primary);
        border: none;
        color: white;
        padding: 0.875rem 2rem;
        border-radius: var(--africode-border-radius-sm);
        font-weight: 600;
        transition: var(--africode-transition);
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: left 0.5s ease;
    }

    .btn-modern:hover::before {
        left: 100%;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: var(--africode-shadow-md);
        color: white;
        text-decoration: none;
    }

    .btn-outline-modern {
        background: transparent;
        border: 2px solid var(--africode-primary);
        color: var(--africode-primary);
        padding: 0.875rem 2rem;
        border-radius: var(--africode-border-radius-sm);
        font-weight: 600;
        transition: var(--africode-transition);
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-outline-modern:hover {
        background: var(--africode-primary);
        color: white;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-info {
            flex-direction: column;
            text-align: center;
            gap: 1.5rem;
        }

        .profile-header {
            padding: 2rem 1rem;
        }

        .profile-details h1 {
            font-size: 2rem;
        }

        .stats-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .tab-nav {
            flex-wrap: wrap;
        }

        .tab-btn {
            flex: 1 1 50%;
            min-width: 120px;
        }

        .content-tabs {
            margin: 0 -1rem;
            border-radius: 0;
        }
    }

    @media (max-width: 480px) {
        .stats-container {
            grid-template-columns: 1fr;
        }

        .certification-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- En-tête du profil -->
<div class="profile-header" data-aos="fade-up">
    <div class="profile-info">
        <div class="avatar-container">
            <img src="{{ $user->profile_image_path ? asset($user->profile_image_path) : asset('assets/images/default-avatar.png') }}" 
                 alt="Photo de profil" class="profile-avatar" id="profilePreview">
            <label for="profile_image_input" class="avatar-upload-btn" title="Changer la photo de profil">
                <i class="fas fa-camera"></i>
            </label>
        </div>
        
        <div class="profile-details">
            <h1>{{ $user->first_name }} {{ $user->last_name }}</h1>
            <div class="profile-title">
                {{ $user->bio ?? 'Apprenant passionné par la technologie' }}
            </div>
            
            <div class="profile-badges">
                <span class="profile-badge">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Inscrit {{ $user->created_at->diffForHumans() }}
                </span>
                <span class="profile-badge">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    {{ $user->city ?? 'Non spécifié' }}
                </span>
                @if($certifications->count() > 0)
                <span class="profile-badge">
                    <i class="fas fa-certificate me-2"></i>
                    {{ $certifications->count() }} {{ Str::plural('certification', $certifications->count()) }}
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-container" data-aos="fade-up" data-aos-delay="100">
    <div class="stat-card">
        <i class="fas fa-book-open stat-icon"></i>
        <div class="stat-value">{{ $totalEnrollments }}</div>
        <div class="stat-label">Cours suivis</div>
    </div>
    
    <div class="stat-card">
        <i class="fas fa-graduation-cap stat-icon"></i>
        <div class="stat-value">{{ $completedCourses }}</div>
        <div class="stat-label">Cours terminés</div>
    </div>
    
    <div class="stat-card">
        <i class="fas fa-certificate stat-icon"></i>
        <div class="stat-value">{{ $certifications->count() }}</div>
        <div class="stat-label">Certifications</div>
    </div>
    
    <div class="stat-card">
        <i class="fas fa-chart-line stat-icon"></i>
        <div class="stat-value">{{ round($averageProgress) }}%</div>
        <div class="stat-label">Progression moyenne</div>
    </div>
</div>

<!-- Messages de session -->
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

<!-- Contenu avec onglets -->
<div class="content-tabs" data-aos="fade-up" data-aos-delay="200">
    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchTab(event, 'profile-tab')">
            <i class="fas fa-user me-2"></i>Informations personnelles
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'activity-tab')">
            <i class="fas fa-history me-2"></i>Activité récente
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'certifications-tab')">
            <i class="fas fa-certificate me-2"></i>Certifications
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'security-tab')">
            <i class="fas fa-shield-alt me-2"></i>Sécurité
        </button>
    </div>

    <!-- Onglet Profil -->
    <div id="profile-tab" class="tab-content active">
        <form action="{{ route('apprenant.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <input type="file" id="profile_image_input" name="profile_image" class="d-none" 
                   accept="image/*" onchange="previewImage(this)">
            
            <!-- Informations personnelles -->
            <div class="form-section">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    Informations personnelles
                </div>
                
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
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label-modern">Adresse email</label>
                            <input type="email" id="email" name="email" 
                                   class="form-control-modern @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone" class="form-label-modern">Téléphone</label>
                            <input type="tel" id="phone" name="phone" 
                                   class="form-control-modern @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_of_birth" class="form-label-modern">Date de naissance</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" 
                                   class="form-control-modern @error('date_of_birth') is-invalid @enderror"
                                   value="{{ old('date_of_birth', $user->date_of_birth) }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="city" class="form-label-modern">Ville</label>
                            <input type="text" id="city" name="city" 
                                   class="form-control-modern @error('city') is-invalid @enderror"
                                   value="{{ old('city', $user->city) }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="country" class="form-label-modern">Pays</label>
                            <input type="text" id="country" name="country" 
                                   class="form-control-modern @error('country') is-invalid @enderror"
                                   value="{{ old('country', $user->country) }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
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

            <!-- Sécurité -->
            <div class="form-section">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    Changer le mot de passe
                </div>
                
                <div class="form-group">
                    <label for="current_password" class="form-label-modern">Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" 
                           class="form-control-modern @error('current_password') is-invalid @enderror">
                    <small class="text-muted">Laissez vide si vous ne souhaitez pas changer votre mot de passe</small>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                <button type="submit" class="btn-modern">
                    <i class="fas fa-save"></i>Sauvegarder les modifications
                </button>
                <button type="reset" class="btn-outline-modern">
                    <i class="fas fa-undo"></i>Annuler
                </button>
            </div>
        </form>
    </div>

    <!-- Onglet Activité -->
    <div id="activity-tab" class="tab-content">
        <div class="section-title">
            <div class="section-icon">
                <i class="fas fa-history"></i>
            </div>
            Activités récentes
        </div>
        
        @if($recentActivities->count() > 0)
            @foreach($recentActivities as $activity)
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">
                        Leçon complétée : {{ $activity->lesson->title }}
                    </div>
                    <div class="activity-description">
                        {{ $activity->lesson->module->title }} - {{ $activity->lesson->module->course->title }}
                    </div>
                </div>
                <div class="activity-time">
                    {{ $activity->completed_at->diffForHumans() }}
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-history display-4 text-muted opacity-50"></i>
                <h5 class="text-muted mt-3">Aucune activité récente</h5>
                <p class="text-muted">Commencez un cours pour voir vos activités ici.</p>
                <a href="{{ route('courses.index') }}" class="btn-modern">
                    <i class="fas fa-search"></i>Explorer les cours
                </a>
            </div>
        @endif
    </div>

    <!-- Onglet Certifications -->
    <div id="certifications-tab" class="tab-content">
        <div class="section-title">
            <div class="section-icon">
                <i class="fas fa-certificate"></i>
            </div>
            Mes certifications
        </div>
        
        @if($certifications->count() > 0)
            <div class="certification-grid">
                @foreach($certifications as $certification)
                <div class="certification-card">
                    <div class="certification-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="certification-title">
                        {{ $certification->course->title }}
                    </div>
                    <div class="certification-date">
                        Obtenu le {{ $certification->issued_at->format('d/m/Y') }}
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-certificate display-4 text-muted opacity-50"></i>
                <h5 class="text-muted mt-3">Aucune certification</h5>
                <p class="text-muted">Terminez un cours pour obtenir votre première certification.</p>
                <a href="{{ route('apprenant.courses') }}" class="btn-modern">
                    <i class="fas fa-book-open"></i>Voir mes cours
                </a>
            </div>
        @endif
    </div>

    <!-- Onglet Sécurité -->
    <div id="security-tab" class="tab-content">
        <div class="section-title">
            <div class="section-icon">
                <i class="fas fa-shield-check"></i>
            </div>
            Paramètres de sécurité
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="security-item d-flex justify-content-between align-items-center p-3 mb-3 border rounded">
                    <div>
                        <h6 class="mb-1">Authentification à deux facteurs</h6>
                        <p class="mb-0 text-muted">Protection supplémentaire de votre compte</p>
                    </div>
                    <button class="btn-outline-modern btn-sm">
                        Activer
                    </button>
                </div>
                
                <div class="security-item d-flex justify-content-between align-items-center p-3 mb-3 border rounded">
                    <div>
                        <h6 class="mb-1">Sessions actives</h6>
                        <p class="mb-0 text-muted">Gérez vos connexions actives</p>
                    </div>
                    <button class="btn-outline-modern btn-sm">
                        Voir
                    </button>
                </div>
                
                <div class="security-item d-flex justify-content-between align-items-center p-3 mb-3 border rounded">
                    <div>
                        <h6 class="mb-1">Notifications par email</h6>
                        <p class="mb-0 text-muted">Recevez des alertes de sécurité</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                        <label class="form-check-label" for="emailNotifications"></label>
                    </div>
                </div>
                
                <div class="security-item d-flex justify-content-between align-items-center p-3 border rounded">
                    <div>
                        <h6 class="mb-1">Dernière modification du mot de passe</h6>
                        <p class="mb-0 text-muted">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                    <span class="badge bg-success">
                        <i class="fas fa-check"></i> Sécurisé
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fonction pour changer d'onglet
    function switchTab(event, tabId) {
        // Retirer la classe active de tous les boutons et contenus
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        // Ajouter la classe active au bouton cliqué et au contenu correspondant
        event.target.classList.add('active');
        document.getElementById(tabId).classList.add('active');
    }

    // Prévisualisation de l'image de profil
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Validation du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const currentPassword = document.getElementById('current_password').value;
        
        if (password && password !== passwordConfirmation) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            return false;
        }
        
        if (password && !currentPassword) {
            e.preventDefault();
            alert('Veuillez saisir votre mot de passe actuel pour le modifier.');
            return false;
        }
    });

    // Animation au scroll
    AOS.init({
        duration: 600,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });
</script>
@endpush
