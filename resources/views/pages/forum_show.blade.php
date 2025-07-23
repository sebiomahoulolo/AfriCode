<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $forum->title }} - Forum AfriCode</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1EA38B;
            --secondary-color: #FF8E2A;
            --accent-color: #E32D31;
            --highlight-color: #27B371;
            --background-color: #f4f7f6;
            --light-accent: #ECF0F1;
            --text-color: #333333;
            --card-bg: #ffffff;
            --border-color: #e0e0e0;
            --hover-bg: #f0f5f4;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--highlight-color));
            color: white;
            padding: 25px 20px;
            margin-bottom: 30px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .forum-topic {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .topic-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
        }

        .topic-title {
            font-size: 1.5em;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .topic-meta {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .topic-content {
            padding: 20px;
            line-height: 1.6;
        }

        .topic-content img {
            max-width: 100%;
            height: auto;
        }

        .replies-section {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .replies-header {
            background-color: var(--light-accent);
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--primary-color);
        }

        .reply-item {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .reply-item:last-child {
            border-bottom: none;
        }

        .reply-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .reply-author {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .reply-date {
            font-size: 0.85em;
            color: #666;
        }

        .reply-content {
            line-height: 1.6;
        }

        .reply-content img {
            max-width: 100%;
            height: auto;
        }

        .reply-form {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
            padding: 20px;
        }

        .reply-form h5 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .btn-reply {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .btn-reply:hover {
            background-color: #e67e22;
            border-color: #e67e22;
            color: white;
        }

        .breadcrumb-nav {
            background-color: var(--card-bg);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .breadcrumb-nav a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-nav a:hover {
            text-decoration: underline;
        }

        .no-replies {
            padding: 40px 20px;
            text-align: center;
            color: #666;
            font-style: italic;
        }

        @media (max-width: 767px) {
            .topic-title {
                font-size: 1.3em;
            }
            
            .reply-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .reply-date {
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>

    <header class="page-header">
        <div class="container">
            <h1><i class="fas fa-comments me-2"></i> Forum des Apprenants</h1>
            <p class="lead mb-0">Discussion et entraide entre apprenants</p>
        </div>
    </header>

    <div class="container mt-4">
        <!-- Navigation breadcrumb -->
        <nav class="breadcrumb-nav">
            <a href="{{ route('pages.forumapp') }}"><i class="fas fa-arrow-left me-1"></i> Retour au forum</a>
            <span class="mx-2">/</span>
            <span>{{ $forum->title }}</span>
        </nav>

        <!-- Sujet principal -->
        <div class="forum-topic">
            <div class="topic-header">
                <div class="topic-title">{{ $forum->title }}</div>
                <div class="topic-meta">
                    <i class="fas fa-user me-1"></i>
                    Par <a href="/profil/{{ $forum->user->id }}" class="text-white">{{ $forum->user->first_name ?? 'Utilisateur' }} {{ $forum->user->last_name ?? '' }}</a>
                    <span class="mx-2">•</span>
                    <i class="fas fa-calendar me-1"></i>
                    {{ $forum->created_at->format('d/m/Y à H:i') }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-tag me-1"></i>
                    {{ $forum->course->title ?? 'Général' }}
                </div>
            </div>
            <div class="topic-content">
                {!! $forum->content !!}
            </div>
        </div>

        <!-- Réponses -->
        <div class="replies-section">
            <div class="replies-header">
                <i class="fas fa-reply me-2"></i>
                Réponses ({{ $posts->count() }})
            </div>
            
            @forelse($posts as $post)
                <div class="reply-item">
                    <div class="reply-header">
                        <div class="reply-author">
                            <i class="fas fa-user me-1"></i>
                            <a href="/profil/{{ $post->user->id }}" class="text-decoration-none">{{ $post->user->first_name ?? 'Utilisateur' }} {{ $post->user->last_name ?? '' }}</a>
                        </div>
                        <div class="reply-date">
                            <i class="fas fa-clock me-1"></i>
                            {{ $post->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                    <div class="reply-content">
                        {!! $post->content !!}
                    </div>
                    
                    <!-- Réponses aux réponses -->
                    @if($post->replies->count() > 0)
                        <div class="mt-3 ms-4">
                            @foreach($post->replies as $reply)
                                <div class="reply-item" style="background-color: var(--light-accent); border-radius: 5px; margin-bottom: 10px;">
                                    <div class="reply-header">
                                        <div class="reply-author">
                                            <i class="fas fa-reply me-1"></i>
                                            <a href="/profil/{{ $reply->user->id }}" class="text-decoration-none">{{ $reply->user->first_name ?? 'Utilisateur' }} {{ $reply->user->last_name ?? '' }}</a>
                                        </div>
                                        <div class="reply-date">
                                            {{ $reply->created_at->format('d/m/Y à H:i') }}
                                        </div>
                                    </div>
                                    <div class="reply-content">
                                        {!! $reply->content !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="no-replies">
                    <i class="fas fa-comment-slash fa-2x mb-3 text-muted"></i>
                    <p>Aucune réponse pour le moment. Soyez le premier à répondre !</p>
                </div>
            @endforelse
        </div>

        <!-- Formulaire de réponse -->
        @auth
            <div class="reply-form">
                <h5><i class="fas fa-reply me-2"></i> Répondre à ce sujet</h5>
                <form id="replyForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="content" class="form-label">Votre réponse <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control" rows="6" required placeholder="Écrivez votre réponse..."></textarea>
                        <div id="content-error" class="text-danger small mt-1 d-none"></div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-reply" id="submitReplyBtn">
                            <span class="spinner-border spinner-border-sm d-none" id="replySpinner" role="status" aria-hidden="true"></span>
                            <i class="fas fa-paper-plane me-1"></i> Publier la réponse
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="reply-form">
                <div class="text-center">
                    <i class="fas fa-lock fa-2x mb-3 text-muted"></i>
                    <h5>Connexion requise</h5>
                    <p>Vous devez être connecté pour répondre à ce sujet.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-1"></i> Se connecter
                    </a>
                </div>
            </div>
        @endauth
    </div>

    <!-- Toast de succès pour la réponse -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="replyToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Réponse publiée avec succès !
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du formulaire de réponse en AJAX
            const replyForm = document.getElementById('replyForm');
            if (replyForm) {
                replyForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Nettoyer les erreurs précédentes
                    document.getElementById('content-error').classList.add('d-none');
                    document.getElementById('content-error').textContent = '';
                    
                    // Récupérer le contenu du textarea
                    const content = document.getElementById('content').value;
                    
                    // Validation côté client
                    if (!content.trim()) {
                        document.getElementById('content-error').textContent = 'Le contenu de la réponse est requis.';
                        document.getElementById('content-error').classList.remove('d-none');
                        return;
                    }
                    
                    // Désactiver le bouton et afficher le spinner
                    const submitBtn = document.getElementById('submitReplyBtn');
                    const spinner = document.getElementById('replySpinner');
                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');
                    
                    // Préparer les données
                    const formData = new FormData();
                    formData.append('content', content);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    // Envoyer la requête AJAX
                    fetch("{{ route('api.forum.reply', $forum->id) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Afficher le toast de succès
                            const toast = new bootstrap.Toast(document.getElementById('replyToast'));
                            toast.show();
                            
                            // Réinitialiser le formulaire
                            document.getElementById('content').value = '';
                            
                            // Ajouter la nouvelle réponse à la liste
                            addReplyToList(data.reply);
                            
                            // Mettre à jour le compteur de réponses
                            updateReplyCount();
                        } else if (data.errors) {
                            // Afficher les erreurs
                            if (data.errors.content) {
                                document.getElementById('content-error').textContent = data.errors.content[0];
                                document.getElementById('content-error').classList.remove('d-none');
                            }
                        } else {
                            alert(data.message || 'Erreur lors de la publication de la réponse.');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors de la publication de la réponse.');
                    })
                    .finally(() => {
                        // Réactiver le bouton et masquer le spinner
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    });
                });
            }
            
            // Fonction pour ajouter une nouvelle réponse à la liste
            function addReplyToList(reply) {
                const repliesSection = document.querySelector('.replies-section');
                const noReplies = repliesSection.querySelector('.no-replies');
                
                // Supprimer le message "aucune réponse" s'il existe
                if (noReplies) {
                    noReplies.remove();
                }
                
                // Créer l'élément de réponse
                const replyElement = document.createElement('div');
                replyElement.className = 'reply-item';
                replyElement.innerHTML = `
                    <div class="reply-header">
                        <div class="reply-author">
                            <i class="fas fa-user me-1"></i>
                            <a href="/profil/${reply.user.id}" class="text-decoration-none">${reply.user.first_name} ${reply.user.last_name}</a>
                        </div>
                        <div class="reply-date">
                            <i class="fas fa-clock me-1"></i>
                            ${reply.created_at}
                        </div>
                    </div>
                    <div class="reply-content">
                        ${reply.content}
                    </div>
                `;
                
                // Ajouter la réponse à la fin de la liste
                repliesSection.appendChild(replyElement);
                
                // Faire défiler vers la nouvelle réponse
                replyElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Fonction pour mettre à jour le compteur de réponses
            function updateReplyCount() {
                const repliesHeader = document.querySelector('.replies-header');
                const currentCount = document.querySelectorAll('.reply-item').length;
                repliesHeader.innerHTML = `<i class="fas fa-reply me-2"></i> Réponses (${currentCount})`;
            }
        });
    </script>

</body>
</html> 