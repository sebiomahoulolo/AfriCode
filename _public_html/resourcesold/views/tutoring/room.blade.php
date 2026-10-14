@extends('layouts.mentorship')

@section('title', 'Session de Tutorat - AfriCode')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
<style>
    body {
        background-color: #ffffff !important;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    <!-- Header de la session -->
    <div class="session-header bg-primary text-white p-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    @auth
                        @if(Auth::user()->role === 'apprenant')
                            <a href="{{ route('apprenant.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        @elseif(Auth::user()->role === 'formateur')
                            <a href="{{ route('formateur.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        @elseif(Auth::user()->role === 'administrateur' || Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        @endif
                    @else
                        <a href="{{ route('home') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    @endauth
                </div>
                <h4 class="mb-0">
                    <i class="fas fa-video me-2"></i>{{ $session->title }}
                </h4>
                <small>Code de session: <strong>{{ $session->session_code }}</strong></small>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="session-info">
                    <span class="badge bg-light text-dark me-2">
                        <i class="fas fa-clock me-1"></i>{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-users me-1"></i>{{ $session->participants->count() + 1 }} participants
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0" style="height: calc(100vh - 80px);">
        <!-- Zone vidéo principale -->
        <div class="col-lg-8">
            <div class="video-container h-100 position-relative">
                <!-- Jitsi Meet iframe -->
                <iframe 
                    id="jitsi-iframe"
                    src="{{ $session->meeting_link }}"
                    allow="camera; microphone; fullscreen; speaker; display-capture"
                    style="width: 100%; height: 100%; border: none;">
                </iframe>
                
                <!-- Overlay de contrôle -->
                <div class="video-controls position-absolute top-0 end-0 p-3">
                    <div class="btn-group" role="group">
                        @if($session->tutor->user_id === Auth::id())
                            @if($session->status === 'scheduled')
                                <form method="POST" action="{{ route('tutoring.sessions.start', $session) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-play me-1"></i>Démarrer
                                    </button>
                                </form>
                            @elseif($session->status === 'in_progress')
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#completeModal">
                                    <i class="fas fa-stop me-1"></i>Terminer
                                </button>
                            @endif
                        @endif
                        
                        <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#sessionInfoModal">
                            <i class="fas fa-info me-1"></i>Infos
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar avec chat et participants -->
        <div class="col-lg-4">
            <div class="h-100 d-flex flex-column">
                <!-- Onglets -->
                <ul class="nav nav-tabs" id="sessionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="chat-tab" data-bs-toggle="tab" data-bs-target="#chat" type="button" role="tab">
                            <i class="fas fa-comments me-1"></i>Chat
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="participants-tab" data-bs-toggle="tab" data-bs-target="#participants" type="button" role="tab">
                            <i class="fas fa-users me-1"></i>Participants
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources" type="button" role="tab">
                            <i class="fas fa-link me-1"></i>Ressources
                        </button>
                    </li>
                </ul>

                <!-- Contenu des onglets -->
                <div class="tab-content flex-grow-1" id="sessionTabContent">
                    <!-- Chat -->
                    <div class="tab-pane fade show active h-100 d-flex flex-column" id="chat" role="tabpanel">
                        <!-- Messages -->
                        <div class="chat-messages flex-grow-1 p-3" id="chatMessages" style="overflow-y: auto; max-height: 400px;">
                            @foreach($session->messages as $message)
                                <div class="message mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ substr($message->user->first_name, 0, 1) }}{{ substr($message->user->last_name, 0, 1) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="message-header">
                                                <strong>{{ $message->user->first_name }} {{ $message->user->last_name }}</strong>
                                                <small class="text-muted ms-2">{{ $message->created_at->format('H:i') }}</small>
                                            </div>
                                            <div class="message-content">
                                                {{ $message->message }}
                                            </div>
                                            @if($message->attachments->count() > 0)
                                                <div class="message-attachments mt-2">
                                                    @foreach($message->attachments as $attachment)
                                                        <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank" class="btn btn-outline-primary btn-sm me-1">
                                                            <i class="fas fa-paperclip me-1"></i>{{ $attachment->filename }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Formulaire de message -->
                        <div class="chat-input p-3 border-top">
                            <form id="messageForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-2">
                                    <textarea class="form-control" id="messageInput" name="message" rows="2" 
                                              placeholder="Tapez votre message..." required></textarea>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="file" id="fileInput" name="attachments[]" multiple 
                                               accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif" style="display: none;">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('fileInput').click()">
                                            <i class="fas fa-paperclip"></i>
                                        </button>
                                        <span id="fileCount" class="text-muted small ms-2"></span>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Participants -->
                    <div class="tab-pane fade h-100" id="participants" role="tabpanel">
                        <div class="p-3">
                            <h6 class="mb-3">Participants de la session</h6>
                            
                            <!-- Tuteur -->
                            <div class="participant-item d-flex align-items-center mb-3 p-2 bg-light rounded">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    {{ substr($session->tutor->user->first_name, 0, 1) }}{{ substr($session->tutor->user->last_name, 0, 1) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $session->tutor->user->first_name }} {{ $session->tutor->user->last_name }}</div>
                                    <small class="text-muted">Tuteur</small>
                                </div>
                                <span class="badge bg-success">En ligne</span>
                            </div>

                            <!-- Étudiant principal -->
                            <div class="participant-item d-flex align-items-center mb-3 p-2 bg-light rounded">
                                <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    {{ substr($session->student->first_name, 0, 1) }}{{ substr($session->student->last_name, 0, 1) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $session->student->first_name }} {{ $session->student->last_name }}</div>
                                    <small class="text-muted">Étudiant</small>
                                </div>
                                <span class="badge bg-success">En ligne</span>
                            </div>

                            <!-- Participants supplémentaires -->
                            @foreach($session->participants as $participant)
                                <div class="participant-item d-flex align-items-center mb-3 p-2 bg-light rounded">
                                    <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        {{ substr($participant->user->first_name, 0, 1) }}{{ substr($participant->user->last_name, 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $participant->user->first_name }} {{ $participant->user->last_name }}</div>
                                        <small class="text-muted">Participant</small>
                                    </div>
                                    <span class="badge {{ $participant->joined_at ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $participant->joined_at ? 'Rejoint' : 'En attente' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ressources -->
                    <div class="tab-pane fade h-100" id="resources" role="tabpanel">
                        <div class="p-3">
                            <h6 class="mb-3">Ressources de la session</h6>
                            
                            @if($session->sessionResources->count() > 0)
                                @foreach($session->sessionResources as $resource)
                                    <div class="resource-item mb-3 p-3 border rounded">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                @switch($resource->type)
                                                    @case('link')
                                                        <i class="fas fa-link text-primary fa-2x"></i>
                                                        @break
                                                    @case('file')
                                                        <i class="fas fa-file text-secondary fa-2x"></i>
                                                        @break
                                                    @case('video')
                                                        <i class="fas fa-video text-danger fa-2x"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-file-alt text-info fa-2x"></i>
                                                @endswitch
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $resource->title }}</h6>
                                                @if($resource->description)
                                                    <p class="text-muted small mb-2">{{ $resource->description }}</p>
                                                @endif
                                                @if($resource->url)
                                                    <a href="{{ $resource->url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-external-link-alt me-1"></i>Ouvrir
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-link fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Aucune ressource partagée pour le moment.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'informations de session -->
<div class="modal fade" id="sessionInfoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informations de la Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Titre:</strong> {{ $session->title }}
                </div>
                <div class="mb-3">
                    <strong>Description:</strong> {{ $session->description }}
                </div>
                <div class="mb-3">
                    <strong>Code de session:</strong> <code>{{ $session->session_code }}</code>
                </div>
                <div class="mb-3">
                    <strong>Durée:</strong> {{ $session->duration_minutes }} minutes
                </div>
                <div class="mb-3">
                    <strong>Statut:</strong> 
                    <span class="badge bg-{{ $session->status === 'in_progress' ? 'success' : 'primary' }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de fin de session -->
@if($session->tutor->user_id === Auth::id())
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terminer la Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('tutoring.sessions.complete', $session) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes de la session</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Notes sur le déroulement de la session..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Feedback pour les participants</label>
                        <textarea class="form-control" id="feedback" name="feedback" rows="3" 
                                  placeholder="Feedback et recommandations..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Terminer la Session</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
// Gestion du chat en temps réel
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    fetch('{{ route("tutoring.sessions.send-message", $session) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            document.getElementById('fileCount').textContent = '';
            document.getElementById('fileInput').value = '';
            // Ajouter le message à l'interface
            addMessageToChat(message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
});

function addMessageToChat(message) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message mb-3';
    messageDiv.innerHTML = `
        <div class="d-flex align-items-start">
            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
            </div>
            <div class="flex-grow-1">
                <div class="message-header">
                    <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong>
                    <small class="text-muted ms-2">${new Date().toLocaleTimeString()}</small>
                </div>
                <div class="message-content">${message}</div>
            </div>
        </div>
    `;
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Gestion des fichiers
document.getElementById('fileInput').addEventListener('change', function() {
    const fileCount = this.files.length;
    const fileCountSpan = document.getElementById('fileCount');
    if (fileCount > 0) {
        fileCountSpan.textContent = `${fileCount} fichier(s) sélectionné(s)`;
    } else {
        fileCountSpan.textContent = '';
    }
});

// Auto-scroll du chat
const chatMessages = document.getElementById('chatMessages');
chatMessages.scrollTop = chatMessages.scrollHeight;
</script>

<style>
.session-header {
    position: sticky;
    top: 0;
    z-index: 1000;
}

.video-container {
    background: #000;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
    font-weight: bold;
}

.message {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-messages {
    background: #f8f9fa;
}

.participant-item {
    transition: all 0.3s ease;
}

.participant-item:hover {
    background-color: #e9ecef !important;
}

.resource-item {
    transition: all 0.3s ease;
}

.resource-item:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.nav-tabs .nav-link {
    border-radius: 0;
}

.nav-tabs .nav-link.active {
    background-color: #f8f9fa;
    border-color: #dee2e6 #dee2e6 #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const fileInput = document.getElementById('fileInput');
    const fileCount = document.getElementById('fileCount');
    const chatMessages = document.getElementById('chatMessages');

    // Gestion des fichiers sélectionnés
    fileInput.addEventListener('change', function() {
        const files = this.files;
        if (files.length > 0) {
            fileCount.textContent = `${files.length} fichier(s) sélectionné(s)`;
            fileCount.style.color = '#007bff';
        } else {
            fileCount.textContent = '';
        }
    });

    // Envoi de message
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const message = messageInput.value.trim();
        
        if (!message && fileInput.files.length === 0) {
            alert('Veuillez saisir un message ou sélectionner un fichier.');
            return;
        }

        // Désactiver le formulaire pendant l'envoi
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        submitBtn.disabled = true;

        fetch('{{ route("tutoring.sessions.send-message", $session) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Vider le formulaire
                messageInput.value = '';
                fileInput.value = '';
                fileCount.textContent = '';
                
                // Recharger les messages
                loadMessages();
                
                // Faire défiler vers le bas
                chatMessages.scrollTop = chatMessages.scrollHeight;
            } else {
                alert('Erreur lors de l\'envoi du message.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'envoi du message.');
        })
        .finally(() => {
            // Réactiver le formulaire
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Charger les messages
    function loadMessages() {
        fetch('{{ route("tutoring.sessions.show", $session) }}')
        .then(response => response.text())
        .then(html => {
            // Extraire les messages du HTML retourné
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newMessages = doc.querySelectorAll('.message');
            
            // Remplacer les messages existants
            chatMessages.innerHTML = '';
            newMessages.forEach(message => {
                chatMessages.appendChild(message);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des messages:', error);
        });
    }

    // Auto-scroll vers le bas au chargement
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Rafraîchir les messages toutes les 30 secondes
    setInterval(loadMessages, 30000);
});
</script>
@endsection
