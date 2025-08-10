@extends('layouts.app')

@section('title', 'Statut du Paiement - ' . $course->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">
                    <!-- Statut du paiement -->
                    <div class="mb-4">
                        @if($payment->status === 'pending')
                            <div class="spinner-border text-warning mb-3" style="width: 4rem; height: 4rem;" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <h3 class="text-warning">Paiement en attente</h3>
                            <p class="text-muted">Votre paiement est en cours de traitement. Veuillez patienter...</p>
                        @elseif($payment->status === 'processing')
                            <div class="spinner-border text-info mb-3" style="width: 4rem; height: 4rem;" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <h3 class="text-info">Paiement en traitement</h3>
                            <p class="text-muted">Votre paiement est en cours de validation. Cela peut prendre quelques minutes.</p>
                        @elseif($payment->status === 'failed')
                            <i class="fas fa-times-circle text-danger mb-3" style="font-size: 4rem;"></i>
                            <h3 class="text-danger">Paiement échoué</h3>
                            <p class="text-muted">Une erreur est survenue lors du paiement. Vous pouvez réessayer.</p>
                        @else
                            <i class="fas fa-question-circle text-secondary mb-3" style="font-size: 4rem;"></i>
                            <h3 class="text-secondary">Statut inconnu</h3>
                            <p class="text-muted">Le statut du paiement n'est pas déterminé.</p>
                        @endif
                    </div>

                    <!-- Informations du cours -->
                    <div class="course-info bg-light rounded p-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" 
                                         alt="{{ $course->title }}" 
                                         class="img-fluid rounded">
                                @else
                                    <div class="bg-primary rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                        <i class="fas fa-book text-white fs-3"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9 text-start">
                                <h5 class="mb-2">{{ $course->title }}</h5>
                                <p class="text-muted small mb-2">{{ Str::limit($course->description, 120) }}</p>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Prix :</strong> {{ number_format($payment->amount) }} {{ $payment->currency }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Transaction :</strong> #{{ $payment->transaction_id }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Barre de progression pour les paiements en cours -->
                    @if(in_array($payment->status, ['pending', 'processing']))
                        <div class="progress mb-4">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: {{ $payment->status === 'pending' ? '50' : '75' }}%">
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="action-buttons">
                        @if($payment->status === 'failed')
                            <!-- Bouton pour réessayer le paiement -->
                            <a href="{{ route('enrollment.show', $course->id) }}" 
                               class="btn btn-primary btn-lg px-5 me-3">
                                <i class="fas fa-redo me-2"></i>Réessayer le Paiement
                            </a>
                        @elseif(in_array($payment->status, ['pending', 'processing']))
                            <!-- Bouton pour vérifier le statut -->
                            <button onclick="checkPaymentStatus()" class="btn btn-outline-primary me-3">
                                <i class="fas fa-sync-alt me-2"></i>Vérifier le Statut
                            </button>
                        @endif

                        <!-- Bouton retour au cours -->
                        <a href="{{ route('courses.show', $course->slug ?? $course->id) }}" 
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour au Cours
                        </a>
                    </div>

                    <!-- Informations de contact -->
                    <div class="mt-5 pt-4 border-top">
                        <p class="text-muted small">
                            <i class="fas fa-info-circle me-2"></i>
                            Si vous rencontrez des problèmes, 
                            <a href="{{ route('contact') }}">contactez notre support</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function checkPaymentStatus() {
    fetch(`/api/payment/{{ $payment->id }}/status`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'completed') {
            // Paiement complété, rediriger vers le cours
            window.location.href = "{{ route('apprenant.course.access', ['courseId' => $course->id]) }}";
        } else if (data.status === 'failed') {
            // Paiement échoué, recharger la page
            location.reload();
        } else {
            // Toujours en cours, afficher un message
            showNotification('Le paiement est toujours en cours de traitement...', 'info');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la vérification:', error);
        showNotification('Erreur lors de la vérification du statut', 'error');
    });
}

function showNotification(message, type) {
    // Créer une notification simple
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'info' ? 'info' : 'success'} alert-dismissible fade show position-fixed`;
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '1050';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Vérification automatique toutes les 30 secondes pour les paiements en cours
@if(in_array($payment->status, ['pending', 'processing']))
    setInterval(checkPaymentStatus, 30000);
@endif
</script>
@endpush

<style>
.course-info {
    transition: all 0.3s ease;
}

.course-info:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.action-buttons .btn {
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
}
</style>
@endsection
