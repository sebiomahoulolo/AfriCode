@extends('layouts.layout')

@section('title', 'AfriCode')

@push('styles')
<style>
    :root {
        --primary-color: #1EA38B;
        --secondary-color: #FF8E2A;
        --accent-color: #E32D31;
        --highlight-color: #27B371;
        --background-color: #F0F4F7;
        --text-color: #333;
        --light-gray: #f8f9fa;
    }

    body {
        background: linear-gradient(135deg, var(--background-color) 0%, #E8F5F3 100%);
        min-height: 100vh;
    }

    .hero-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--highlight-color) 100%);
        color: white;
        padding: 80px 0;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
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

    .hero-section::after {
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

    .verification-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        padding: 40px;
        margin-bottom: 40px;
        border: 1px solid rgba(30, 163, 139, 0.1);
        position: relative;
        overflow: hidden;
    }

    .verification-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }

    .form-control-lg {
        padding: 15px 20px;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .form-control-lg:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(30, 163, 139, 0.25);
    }

    .btn-verify {
        background: linear-gradient(135deg, var(--primary-color), var(--highlight-color));
        border: none;
        padding: 15px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(30, 163, 139, 0.3);
    }

    .btn-verify:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(30, 163, 139, 0.4);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin: 60px 0;
    }

    .feature-card {
        background: white;
        padding: 30px 25px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(30, 163, 139, 0.1);
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--primary-color), var(--highlight-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 1.8rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: none;
        border-left: 4px solid var(--highlight-color);
        border-radius: 12px;
        padding: 20px;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        border: none;
        border-left: 4px solid var(--accent-color);
        border-radius: 12px;
        padding: 20px;
    }

    /* Nouveau design pour les résultats du certificat */
    .certificate-result-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        margin: 40px 0;
        overflow: hidden;
        border: 1px solid rgba(30, 163, 139, 0.1);
        transition: all 0.4s ease;
    }

    .certificate-result-card:hover {
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .result-header {
        background: linear-gradient(135deg, var(--primary-color), var(--highlight-color));
        color: white;
        padding: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .result-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 20px;
        z-index: 1;
        position: relative;
    }

    .status-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        backdrop-filter: blur(10px);
    }

    .status-content h4 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .status-content p {
        margin: 0;
        opacity: 0.9;
        font-size: 1rem;
    }

    .verification-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.2);
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        backdrop-filter: blur(10px);
        z-index: 1;
        position: relative;
    }

    .certificate-main-info {
        padding: 40px 30px;
        background: #fafbfc;
    }

    .holder-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        padding: 25px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .holder-avatar {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--primary-color), var(--highlight-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    .holder-details h5 {
        margin: 0 0 5px 0;
        color: var(--text-color);
        font-size: 1.3rem;
        font-weight: 700;
    }

    .holder-details p {
        margin: 0;
        color: #6c757d;
        font-size: 1rem;
    }

    .course-section {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        padding: 25px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .course-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--secondary-color), #ff6b35);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .course-details h6 {
        margin: 0 0 10px 0;
        color: var(--text-color);
        font-size: 1.2rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .course-meta {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .course-category, .course-level {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .course-category {
        background: rgba(30, 163, 139, 0.1);
        color: var(--primary-color);
    }

    .course-level {
        background: rgba(255, 142, 42, 0.1);
        color: var(--secondary-color);
    }

    .certificate-metadata {
        padding: 30px;
        background: white;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .metadata-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .metadata-item:last-child {
        border-bottom: none;
    }

    .metadata-item i {
        width: 40px;
        height: 40px;
        background: rgba(30, 163, 139, 0.1);
        color: var(--primary-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .metadata-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .metadata-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }

    .metadata-value {
        font-size: 1rem;
        color: var(--text-color);
        font-weight: 600;
    }

    .metadata-value.code {
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.9rem;
        letter-spacing: 1px;
        display: inline-block;
        margin-top: 2px;
    }

    .certificate-actions {
        padding: 30px;
        background: #f8f9fa;
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-view-certificate {
        background: linear-gradient(135deg, var(--primary-color), var(--highlight-color));
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(30, 163, 139, 0.3);
    }

    .btn-view-certificate:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 163, 139, 0.4);
        color: white;
    }

    .btn-share {
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-share:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    .certificate-info {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-top: 20px;
        border-left: 4px solid var(--primary-color);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .info-item {
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .info-value {
        color: var(--text-color);
        font-size: 1.1rem;
        font-weight: 500;
    }

    .scan-guide {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        border-radius: 15px;
        padding: 30px;
        margin: 40px 0;
        text-align: center;
        border: 1px solid #f9ca24;
    }

    .qr-example {
        width: 120px;
        height: 120px;
        background: #f8f9fa;
        border: 2px dashed #6c757d;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 20px auto;
        font-size: 2rem;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 60px 0;
        }
        
        .verification-card {
            padding: 25px;
            margin: 20px 10px;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
            gap: 20px;
            margin: 40px 0;
        }
        
        .form-control-lg {
            font-size: 1rem;
            padding: 12px 16px;
        }
        
        .btn-verify {
            font-size: 1rem;
            padding: 12px 30px;
        }

        /* Styles responsive pour le nouveau design */
        .certificate-result-card {
            margin: 20px 0;
            border-radius: 15px;
        }

        .result-header {
            padding: 20px;
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .status-indicator {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .status-content h4 {
            font-size: 1.3rem;
        }

        .certificate-main-info {
            padding: 25px 20px;
        }

        .holder-section, .course-section {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .holder-avatar, .course-icon {
            margin: 0 auto;
        }

        .course-meta {
            justify-content: center;
        }

        .certificate-metadata {
            padding: 20px;
        }

        .metadata-item {
            flex-direction: column;
            text-align: center;
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 15px;
            border-bottom: none;
        }

        .certificate-actions {
            padding: 20px;
            flex-direction: column;
        }

        .btn-view-certificate, .btn-share {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4" data-aos="fade-up">
                    <i class="fas fa-shield-check me-3"></i>
                    Vérificateur de Certificat
                </h1>
                <p class="lead mb-4" data-aos="fade-up" data-aos-delay="200">
                    Vérifiez instantanément l'authenticité des certificats émis par AfriCode. 
                    Entrez le code de vérification pour obtenir toutes les informations du certificat.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="400">
                    <span class="badge bg-light text-dark px-3 py-2 fs-6">
                        <i class="fas fa-check me-2"></i>Vérification instantanée
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2 fs-6">
                        <i class="fas fa-globe me-2"></i>Accessible 24h/24
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2 fs-6">
                        <i class="fas fa-lock me-2"></i>100% sécurisé
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <!-- Formulaire de vérification -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="verification-card" data-aos="fade-up">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">
                        <i class="fas fa-search me-2"></i>
                        Entrez le code de vérification
                    </h3>
                    <p class="text-muted">
                        Saisissez le code qui figure sur le certificat ou scannez le QR code
                    </p>
                </div>

                <form action="{{ route('certificate.verification') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="verification_code" class="form-label fw-semibold">
                            <i class="fas fa-key me-2 text-primary"></i>
                            Code de vérification
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg text-center" 
                               id="verification_code" 
                               name="verification_code" 
                               placeholder="Ex: AFC-XXXXXXXX ou AF-2025-XXXX" 
                               value="{{ $submitted_code ?? old('verification_code') }}"
                               required 
                               style="letter-spacing: 2px; font-weight: 600;">
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Le code se trouve généralement en bas du certificat
                        </small>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-verify text-white">
                            <i class="fas fa-search me-2"></i>
                            Vérifier maintenant
                        </button>
                    </div>
                </form>
            </div>

            <!-- Résultats de la vérification -->
            @if(isset($certification) && $certification)
                <!-- Certificat Valide - Design moderne -->
                <div class="certificate-result-card" data-aos="fade-up" data-aos-delay="200">
                    <!-- Header avec status -->
                    <div class="result-header">
                        <div class="status-indicator">
                            <div class="status-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <div class="status-content">
                                <h4 class="status-title">Certificat Authentique</h4>
                                <p class="status-subtitle">Vérifié avec succès dans notre base de données</p>
                            </div>
                        </div>
                        <div class="verification-badge">
                            <i class="fas fa-check-circle"></i>
                            <span>VÉRIFIÉ</span>
                        </div>
                    </div>

                    <!-- Informations principales du certificat -->
                    <div class="certificate-main-info">
                        <div class="holder-section">
                            <div class="holder-avatar">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="holder-details">
                                <h5 class="holder-name">{{ $certification->user->first_name }} {{ $certification->user->last_name }}</h5>
                                <p class="holder-email">{{ $certification->user->email }}</p>
                            </div>
                        </div>

                        <div class="course-section">
                            <div class="course-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="course-details">
                                <h6 class="course-title">{{ $certification->course->title }}</h6>
                                <div class="course-meta">
                                    @if($certification->course->category)
                                        <span class="course-category">
                                            <i class="fas fa-tag"></i>
                                            {{ $certification->course->category->name }}
                                        </span>
                                    @endif
                                    @if($certification->course->level)
                                        <span class="course-level">
                                            <i class="fas fa-chart-line"></i>
                                            {{ $certification->course->level }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Métadonnées du certificat -->
                    <div class="certificate-metadata">
                        <div class="metadata-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="metadata-content">
                                <span class="metadata-label">Date d'émission</span>
                                <span class="metadata-value">{{ $certification->issued_at->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="metadata-item">
                            <i class="fas fa-key"></i>
                            <div class="metadata-content">
                                <span class="metadata-label">Code de vérification</span>
                                <span class="metadata-value code">{{ $certification->verification_code }}</span>
                            </div>
                        </div>
                        <div class="metadata-item">
                            <i class="fas fa-fingerprint"></i>
                            <div class="metadata-content">
                                <span class="metadata-label">Identifiant unique</span>
                                <span class="metadata-value code">{{ $certification->certificate_identifier }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="certificate-actions">
                        <a href="{{ route('public.certificate.show', ['certification' => $certification->verification_code]) }}" 
                           class="btn btn-view-certificate">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Voir le certificat complet
                        </a>
                        <button class="btn btn-share" onclick="shareCertificate('{{ $certification->verification_code }}')">
                            <i class="fas fa-share-alt me-2"></i>
                            Partager
                        </button>
                    </div>
                </div>
                
            @elseif(isset($error) && $error)
                <div class="alert alert-danger" data-aos="fade-up" data-aos-delay="200">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle fa-2x text-danger me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">❌ Certificat non trouvé</h5>
                            <p class="mb-0">{{ $error }}</p>
                        </div>
                    </div>
                </div>
                
            @elseif(request()->isMethod('post'))
                <div class="alert alert-warning" data-aos="fade-up" data-aos-delay="200">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x text-warning me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">ℹ️ Aucun code fourni</h5>
                            <p class="mb-0">Veuillez entrer un code de certificat pour lancer la vérification.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Guide de scan QR -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="scan-guide" data-aos="fade-up" data-aos-delay="300">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-qrcode me-2"></i>
                    Vous avez un QR Code ?
                </h5>
                <p class="mb-3">
                    Scannez le QR Code présent sur le certificat avec votre téléphone pour une vérification automatique
                </p>
                <div class="qr-example">
                    <i class="fas fa-qrcode"></i>
                </div>
                <small class="text-muted">
                    <i class="fas fa-mobile-alt me-1"></i>
                    Utilisez l'appareil photo de votre téléphone ou une application de scan QR
                </small>
            </div>
        </div>
    </div>

    <!-- Fonctionnalités -->
    <section class="features-grid" data-aos="fade-up" data-aos-delay="400">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-lightning-bolt"></i>
            </div>
            <h5 class="fw-bold mb-3">Vérification instantanée</h5>
            <p class="text-muted">
                Obtenez le résultat de la vérification en quelques secondes seulement
            </p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h5 class="fw-bold mb-3">Sécurité maximale</h5>
            <p class="text-muted">
                Chaque certificat est protégé par un code unique et inviolable
            </p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-globe"></i>
            </div>
            <h5 class="fw-bold mb-3">Accessible partout</h5>
            <p class="text-muted">
                Vérifiez l'authenticité depuis n'importe où, 24h/24 et 7j/7
            </p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <h5 class="fw-bold mb-3">Informations complètes</h5>
            <p class="text-muted">
                Accédez aux détails complets du certificat et de la formation
            </p>
        </div>
    </section>

    <!-- Section d'aide -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg" data-aos="fade-up" data-aos-delay="500">
                <div class="card-body p-4">
                    <h5 class="card-title text-center mb-4">
                        <i class="fas fa-question-circle me-2 text-primary"></i>
                        Besoin d'aide ?
                    </h5>
                    
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                            <h6>Contactez-nous</h6>
                            <a href="{{ route('pages.contact') }}" class="text-decoration-none">
                                support@africode.tech
                            </a>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                            <h6>Appelez-nous</h6>
                            <span class="text-muted">+225 XX XX XX XX</span>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-clock fa-2x text-primary mb-3"></i>
                            <h6>Disponibilité</h6>
                            <span class="text-muted">Lun-Ven 8h-18h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Espace en bas -->
<div style="height: 80px;"></div>
@endsection

@push('scripts')
<script>
    // Auto-format le code pendant la saisie
    document.getElementById('verification_code').addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
        e.target.value = value;
    });
    
    // Sélectionner tout le texte au focus
    document.getElementById('verification_code').addEventListener('focus', function(e) {
        e.target.select();
    });
    
    // Animation du bouton au submit
    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.querySelector('.btn-verify');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Vérification en cours...';
        btn.disabled = true;
    });
    
    // Scroll automatique vers les résultats si ils existent
    @if(isset($certification) || isset($error))
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const resultsSection = document.querySelector('.certificate-result-card, .alert');
            if (resultsSection) {
                resultsSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }
        }, 500);
    });
    @endif
    
    // Animation d'apparition des résultats
    @if(isset($certification))
    document.addEventListener('DOMContentLoaded', function() {
        const certCard = document.querySelector('.certificate-result-card');
        if (certCard) {
            certCard.style.transform = 'translateY(20px)';
            certCard.style.opacity = '0';
            setTimeout(function() {
                certCard.style.transition = 'all 0.6s ease';
                certCard.style.transform = 'translateY(0)';
                certCard.style.opacity = '1';
            }, 100);
        }
    });
    @endif

    // Fonction pour partager le certificat
    function shareCertificate(verificationCode) {
        const url = window.location.origin + '/c/' + verificationCode;
        const text = 'Vérifiez ce certificat AfriCode : ' + url;
        
        if (navigator.share) {
            // API Web Share (mobile)
            navigator.share({
                title: 'Certificat AfriCode',
                text: 'Vérifiez l\'authenticité de ce certificat',
                url: url
            });
        } else {
            // Fallback - copier dans le presse-papier
            navigator.clipboard.writeText(text).then(function() {
                // Afficher une notification
                showNotification('Lien copié dans le presse-papier !', 'success');
            }).catch(function() {
                // Fallback pour les anciens navigateurs
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('Lien copié dans le presse-papier !', 'success');
            });
        }
    }

    // Fonction pour afficher une notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle me-2"></i>
            ${message}
        `;
        
        // Styles de la notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#d4edda' : '#d1ecf1'};
            color: ${type === 'success' ? '#155724' : '#0c5460'};
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 10000;
            font-weight: 500;
            min-width: 250px;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        // Animation d'entrée
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Animation de sortie et suppression
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
</script>
@endpush
