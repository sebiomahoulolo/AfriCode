@extends('layouts.app')

@section('title', 'AfriCode')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #1EA38B;
        --secondary-color: #FF8E2A;
        --accent-color: #E32D31;
        --background-color: #F0F4F7;
        --text-color: #333;
        --light-gray: #f8f9fa;
    }

    body {
        background-color: var(--background-color);
    }

    .verification-page {
        padding: 50px 0;
    }

    .verification-card {
        background-color: #fff;
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .verification-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 30px;
        text-align: center;
    }

    .verification-header i {
        font-size: 4rem;
        margin-bottom: 1rem;
        text-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .verification-body {
        padding: 30px;
    }

    .form-control-lg {
        border-radius: 10px;
        font-size: 1.1rem;
        padding: 12px 20px;
    }
    
    .btn-lg {
        border-radius: 10px;
        padding: 12px;
        font-weight: bold;
    }

    #result-container {
        margin-top: 30px;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .cert-result-card {
        border-left: 5px solid;
        border-radius: 10px;
    }

    .cert-valid {
        border-left-color: var(--primary-color);
    }
    .cert-invalid {
        border-left-color: var(--accent-color);
    }

    .cert-result-header {
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }

    .cert-result-body {
        padding: 20px;
    }

    .cert-result-body .info-item {
        margin-bottom: 1rem;
    }

    .info-item label {
        font-weight: bold;
        color: #555;
        display: block;
        font-size: 0.9rem;
    }

    .info-item span {
        font-size: 1.1rem;
    }

</style>
@endpush

@section('content')
<div class="verification-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="verification-card">
                    <div class="verification-header">
                        <i class="fas fa-shield-alt"></i>
                        <h1 class="h3 mb-1">Vérificateur de Certificat</h1>
                        <p class="mb-0">Confirmez l'authenticité des certificats émis par AfriCode.</p>
                    </div>
                    <div class="verification-body">
                        <form action="{{ route('verification.form') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="verification_code" class="form-label visually-hidden">Code de Vérification</label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="verification_code" 
                                       name="verification_code" 
                                       placeholder="Entrez le code ici..." 
                                       value="{{ $submitted_code ?? old('verification_code') }}"
                                       required 
                                       aria-label="Code de Vérification">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                                    <i class="fas fa-check-circle me-2"></i>Vérifier Maintenant
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="result-container">
                    @if(isset($certification) && $certification)
                        <div class="card cert-result-card cert-valid mt-4">
                            <div class="card-header cert-result-header bg-light">
                                <h5 class="mb-0 text-success"><i class="fas fa-check-circle me-2"></i>Certificat Valide</h5>
                            </div>
                            <div class="card-body cert-result-body">
                                <div class="row">
                                    <div class="col-md-6 info-item">
                                        <label>Détenteur</label>
                                        <span>{{ $certification->user->first_name }} {{ $certification->user->last_name }}</span>
                                    </div>
                                    <div class="col-md-6 info-item">
                                        <label>Formation</label>
                                        <span>{{ $certification->course->title }}</span>
                                    </div>
                                    <div class="col-md-6 info-item">
                                        <label>Date d'émission</label>
                                        <span>{{ $certification->issued_at->format('d F Y') }}</span>
                                    </div>
                                    <div class="col-md-6 info-item">
                                        <label>Code de vérification</label>
                                        <span class="font-monospace">{{ $certification->verification_code }}</span>
                                    </div>
                                    @if($certification->course->category)
                                    <div class="col-md-6 info-item">
                                        <label>Catégorie</label>
                                        <span>{{ $certification->course->category->name }}</span>
                                    </div>
                                    @endif
                                </div>
                                <hr class="my-3">
                                <div class="text-center">
                                    <a href="{{ route('public.certificate.show', ['certification' => $certification->verification_code]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-2"></i>Voir le certificat complet
                                    </a>
                                </div>
                            </div>
                        </div>
                    @elseif(isset($error) && $error)
                        <div class="alert alert-danger d-flex align-items-center mt-4" role="alert">
                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading">Erreur de vérification</h5>
                                {{ $error }}
                            </div>
                        </div>
                    @elseif(request()->isMethod('post'))
                        <div class="alert alert-warning d-flex align-items-center mt-4" role="alert">
                           <i class="fas fa-info-circle fa-2x me-3"></i>
                           <div>
                                Veuillez entrer un code de certificat pour lancer la vérification.
                           </div>
                        </div>
                    @endif
                </div>

                 <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-muted small"><i class="fas fa-arrow-left me-1"></i> Retour à l'accueil</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection 