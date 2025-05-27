<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certification | AfriCode</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Font pour le certificat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3461FF;
            --secondary-color: #FF8B34;
            --light-bg: #f8f9fa;
            --border-radius: 10px;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #3461FF 0%, #2B4BC9 100%);
            color: white;
            min-height: 100vh;
            padding-top: 2rem;
        }
        
        .sidebar .logo {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: bold;
            font-size: 1.8rem;
        }
        
        .sidebar .nav-item {
            margin-bottom: 0.5rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            border-radius: var(--border-radius);
            padding: 0.8rem 1.5rem;
            margin: 0 1rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
        }
        
        .main-content {
            padding: 2rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Styles pour le certificat */
        .certificate-container {
            max-width: 800px;
            margin: 2rem auto;
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
                            <p>Délivré le {{ $certification->issue_date->format('d/m/Y') }}</p>
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
