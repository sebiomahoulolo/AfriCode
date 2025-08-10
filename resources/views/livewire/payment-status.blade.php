<div class="payment-status-container">
    <div class="card border-0 shadow-lg">
        <div class="card-body text-center p-5">
            <!-- Status Icon -->
            <div class="status-icon mb-4">
                @if($status === 'pending' || $status === 'processing')
                    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                @elseif($status === 'completed')
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                @elseif($status === 'failed')
                    <i class="fas fa-times-circle text-danger" style="font-size: 4rem;"></i>
                @else
                    <i class="fas fa-question-circle text-warning" style="font-size: 4rem;"></i>
                @endif
            </div>

            <!-- Status Message -->
            <h3 class="mb-3">
                @if($status === 'pending' || $status === 'processing')
                    Traitement en cours...
                @elseif($status === 'completed')
                    Paiement réussi !
                @elseif($status === 'failed')
                    Paiement échoué
                @else
                    {{ ucfirst($status) }}
                @endif
            </h3>

            <p class="text-muted mb-4">{{ $message }}</p>

            <!-- Course Information -->
            <div class="course-info bg-light rounded p-3 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" 
                                 alt="{{ $course->title }}" 
                                 class="img-fluid rounded">
                        @endif
                    </div>
                    <div class="col-md-9 text-start">
                        <h5 class="mb-2">{{ $course->title }}</h5>
                        <p class="text-muted small mb-2">{{ Str::limit($course->description, 100) }}</p>
                        <div class="row">
                            <div class="col-6">
                                <strong>Prix:</strong> {{ number_format($payment->amount) }} {{ $payment->currency }}
                            </div>
                            <div class="col-6">
                                <strong>Transaction:</strong> #{{ $payment->transaction_id }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar pour pending/processing -->
            @if(in_array($status, ['pending', 'processing']))
                <div class="progress mb-4">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: {{ $status === 'pending' ? '50' : '75' }}%">
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                @if($status === 'completed')
                    <a href="{{ route('apprenant.course.access', ['courseId' => $course->id]) }}" 
                       class="btn btn-success btn-lg px-5">
                        <i class="fas fa-play me-2"></i>Accéder au Cours
                    </a>
                @elseif($status === 'failed')
                    <button wire:click="retryPayment" class="btn btn-primary btn-lg px-5 me-3">
                        <i class="fas fa-redo me-2"></i>Réessayer le Paiement
                    </button>
                    <a href="{{ route('courses.show', $course->slug ?? $course->id) }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au Cours
                    </a>
                @elseif(in_array($status, ['pending', 'processing']))
                    <button wire:click="checkPaymentStatus" class="btn btn-outline-primary">
                        <i class="fas fa-sync-alt me-2"></i>Vérifier le Statut
                    </button>
                @endif
            </div>

            <!-- Payment Details (for admins or in development) -->
            @if(config('app.debug') && auth()->user()->isAdmin())
                <div class="mt-4 pt-4 border-top">
                    <details>
                        <summary class="btn btn-sm btn-outline-secondary">Détails Techniques</summary>
                        <div class="mt-3 text-start">
                            <pre class="bg-dark text-light p-3 rounded">{{ json_encode([
                                'payment_id' => $payment->id,
                                'status' => $payment->status,
                                'gateway' => $payment->payment_gateway,
                                'transaction_id' => $payment->transaction_id,
                                'gateway_response' => $payment->gateway_response
                            ], JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </details>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    let checkInterval;
    
    // Vérifier automatiquement le statut pour les paiements en cours
    @if(in_array($status, ['pending', 'processing']))
        checkInterval = setInterval(() => {
            @this.call('checkPaymentStatus');
        }, {{ $checkInterval }});
    @endif
    
    // Écouter les événements
    Livewire.on('payment-completed', () => {
        if (checkInterval) {
            clearInterval(checkInterval);
        }
        
        // Afficher une notification de succès
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Paiement réussi !',
                text: 'Vous allez être redirigé vers votre cours.',
                timer: 3000,
                showConfirmButton: false
            });
        }
        
        // Son de succès (optionnel)
        playNotificationSound('success');
    });
    
    Livewire.on('payment-failed', () => {
        if (checkInterval) {
            clearInterval(checkInterval);
        }
        
        // Afficher une notification d'erreur
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Paiement échoué',
                text: 'Une erreur est survenue lors du paiement. Vous pouvez réessayer.',
                confirmButtonText: 'Compris'
            });
        }
        
        // Son d'erreur (optionnel)
        playNotificationSound('error');
    });
    
    Livewire.on('redirect-to-course', (url) => {
        setTimeout(() => {
            window.location.href = url[0];
        }, 3000);
    });
});

function playNotificationSound(type) {
    try {
        const audio = new Audio();
        if (type === 'success') {
            audio.src = '/sounds/success.mp3'; // Ajoutez vos fichiers audio
        } else if (type === 'error') {
            audio.src = '/sounds/error.mp3';
        }
        audio.play().catch(() => {}); // Ignorer les erreurs si pas de son
    } catch (e) {
        // Ignorer les erreurs audio
    }
}
</script>
@endpush

<style>
.payment-status-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.card {
    max-width: 600px;
    width: 100%;
    border-radius: 20px;
}

.status-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

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

@media (max-width: 768px) {
    .payment-status-container {
        padding: 10px;
    }
    
    .card-body {
        padding: 2rem 1rem !important;
    }
}
</style>
