@extends('apprenants.layouts.app')

@section('title', 'AfriCode')
@section('page-title', 'Mes Certifications')

@push('styles')
<style>
    /* Certification page styles */
    .certifications-hero {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: var(--border-radius-lg);
        padding: 3rem 2rem;
        text-align: center;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .certifications-hero::before {
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

    .certifications-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        transform: translate(-50px, 50px);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .certification-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .stat-text {
        font-size: 0.875rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .certifications-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .certification-card {
        background: white;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        position: relative;
    }

    .certification-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .certification-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
    }

    .certification-badge {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        font-size: 2rem;
        color: var(--primary-color);
        box-shadow: var(--shadow);
    }

    .certification-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .certification-course {
        opacity: 0.9;
        font-size: 1rem;
    }

    .certification-body {
        padding: 2rem;
    }

    .certification-details {
        margin-bottom: 1.5rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-100);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .detail-value {
        color: var(--gray-800);
        font-weight: 600;
    }

    .certification-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-certificate {
        flex: 1;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 0.875rem 1.5rem;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-certificate:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
        color: white;
        text-decoration: none;
    }

    .btn-share {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
        padding: 0.875rem 1rem;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-share:hover {
        background: var(--gray-200);
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem auto;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--primary-color);
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 1rem;
    }

    .empty-text {
        color: var(--gray-600);
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .progress-section {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
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

    .course-progress {
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        background: var(--gray-50);
        border-radius: var(--border-radius);
        border: 1px solid var(--gray-200);
    }

    .course-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .course-name {
        font-weight: 600;
        color: var(--gray-800);
    }

    .course-percentage {
        font-weight: 700;
        color: var(--primary-color);
    }

    .progress-bar-container {
        background: var(--gray-200);
        border-radius: 20px;
        height: 8px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 20px;
        transition: width 0.3s ease;
    }

    .requirements-text {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin-top: 0.5rem;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .certifications-grid {
            grid-template-columns: 1fr;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .certification-actions {
            flex-direction: column;
        }
        
        .certification-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .certification-stats {
            grid-template-columns: 1fr;
        }
        
        .certifications-hero {
            padding: 2rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid" data-aos="fade-up">
    <!-- Hero Section -->
    <div class="certifications-hero">
        <div class="hero-content">
            <h1 class="hero-title">🏆 Mes Certifications</h1>
            <p class="hero-subtitle">Découvrez et gérez toutes vos certifications AfriCode</p>
            
            <div class="certification-stats">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-number">{{ $certifications->count() }}</div>
                    <div class="stat-text">Certifications Obtenues</div>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-number">{{ $courses_completed ?? 0 }}</div>
                    <div class="stat-text">Cours Terminés</div>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-number">{{ $total_hours ?? 0 }}h</div>
                    <div class="stat-text">Heures d'Apprentissage</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Progress Section -->
    @if(isset($courses_in_progress) && $courses_in_progress->count() > 0)
    <div class="progress-section" data-aos="fade-up" data-aos-delay="100">
        <h2 class="section-title">
            <div class="section-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            Cours en Progression
        </h2>
        
        @foreach($courses_in_progress as $course)
        <div class="course-progress">
            <div class="course-info">
                <span class="course-name">{{ $course->title }}</span>
                <span class="course-percentage">{{ $course->completion_percentage }}%</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: {{ $course->completion_percentage }}%"></div>
            </div>
            <p class="requirements-text">
                <i class="fas fa-info-circle"></i>
                Complétez ce cours à 100% pour obtenir votre certification
            </p>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Certifications Grid -->
    @if($certifications->count() > 0)
    <div class="certifications-grid">
        @foreach($certifications as $certification)
        <div class="certification-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
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
                        <span class="detail-value">{{ $certification->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Numéro de certification</span>
                        <span class="detail-value">#{{ $certification->certificate_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Durée du cours</span>
                        <span class="detail-value">{{ $certification->course->duration ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Niveau</span>
                        <span class="detail-value">{{ ucfirst($certification->course->level ?? 'Débutant') }}</span>
                    </div>
                </div>
                
                <div class="certification-actions">
                    <a href="{{ route('apprenants.certificate.download', $certification->id) }}" 
                       class="btn-certificate">
                        <i class="fas fa-download"></i>
                        Télécharger
                    </a>
                    <button class="btn-share" onclick="shareCertification({{ $certification->id }})">
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state" data-aos="fade-up" data-aos-delay="200">
        <div class="empty-icon">
            <i class="fas fa-certificate"></i>
        </div>
        <h3 class="empty-title">Aucune certification pour le moment</h3>
        <p class="empty-text">
            Terminez vos cours pour obtenir vos premières certifications AfriCode et valoriser vos compétences.
        </p>
        <a href="{{ route('apprenants.dashboard') }}" class="btn-modern btn-primary">
            <i class="fas fa-play"></i>
            Reprendre mes cours
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function shareCertification(certificationId) {
        // Check if Web Share API is supported
        if (navigator.share) {
            navigator.share({
                title: 'Ma certification AfriCode',
                text: 'Découvrez ma nouvelle certification obtenue sur AfriCode !',
                url: window.location.origin + '/certifications/' + certificationId + '/public'
            }).catch(console.error);
        } else {
            // Fallback: copy link to clipboard
            const url = window.location.origin + '/certifications/' + certificationId + '/public';
            navigator.clipboard.writeText(url).then(() => {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'position-fixed top-0 end-0 p-3';
                toast.style.zIndex = '1055';
                toast.innerHTML = `
                    <div class="toast show" role="alert">
                        <div class="toast-header">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong class="me-auto">Succès</strong>
                        </div>
                        <div class="toast-body">
                            Lien copié dans le presse-papier !
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 3000);
            });
        }
    }

    // Initialize AOS
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true
            });
        }
    });
</script>
@endpush
