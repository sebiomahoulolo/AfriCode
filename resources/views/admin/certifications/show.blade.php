@extends('admin.layouts.app')

@section('breadcrumb', 'Détails du Certificat')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1.5rem;" data-aos="fade-up">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.certifications.index') }}">Certifications</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Détails</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="100">
        <div class="admin-card-header">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-certificate me-2" style="color: #FF8E2A;"></i>
                    Détails du Certificat
                </h1>
                <p class="admin-card-subtitle">Informations complètes sur le certificat</p>
            </div>
            <div>
                <a href="{{ route('admin.certifications.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Certificate Information -->
    <div class="admin-grid admin-grid-2" style="margin-bottom: 2rem;">
        <!-- Student Information -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-user-graduate me-2" style="color: #1EA38B;"></i>
                    Informations de l'Étudiant
                </h3>
            </div>
            <div class="admin-card-body">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #1EA38B, #27B371); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.5rem;">
                        {{ strtoupper(substr($certification->user->first_name ?? 'N', 0, 1)) }}{{ strtoupper(substr($certification->user->last_name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h4 style="margin: 0; color: #2C3E50; font-weight: 600;">
                            {{ $certification->user->first_name ?? 'N/A' }} {{ $certification->user->last_name ?? 'N/A' }}
                        </h4>
                        <p style="margin: 0.25rem 0 0 0; color: #6C757D; font-size: 0.9rem;">
                            <i class="fas fa-envelope me-1"></i>
                            {{ $certification->user->email ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Rôle</label>
                        <div style="margin-top: 0.25rem;">
                            <span style="background: rgba(30, 163, 139, 0.1); color: #1EA38B; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                {{ ucfirst($certification->user->role ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Date d'inscription</label>
                        <div style="margin-top: 0.25rem; color: #2C3E50;">
                            {{ $certification->user->created_at ? $certification->user->created_at->format('d/m/Y') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Information -->
        <div class="admin-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="fas fa-book me-2" style="color: #FF8E2A;"></i>
                    Informations du Cours
                </h3>
            </div>
            <div class="admin-card-body">
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="margin: 0 0 0.5rem 0; color: #2C3E50; font-weight: 600;">
                        {{ $certification->course->title ?? 'N/A' }}
                    </h4>
                    <p style="margin: 0; color: #6C757D; font-size: 0.9rem;">
                        {{ \Illuminate\Support\Str::limit($certification->course->short_description ?? 'N/A', 100) }}
                    </p>
                </div>
                
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Formateur</label>
                        <div style="margin-top: 0.25rem; color: #2C3E50;">
                            <i class="fas fa-user-tie me-1" style="color: #1EA38B;"></i>
                            {{ $certification->course->formateur->first_name ?? 'N/A' }} {{ $certification->course->formateur->last_name ?? 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Niveau</label>
                        <div style="margin-top: 0.25rem;">
                            <span style="background: rgba(255, 142, 42, 0.1); color: #FF8E2A; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500;">
                                {{ ucfirst($certification->course->level ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Prix</label>
                        <div style="margin-top: 0.25rem; color: #2C3E50; font-weight: 600;">
                            {{ number_format($certification->course->price ?? 0, 0, ',', ' ') }} {{ $certification->course->currency ?? 'FCFA' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificate Details -->
    <div class="admin-card" data-aos="fade-up" data-aos-delay="400">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="fas fa-certificate me-2" style="color: #E32D31;"></i>
                Détails du Certificat
            </h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-grid admin-grid-3" style="margin-bottom: 2rem;">
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Date d'émission</label>
                    <div style="margin-top: 0.5rem; color: #2C3E50; font-size: 1.1rem; font-weight: 600;">
                        <i class="fas fa-calendar-alt me-2" style="color: #1EA38B;"></i>
                        {{ $certification->issued_at ? $certification->issued_at->format('d/m/Y à H:i') : 'N/A' }}
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Code de vérification</label>
                    <div style="margin-top: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <code style="background: rgba(30, 163, 139, 0.1); color: #1EA38B; padding: 0.5rem 1rem; border-radius: 8px; font-size: 1rem; font-weight: 600; flex: 1;">
                                {{ $certification->verification_code ?? 'N/A' }}
                            </code>
                            <button class="admin-btn admin-btn-secondary admin-btn-sm" 
                                    onclick="copyToClipboard('{{ $certification->verification_code }}')"
                                    title="Copier le code">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6C757D; font-weight: 500;">Identifiant unique</label>
                    <div style="margin-top: 0.5rem; color: #2C3E50; font-size: 1rem; font-weight: 500;">
                        {{ $certification->certificate_identifier ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Certificate Preview -->
            <div style="border: 2px dashed #E0E0E0; border-radius: 12px; padding: 2rem; background: #FAFAFA; margin-bottom: 2rem;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <h4 style="color: #2C3E50; margin-bottom: 0.5rem;">Aperçu du Certificat</h4>
                    <p style="color: #6C757D; font-size: 0.9rem;">Cliquez sur les boutons ci-dessous pour voir ou télécharger le certificat</p>
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    @if($certification->certificate_path)
                    <a href="{{ route('public.certificate.show', ['certification' => $certification->verification_code]) }}" 
                       target="_blank"
                       class="admin-btn admin-btn-primary"
                       style="min-width: 200px;">
                        <i class="fas fa-eye me-2"></i>
                        Voir le certificat complet
                    </a>
                    <a href="{{ asset($certification->certificate_path) }}" 
                       download
                       class="admin-btn admin-btn-secondary"
                       style="min-width: 200px;">
                        <i class="fas fa-download me-2"></i>
                        Télécharger le PDF
                    </a>
                    @else
                    <div style="text-align: center; color: #E32D31; padding: 1rem;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Le fichier du certificat n'est pas disponible
                    </div>
                    @endif
                </div>
            </div>

            <!-- Verification Link -->
            <div style="background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1)); border-radius: 12px; padding: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #1EA38B, #27B371); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h5 style="margin: 0; color: #2C3E50; font-weight: 600;">Lien de vérification publique</h5>
                        <p style="margin: 0.25rem 0 0 0; color: #6C757D; font-size: 0.9rem;">
                            Partagez ce lien pour vérifier l'authenticité du certificat
                        </p>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="text" 
                           id="verificationLink" 
                           value="{{ route('public.certificate.show', ['certification' => $certification->verification_code]) }}" 
                           readonly
                           style="flex: 1; padding: 0.75rem; border: 1px solid #E0E0E0; border-radius: 8px; background: white; font-size: 0.9rem;">
                    <button class="admin-btn admin-btn-secondary" 
                            onclick="copyVerificationLink()">
                        <i class="fas fa-copy me-2"></i>
                        Copier
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('Code copié dans le presse-papiers !', 'success');
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
            showToast('Erreur lors de la copie', 'error');
        });
    }

    function copyVerificationLink() {
        const linkInput = document.getElementById('verificationLink');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999); // Pour mobile
        
        navigator.clipboard.writeText(linkInput.value).then(function() {
            showToast('Lien copié dans le presse-papiers !', 'success');
        }).catch(function(err) {
            // Fallback pour les navigateurs plus anciens
            document.execCommand('copy');
            showToast('Lien copié dans le presse-papiers !', 'success');
        });
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
        toast.style.position = 'fixed';
        toast.style.top = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '9999';
        toast.style.minWidth = '300px';
        toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endpush
@endsection

