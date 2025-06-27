@extends('apprenants.layouts.app')

@section('title', 'Mes Certifications | AfriCode')
@section('page-title', 'Mes Certifications')

@push('styles')
<style>
    /* Page des certifications avec thème AfriCode */
    .certifications-header {
        background: var(--africode-gradient-primary);
        border-radius: var(--africode-border-radius-lg);
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--africode-shadow-lg);
        color: white;
        text-align: center;
    }

    .certifications-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(80px, -80px);
    }

    .certifications-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 180px;
        height: 180px;
        background: rgba(255, 142, 42, 0.2);
        border-radius: 50%;
        transform: translate(-60px, 60px);
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .header-icon {
        font-size: 4rem;
        color: var(--africode-secondary);
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: var(--africode-border-radius-lg);
        padding: 2rem;
        box-shadow: var(--africode-shadow-md);
        border: 1px solid rgba(52, 97, 255, 0.1);
        transition: var(--africode-transition);
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--africode-shadow-lg);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.primary { background: var(--africode-gradient-primary); }
    .stat-icon.secondary { background: var(--africode-gradient-secondary); }
    .stat-icon.success { background: linear-gradient(135deg, #28a745, #20c997); }
    .stat-icon.warning { background: linear-gradient(135deg, #ffc107, #fd7e14); }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--africode-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
    }

    .certifications-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .certification-card {
        background: white;
        border-radius: var(--africode-border-radius-lg);
        overflow: hidden;
        box-shadow: var(--africode-shadow-md);
        transition: var(--africode-transition);
        border: 1px solid rgba(52, 97, 255, 0.1);
    }

    .certification-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--africode-shadow-xl);
    }

    .certification-header {
        background: var(--africode-gradient-primary);
        padding: 1.5rem;
        text-align: center;
        position: relative;
        color: white;
    }

    .certification-badge {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        color: var(--africode-secondary);
        backdrop-filter: blur(10px);
    }

    .certification-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .certification-course {
        opacity: 0.9;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .certification-body {
        padding: 1.5rem;
    }

    .certification-details {
        margin-bottom: 1.5rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .detail-value {
        color: var(--africode-primary);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .certification-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-certificate {
        flex: 1;
        background: var(--africode-gradient-primary);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: var(--africode-border-radius);
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        transition: var(--africode-transition);
        border: none;
        font-size: 0.9rem;
    }

    .btn-certificate:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--africode-shadow-md);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        padding: 0.75rem 1rem;
        border-radius: var(--africode-border-radius);
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        transition: var(--africode-transition);
        border: none;
        font-size: 0.9rem;
    }

    .btn-secondary:hover {
        color: white;
        background: #5a6268;
        transform: translateY(-2px);
    }

    .progress-section {
        background: white;
        border-radius: var(--africode-border-radius-lg);
        padding: 2rem;
        box-shadow: var(--africode-shadow-md);
        border: 1px solid rgba(52, 97, 255, 0.1);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        color: var(--africode-primary);
        font-size: 1.5rem;
        font-weight: 700;
    }

    .section-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--africode-gradient-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .course-progress-card {
        background: #f8f9fa;
        border-radius: var(--africode-border-radius);
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--africode-primary);
    }

    .course-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .course-name {
        font-weight: 600;
        color: var(--africode-primary);
        font-size: 1.1rem;
    }

    .course-percentage {
        font-weight: 700;
        color: var(--africode-secondary);
        font-size: 1.1rem;
    }

    .progress-bar-container {
        background: #e9ecef;
        border-radius: 10px;
        height: 8px;
        overflow: hidden;
        margin-bottom: 0.75rem;
    }

    .progress-bar-fill {
        height: 100%;
        background: var(--africode-gradient-secondary);
        transition: width 0.5s ease;
        border-radius: 10px;
    }

    .progress-text {
        color: #6c757d;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: var(--africode-border-radius-lg);
        box-shadow: var(--africode-shadow-md);
        border: 1px solid rgba(52, 97, 255, 0.1);
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 3rem;
        color: #adb5bd;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--africode-primary);
        margin-bottom: 1rem;
    }

    .empty-text {
        color: #6c757d;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .verification-info {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        border-radius: var(--africode-border-radius);
        padding: 1rem;
        margin-top: 1rem;
    }

    .verification-info .text-primary {
        color: #1976d2 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .certifications-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .certifications-header {
            padding: 2rem 1rem;
        }
        
        .header-icon {
            font-size: 3rem;
        }
        
        .stat-card {
            padding: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .certification-actions {
            flex-direction: column;
        }
        
        .course-info {
            flex-direction: column;
            gap: 0.5rem;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header des certifications -->
    <div class="certifications-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-certificate"></i>
            </div>
            <h1 class="mb-3">Mes Certifications</h1>
            <p class="mb-0 fs-5">Découvrez toutes vos réussites et certifications obtenues sur AfriCode</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-medal"></i>
            </div>
            <div class="stat-number">{{ $stats['total_certifications'] }}</div>
            <div class="stat-label">Certifications Obtenues</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon secondary">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-number">{{ $stats['this_year_certifications'] }}</div>
            <div class="stat-label">Cette Année</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-number">{{ $stats['total_courses_completed'] }}</div>
            <div class="stat-label">Cours Terminés</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $stats['average_completion_time'] }}</div>
            <div class="stat-label">Jours Moyens</div>
        </div>
    </div>

    @if($certifications->count() > 0)
    <!-- Grille des certifications -->
    <div class="certifications-grid">
        @foreach($certifications as $certification)
        <div class="certification-card">
            <div class="certification-header">
                <div class="certification-badge">
                    <i class="fas fa-medal"></i>
                </div>
                <h3 class="certification-title">{{ $certification->course->title }}</h3>
                <p class="certification-course">Certification AfriCode</p>
            </div>
            
            <div class="certification-body">
                <div class="certification-details">
                    <div class="detail-row">
                        <span class="detail-label">Date d'obtention</span>
                        <span class="detail-value">{{ $certification->issued_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Numéro de certification</span>
                        <span class="detail-value">#{{ $certification->certificate_identifier ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Formateur</span>
                        <span class="detail-value">{{ $certification->course->formateur->first_name ?? '' }} {{ $certification->course->formateur->last_name ?? 'AfriCode' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Niveau</span>
                        <span class="detail-value">{{ ucfirst($certification->course->level ?? 'Débutant') }}</span>
                    </div>
                </div>
                
                <div class="certification-actions">
                    <a href="{{ route('apprenant.certification.download', $certification->id) }}" 
                       class="btn-certificate">
                        <i class="fas fa-download me-2"></i>
                        Télécharger
                    </a>
                    <button class="btn-secondary" onclick="shareCertification('{{ $certification->id }}')">
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>

                @if($certification->verification_code)
                <div class="verification-info">
                    <small class="text-primary">
                        <i class="fas fa-shield-alt me-1"></i>
                        <strong>Code de vérification :</strong> {{ $certification->verification_code }}
                    </small>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- État vide -->
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-certificate"></i>
        </div>
        <h3 class="empty-title">Aucune certification pour le moment</h3>
        <p class="empty-text">
            Complétez vos cours pour obtenir vos premières certifications AfriCode !
        </p>
        <a href="{{ route('apprenant.courses') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-book me-2"></i>
            Voir mes cours
        </a>
    </div>
    @endif

    @if($coursesInProgress->count() > 0)
    <!-- Cours en progression -->
    <div class="progress-section">
        <h2 class="section-title">
            <div class="section-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            Cours en Progression
        </h2>
        
        @foreach($coursesInProgress as $enrollment)
        <div class="course-progress-card">
            <div class="course-info">
                <span class="course-name">{{ $enrollment->course->title }}</span>
                <span class="course-percentage">{{ $enrollment->progress_percentage }}%</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: {{ $enrollment->progress_percentage }}%"></div>
            </div>
            <p class="progress-text">
                <i class="fas fa-info-circle"></i>
                Complétez ce cours à 100% pour obtenir votre certification
            </p>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Animation des cartes au scroll
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer les cartes de certification
        document.querySelectorAll('.certification-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

        // Observer les cartes de statistiques
        document.querySelectorAll('.stat-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });

    // Fonction de partage de certification
    function shareCertification(certificationId) {
        if (navigator.share) {
            navigator.share({
                title: 'Ma certification AfriCode',
                text: 'Je viens d\'obtenir une nouvelle certification sur AfriCode !',
                url: window.location.origin + '/verify/' + certificationId
            }).catch(console.error);
        } else {
            // Fallback: copier le lien
            const url = window.location.origin + '/verify/' + certificationId;
            navigator.clipboard.writeText(url).then(() => {
                // Toast notification
                showToast('Lien de vérification copié !', 'success');
            }).catch(() => {
                // Fallback pour anciens navigateurs
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('Lien de vérification copié !', 'success');
            });
        }
    }

    // Fonction pour afficher un toast
    function showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-body d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                ${message}
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto-remove après 3 secondes
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        `;
        document.body.appendChild(container);
        return container;
    }
</script>
@endpush