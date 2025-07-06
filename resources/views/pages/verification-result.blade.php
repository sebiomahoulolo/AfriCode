@extends('layouts.app')

@section('title', 'AfriCode')

@push('styles')
    {{-- J'intègre ici les styles du certificat pour un rendu cohérent. --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1EA38B; --secondary-color: #FF8E2A; --accent-color: #E32D31;
            --highlight-color: #27B371; --background-color: #F8F9FA; --light-accent: #ECF0F1;
            --text-color: #333333;
        }
        body { background-color: var(--background-color); font-family: 'Segoe UI', sans-serif; }
        .verification-container { padding: 40px 15px; }
        .result-card { border-radius: 15px; box-shadow: 0 8px 30px rgba(0,0,0,0.1); }
        
        /* Styles pour un certificat valide */
        .certificate-preview { border: 8px solid var(--primary-color); padding: 30px; background: #fff; text-align: center; }
        .certificate-header .logo-text { font-size: 24px; font-weight: bold; color: var(--primary-color); }
        .certificate-header .title { font-size: 28px; font-weight: 700; color: var(--text-color); margin-top: 15px; }
        .student-name { font-size: 24px; font-weight: 700; color: var(--primary-color); margin: 20px 0; border-bottom: 2px dashed var(--secondary-color); display: inline-block; padding-bottom: 5px; }
        .course-name { font-size: 20px; font-weight: 600; color: var(--text-color); }
        .certificate-details span { display: inline-block; background: var(--light-accent); color: var(--text-color); padding: 8px 15px; border-radius: 20px; margin: 5px; font-size: 14px; }
        .verification-success-banner { background-color: var(--highlight-color); color: white; padding: 15px; border-radius: 8px 8px 0 0; }
        
        /* Styles pour une erreur */
        .verification-error-banner { background-color: var(--accent-color); color: white; padding: 15px; text-align: center; border-radius: 8px; }

        .actions { margin-top: 30px; text-align: center; }
    </style>
@endpush

@section('content')
<div class="verification-container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            @if(isset($certification))
                {{-- Affichage pour un certificat VALIDE --}}
                <div class="card result-card">
                    <div class="verification-success-banner text-center">
                        <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Certificat Authentifié</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="certificate-preview">
                            <div class="certificate-header">
                                <div class="logo-text">AFRICODE</div>
                                <h1 class="title">Certificat de Réussite</h1>
                            </div>
                            <div class="my-4">
                                <p class="text-muted mb-2">Ce certificat est fièrement décerné à :</p>
                                <h2 class="student-name">{{ $certification->user->first_name }} {{ $certification->user->last_name }}</h2>
                                <p class="mt-3">pour avoir complété avec succès la formation :</p>
                                <h3 class="course-name">{{ $certification->course->title }}</h3>
                            </div>
                            <div class="certificate-details my-4">
                                <span><i class="fas fa-calendar-alt me-2"></i>Délivré le: {{ $certification->issued_at->format('d/m/Y') }}</span>
                                <span><i class="fas fa-barcode me-2"></i>Code: {{ $certification->verification_code }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Affichage pour un certificat INVALIDE ou non trouvé --}}
                <div class="verification-error-banner">
                    <h4 class="mb-0"><i class="fas fa-times-circle me-2"></i>Certificat Non Trouvé</h4>
                    <p class="mt-2 mb-0">{{ $message ?? "Le code de vérification fourni est invalide ou le certificat n'existe pas." }}</p>
                </div>
            @endif

            <div class="actions">
                <a href="{{ route('verification.form') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Effectuer une autre vérification
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 