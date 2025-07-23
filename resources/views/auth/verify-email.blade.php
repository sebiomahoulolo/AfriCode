@extends('layouts.layout')

@section('title', 'Vérification Email - AfriCode')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow rounded-lg">
                <div class="card-header text-white text-center py-3" style="background-color: #1EA38B">
                    <h4 class="mb-0">
                        <i class="fas fa-envelope me-2" style="color:  #FF8E2A;"></i>
                        Vérifiez votre adresse email
                    </h4>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-envelope-open-text" style="font-size: 2rem; color:  #FF8E2A"></i>
                        </div>
                        <h5 class="text-primary">Merci de vous être inscrit sur AfriCode !</h5>
                        <p class="text-muted">
                            Avant de commencer, pourriez-vous vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer ? 
                            Si vous n'avez pas reçu l'email, nous vous en enverrons un autre.
                        </p>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2" style="color:  #FF8E2A;"></i>
                            Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie lors de l'inscription.
                        </div>
                    @endif

                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>
                            Que se passe-t-il après la vérification ?
                        </h6>
                        <ul class="mb-0 mt-2">
                            <li>Vous recevrez un email de bienvenue avec toutes les informations importantes</li>
                            <li>Vous pourrez accéder à votre tableau de bord personnalisé</li>
                            <li>Vous découvrirez nos formations et ressources exclusives</li>
                            <li>Vous rejoindrez notre communauté tech africaine</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn " style="background-color:  #1EA38B; color:white" >
                                <i class="fas fa-paper-plane me-2" style="color:  #FF8E2A;"></i>
                                Renvoyer l'email de vérification
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none">
                                <i class="fas fa-sign-out-alt me-2" style="color:  #FF8E2A;"></i>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
}

.btn-primary {
    background-color: #1EA38B;
    border-color: #1EA38B;
    padding: 12px 24px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #1a8f7a;
    border-color: #1a8f7a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 163, 139, 0.3);
}

.alert {
    border-radius: 10px;
    border: none;
}

.alert-info {
    background-color: rgba(30, 163, 139, 0.1);
    color: #1EA38B;
    border-left: 4px solid #1EA38B;
}

.alert-success {
    background-color: rgba(39, 179, 113, 0.1);
    color: #27B371;
    border-left: 4px solid #27B371;
}

.btn-link {
    color: #6c757d;
    transition: color 0.3s ease;
}

.btn-link:hover {
    color: #1EA38B;
}
</style>
@endsection
