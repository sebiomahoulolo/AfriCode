@extends('apprenants.layouts.app')

@section('title', 'Mes Certifications | AfriCode')
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
            padding: 0;
            position: relative;
        }
        
        .certificate {
            background-color: #ffffff;
            border: 15px solid #3461FF;
            padding: 50px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .certificate-title {
            font-family: 'Playfair Display', serif;
            color: #3461FF;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .certificate-subtitle {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 1rem;
        }
        
        .certificate-body {
            text-align: center;
            margin: 40px 0;
            font-family: 'Montserrat', sans-serif;
        }
        
        .certificate-recipient {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin: 20px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .certificate-message {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #666;
            margin: 20px 0;
        }
        
        .certificate-course {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin: 20px 0;
        }
        
        .certificate-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            padding-top: 20px;
        }
        
        .certificate-signature {
            text-align: center;
            flex: 1;
        }
        
        .signature-line {
            width: 80%;
            height: 1px;
            background-color: #333;
            margin: 10px auto;
        }
        
        .certificate-date {
            text-align: right;
            font-family: 'Montserrat', sans-serif;
            color: #666;
            font-size: 0.9rem;
        }
        
        .certificate-identifier {
            position: absolute;
            bottom: 20px;
            right: 50px;
            font-size: 0.8rem;
            color: #999;
        }
        
        .certificate-seal {
            position: absolute;
            bottom: 40px;
            left: 50px;
            width: 100px;
            height: 100px;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSI1MCIgY3k9IjUwIiByPSI0NSIgc3Ryb2tlPSIjMzQ2MUZGIiBzdHJva2Utd2lkdGg9IjIiIGZpbGw9Im5vbmUiLz48Y2lyY2xlIGN4PSI1MCIgY3k9IjUwIiByPSIzNSIgc3Ryb2tlPSIjMzQ2MUZGIiBzdHJva2Utd2lkdGg9IjIiIGZpbGw9Im5vbmUiLz48dGV4dCB4PSI1MCIgeT0iNTAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTIiIGZpbGw9IiMzNDYxRkYiPkFmcmlDb2RlPC90ZXh0Pjx0ZXh0IHg9IjUwIiB5PSI2NCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjgiIGZpbGw9IiMzNDYxRkYiPkNlcnRpZmljYXRpb248L3RleHQ+PC9zdmc+');
            background-size: contain;
            background-repeat: no-repeat;
        }
        
        @media (max-width: 768px) {
            .certificate {
                padding: 20px;
                border-width: 10px;
            }
            
            .certificate-title {
                font-size: 2rem;
            }
            
            .certificate-recipient {
                font-size: 1.5rem;
            }
            
            .certificate-course {
                font-size: 1.2rem;
            }
            
            .certificate-footer {
                flex-direction: column;
            }
            
            .certificate-signature {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="logo">
                    <i class="fas fa-code me-2"></i> AfriCode
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('apprenant.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-graduation-cap"></i> Mes cours
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-certificate"></i> Mes certifications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('apprenant.profile') }}">
                            <i class="fas fa-user"></i> Mon profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-comment-alt"></i> Forum
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between align-items-center py-3 mb-4">
                    <h2>Ma Certification</h2>
                    <a href="{{ route('apprenant.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Retour au tableau de bord
                    </a>
                </div>

                <div class="certificate-container">
                    <div class="certificate" id="certificate-print">
                        <div class="certificate-header">
                            <h1 class="certificate-title">Certificat d'Excellence</h1>
                            <p class="certificate-subtitle">AfriCode - La Plateforme Africaine d'Apprentissage du Code</p>
                        </div>
                        
                        <div class="certificate-body">
                            <p>Ce certificat est décerné à</p>
                            <h2 class="certificate-recipient">{{ $certification->user->first_name }} {{ $certification->user->last_name }}</h2>
                            <p class="certificate-message">pour avoir complété avec succès le cours</p>
                            <h3 class="certificate-course">{{ $certification->course->title }}</h3>
                        </div>
                        
                        <div class="certificate-footer">
                            <div class="certificate-signature">
                                <div class="signature-line"></div>
                                <p>Directeur de la Formation</p>
                            </div>
                            
                            <div class="certificate-signature">
                                <div class="signature-line"></div>
                                <p>{{ $certification->course->formateur->first_name }} {{ $certification->course->formateur->last_name }}</p>
                                <small>Formateur</small>
                            </div>
                        </div>
                        
                        <div class="certificate-date">
                            <p>Délivré le {{ $certification->issued_at->format('d/m/Y') }}</p>
                        </div>
                        
                        <div class="certificate-seal"></div>
                        
                        <div class="certificate-identifier">
                            ID: {{ $certification->certificate_identifier }}
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i> Imprimer le certificat
                        </button>
                        <a href="#" class="btn btn-outline-primary" onclick="downloadAsPDF()">
                            <i class="fas fa-file-pdf me-2"></i> Télécharger en PDF
                        </a>
                        <a href="#" class="btn btn-outline-secondary" onclick="shareCertificate()">
                            <i class="fas fa-share-alt me-2"></i> Partager
                        </a>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i> 
                        <strong>Vérification:</strong> Ce certificat peut être vérifié en utilisant l'identifiant 
                        <strong>{{ $certification->certificate_identifier }}</strong> sur 
                        <a href="{{ route('pages.verifier') }}" class="alert-link">notre page de vérification</a>.
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <script>
        // Fonction pour télécharger le certificat en PDF
        function downloadAsPDF() {
            const element = document.getElementById('certificate-print');
            const opt = {
                margin: 0.5,
                filename: 'certificat-africode-{{ $certification->certificate_identifier }}.pdf',
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
            };
            
            html2pdf().set(opt).from(element).save();
        }
        
        // Fonction pour partager le certificat
        function shareCertificate() {
            if (navigator.share) {
                navigator.share({
                    title: 'Mon certificat AfriCode',
                    text: 'Je viens d\'obtenir ma certification pour le cours "{{ $certification->course->title }}" sur AfriCode!',
                    url: window.location.href,
                }).then(() => {
                    console.log('Merci d\'avoir partagé!');
                }).catch(console.error);
            } else {
                // Fallback pour les navigateurs qui ne supportent pas l'API Web Share
                const textArea = document.createElement('textarea');
                textArea.value = window.location.href;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    alert('Lien copié dans le presse-papier! Vous pouvez maintenant le partager.');
                } catch (err) {
                    console.error('Impossible de copier le lien: ', err);
                }
                document.body.removeChild(textArea);
            }
        }
    </script>
</body>
</html>
