<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum des Apprenants - AfriCode</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1EA38B; /* Vert émeraude */
            --secondary-color: #FF8E2A; /* Orange */
            --accent-color: #E32D31; /* Rouge */
            --highlight-color: #27B371; /* Vert clair */
            --background-color: #f4f7f6;
            --light-accent: #ECF0F1;
            --text-color: #333333;
            --card-bg: #ffffff;
            --border-color: #e0e0e0;
            --hover-bg: #f0f5f4;
            --gold-color: #FFD700;
            --silver-color: #C0C0C0;
            --bronze-color: #CD7F32;
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
         .page-header h1 { font-size: 1.8em; margin-bottom: 5px; }

        .forum-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Pour mobile */
            margin-bottom: 20px;
            padding: 15px;
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .forum-controls .btn-new-topic {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
            font-weight: 500;
        }
        .forum-controls .btn-new-topic:hover {
            background-color: #e67e22; /* Orange plus foncé */
            border-color: #e67e22;
        }

        /* --- Main Content (Forum List) --- */
        .forum-list-container {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
            overflow: hidden; /* Pour les coins arrondis du tableau */
        }
        .forum-table thead {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
             border-bottom: 2px solid var(--primary-color); /* Ligne plus épaisse */
        }
         .forum-table th { padding: 12px 15px; font-size: 0.9em; text-transform: uppercase; letter-spacing: 0.5px;}
        .forum-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }
        .forum-table tbody tr:last-child { border-bottom: none; }
        .forum-table tbody tr:hover { background-color: var(--hover-bg); }
        .forum-table td { padding: 15px 15px; vertical-align: middle; }

        .topic-title a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            font-size: 1.05em;
            transition: color 0.2s ease;
        }
        .topic-title a:hover { 
            text-decoration: underline; 
            color: var(--highlight-color);
        }
        .topic-meta { font-size: 0.85em; color: #666; margin-top: 3px; }
        .topic-meta .author-link { color: var(--secondary-color); font-weight: 500; text-decoration: none; }
        .topic-meta .author-link:hover { text-decoration: underline; }
        .topic-category span {
            background-color: var(--light-accent);
            color: #555;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 500;
            border: 1px solid #ccc;
        }

        .topic-stats { text-align: center; font-size: 0.9em; color: #555; }
        .topic-stats div { line-height: 1.3; }
        .topic-stats strong { color: var(--text-color); font-size: 1.1em;}

        .topic-last-post { font-size: 0.85em; color: #666; line-height: 1.4; }
        .topic-last-post .date { display: block; }

        /* --- Sidebar --- */
        .sidebar-widget {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .sidebar-widget h5 {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-color);
        }

        /* Competition Highlights Widget */
        .leaderboard-widget-list { list-style: none; padding: 0; margin: 0; }
        .leaderboard-widget-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border-color);
            transition: all 0.3s ease;
        }
        .leaderboard-widget-item:last-child { border-bottom: none; }
        .leaderboard-widget-item:hover {
            background-color: var(--hover-bg);
            border-radius: 8px;
            padding-left: 8px;
            padding-right: 8px;
            margin-left: -8px;
            margin-right: -8px;
        }
        .leaderboard-widget-rank {
            font-weight: bold;
            font-size: 1.2em;
            min-width: 35px;
            text-align: center;
            margin-right: 12px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--light-accent);
        }
        .rank-1 { 
            color: var(--gold-color); 
            background-color: rgba(255, 215, 0, 0.1);
            border: 2px solid var(--gold-color);
        }
        .rank-2 { 
            color: var(--silver-color); 
            background-color: rgba(192, 192, 192, 0.1);
            border: 2px solid var(--silver-color);
        }
        .rank-3 { 
            color: var(--bronze-color); 
            background-color: rgba(205, 127, 50, 0.1);
            border: 2px solid var(--bronze-color);
        }
        .leaderboard-widget-avatar { 
            width: 35px; 
            height: 35px; 
            border-radius: 50%; 
            margin-right: 12px;
            border: 2px solid var(--border-color);
            object-fit: cover;
        }
        .leaderboard-widget-name { 
            font-size: 0.95em; 
            flex-grow: 1; 
            font-weight: 500;
            color: var(--text-color);
        }
        .leaderboard-widget-score { 
            font-size: 0.9em; 
            color: var(--primary-color); 
            font-weight: 600;
            background-color: rgba(30, 163, 139, 0.1);
            padding: 4px 8px;
            border-radius: 12px;
            border: 1px solid rgba(30, 163, 139, 0.2);
        }
        .leaderboard-widget-badges {
            font-size: 0.8em;
            margin-top: 2px;
        }
        .leaderboard-widget-badges i {
            font-size: 0.9em;
        }
        .view-full-leaderboard { 
            display: block; 
            text-align: center; 
            margin-top: 15px; 
            font-size: 0.9em;
            color: var(--primary-color);
            text-decoration: none;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .view-full-leaderboard:hover {
            background-color: var(--hover-bg);
            color: var(--highlight-color);
            text-decoration: none;
        }

        /* Bouton de rafraîchissement */
        #refresh-leaderboard {
            border-color: var(--primary-color);
            color: var(--primary-color);
            padding: 4px 8px;
            font-size: 0.8em;
            transition: all 0.3s ease;
        }
        #refresh-leaderboard:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }
        #refresh-leaderboard .fa-spin {
            animation-duration: 1s;
        }

        /* Categories Widget */
        .category-list { list-style: none; padding: 0; margin: 0; }
        .category-list a {
            display: block;
            padding: 12px 15px;
            margin-bottom: 3px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.95em;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            cursor: pointer;
        }
        .category-list a:hover { 
            background-color: var(--hover-bg); 
            border-color: var(--primary-color);
            transform: translateX(5px);
        }
        .category-list a.active { 
            background-color: var(--primary-color); 
            color: white; 
            font-weight: 600;
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(30, 163, 139, 0.3);
        }
        .category-list a.active:hover {
            background-color: var(--highlight-color);
            border-color: var(--highlight-color);
        }
        .category-list i { 
            margin-right: 10px; 
            color: var(--primary-color);
            width: 16px;
            text-align: center;
        }
        .category-list a.active i {
            color: white;
        }

        /* Modal */
         .modal-header {
             background-color: var(--primary-color);
             color: white;
         }
         .modal-header .btn-close { filter: brightness(0) invert(1); }


        @media (max-width: 767px) {
             .forum-controls { flex-direction: column; align-items: stretch; }
             .forum-controls .input-group { margin-top: 10px; }
             .forum-table thead { display: none; } /* Cacher l'en-tête sur petit écran */
             .forum-table tbody tr { display: block; margin-bottom: 15px; border: 1px solid var(--border-color); border-radius: 5px; }
             .forum-table tbody td { display: block; text-align: left; padding: 10px 15px; border-bottom: 1px dashed #eee; }
             .forum-table tbody td:last-child { border-bottom: none; }
             .forum-table tbody td::before { /* Ajouter des labels pour mobile */
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
                 color: var(--primary-color);
                 font-size: 0.8em;
                 text-transform: uppercase;
             }
             .topic-stats, .topic-last-post { text-align: left; } /* Ajuster alignement */
        }
    </style>
</head>
<body>

    <header class="page-header">
        <div class="container">
            <h1><i class="fas fa-users me-2"></i> Forum des Apprenants</h1>
            <p class="lead mb-0">Échangez, posez vos questions et partagez vos connaissances !</p>
        </div>
    </header>

    <div class="container mt-4">
        <div class="row">

            <!-- Colonne Principale : Liste des Sujets -->
            <div class="col-lg-8">
                <div class="forum-controls">
                    <button class="btn btn-sm btn-new-topic" data-bs-toggle="modal" data-bs-target="#newTopicModal">
                        <i class="fas fa-plus me-1"></i> Créer un Sujet
                    </button>
                    <div class="input-group input-group-sm" style="max-width: 300px;">
                        <input type="text" class="form-control" placeholder="Rechercher un sujet..." id="forum-search-input">
                        <button class="btn btn-outline-secondary" type="button" id="forum-search-btn"><i class="fas fa-search"></i></button>
                    </div>
                     <!-- TODO: Ajouter Dropdown pour Tri -->
                </div>

                <div class="forum-list-container">
                    <table class="table forum-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Sujet</th>
                                <th scope="col" class="text-center">Catégorie</th>
                                <th scope="col" class="text-center">Statistiques</th>
                                <th scope="col">Dernier Message</th>
                            </tr>
                        </thead>
                        <tbody id="forum-topics-list">
                            @forelse($forums as $forum)
                                <tr>
                                    <td data-label="Sujet">
                                        <div class="topic-title">
                                            <a href="{{ route('forum.show', $forum->id) }}">{{ $forum->title }}</a>
                                        </div>
                                        <div class="topic-meta">
                                            Par <a href="/profil/{{ $forum->user->id ?? '' }}" class="author-link">{{ $forum->user->first_name ?? 'Utilisateur' }} {{ $forum->user->last_name ?? '' }}</a> - {{ $forum->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td data-label="Catégorie" class="text-center topic-category">
                                        <span>{{ $forum->course->title ?? 'Général' }}</span>
                                    </td>
                                    <td data-label="Statistiques" class="topic-stats">
                                        <div><strong>{{ $forum->getTotalRepliesCount() }}</strong> Réponses</div>
                                        <div><strong>{{ $forum->views ?? 0 }}</strong> Vues</div>
                                    </td>
                                    <td data-label="Dernier Message" class="topic-last-post">
                                        <a href="{{ route('forum.show', $forum->id) }}" title="Lire et répondre" class="me-2 text-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @php
                                            $lastPost = $forum->posts()->latest()->first();
                                        @endphp
                                        @if($lastPost)
                                            Par <a href="/profil/{{ $lastPost->user->id ?? '' }}" class="author-link">{{ $lastPost->user->first_name ?? 'Utilisateur' }} {{ $lastPost->user->last_name ?? '' }}</a>
                                            <span class="date">{{ $lastPost->created_at->diffForHumans() }}</span>
                                        @else
                                            <span class="text-muted">Aucun message</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-4">Aucun sujet trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $forums->links() }}
                </div>
            </div>

            <!-- Colonne Latérale : Compétition et Catégories -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <!-- Widget Compétition -->
                <div class="sidebar-widget">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-trophy me-2" style="color: var(--gold-color);"></i> Top 3 Apprenants</h5>
                        <button class="btn btn-sm btn-outline-primary" id="refresh-leaderboard" title="Rafraîchir">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <ul class="leaderboard-widget-list" id="leaderboard-widget">
                        @php
                            // Récupérer les données du leaderboard comme dans CompetitionDisplayController
                            $globalLeaderboard = \App\Models\Leaderboard::where('type', 'global')->first();
                            $leaderboardData = [];
                            
                            if ($globalLeaderboard) {
                                $leaderboardData = \App\Models\UserScore::with('user')
                                    ->where('leaderboard_id', $globalLeaderboard->id)
                                    ->orderBy('score', 'desc')
                                    ->take(3)
                                    ->get()
                                    ->map(function ($score, $index) {
                                        $user = $score->user;
                                        $name = $user->first_name . ' ' . $user->last_name;
                                        
                                        // Générer l'avatar
                                        $initials = strtoupper(substr($name, 0, 2));
                                        $colors = ['#1EA38B', '#FF8E2A', '#E32D31', '#27B371', '#9B59B6'];
                                        $color = $colors[array_rand($colors)];
                                        $avatar = "https://via.placeholder.com/35/{$color}/FFFFFF?text=" . urlencode($initials);
                                        
                                        // Récupérer les badges récents
                                        $recentBadges = \Illuminate\Support\Facades\DB::table('badge_user')
                                            ->join('badges', 'badge_user.badge_id', '=', 'badges.id')
                                            ->where('badge_user.user_id', $user->id)
                                            ->orderBy('badge_user.awarded_at', 'desc')
                                            ->limit(3)
                                            ->pluck('badges.icon')
                                            ->toArray();

                                        return [
                                            'rank' => $index + 1,
                                            'id' => $user->id,
                                            'name' => $name,
                                            'avatar' => $avatar,
                                            'score' => $score->score,
                                            'recentBadges' => $recentBadges
                                        ];
                                    });
                            }

                            // Si pas de données, utiliser des données de test
                            if (empty($leaderboardData)) {
                                $leaderboardData = [
                                    [
                                        'rank' => 1, 
                                        'id' => 5, 
                                        'name' => 'Amina D.', 
                                        'avatar' => 'https://via.placeholder.com/35/FF8E2A/FFFFFF?text=AD', 
                                        'score' => 1520,
                                        'recentBadges' => ['fa-trophy', 'fa-star']
                                    ],
                                    [
                                        'rank' => 2, 
                                        'id' => 23, 
                                        'name' => 'Kwame N.', 
                                        'avatar' => 'https://via.placeholder.com/35/E32D31/FFFFFF?text=KN', 
                                        'score' => 1480,
                                        'recentBadges' => ['fa-medal']
                                    ],
                                    [
                                        'rank' => 3, 
                                        'id' => 12, 
                                        'name' => 'Fatou S.', 
                                        'avatar' => 'https://via.placeholder.com/35/27B371/FFFFFF?text=FS', 
                                        'score' => 1350,
                                        'recentBadges' => ['fa-award']
                                    ],
                                ];
                            }
                        @endphp

                        @foreach($leaderboardData as $user)
                            @php
                                $rankClass = '';
                                if ($user['rank'] === 1) $rankClass = 'rank-1';
                                elseif ($user['rank'] === 2) $rankClass = 'rank-2';
                                elseif ($user['rank'] === 3) $rankClass = 'rank-3';
                                
                                $recentBadgesHTML = '';
                                if (!empty($user['recentBadges'])) {
                                    $recentBadgesHTML = collect($user['recentBadges'])->map(function($icon) {
                                        return '<i class="fas ' . $icon . ' mx-1" title="Badge Récent" style="color: var(--secondary-color);"></i>';
                                    })->join('');
                                }
                            @endphp
                            <li class="leaderboard-widget-item">
                                <span class="leaderboard-widget-rank {{ $rankClass }}">{{ $user['rank'] }}</span>
                                <img src="{{ $user['avatar'] }}" alt="Avatar" class="leaderboard-widget-avatar" onerror="this.src='https://via.placeholder.com/35/cccccc/FFFFFF?text=U'">
                                <div class="flex-grow-1">
                                    <div class="leaderboard-widget-name">{{ $user['name'] }}</div>
                                    <div class="leaderboard-widget-badges">
                                        {!! $recentBadgesHTML ?: '<small class="text-muted">Aucun badge</small>' !!}
                                    </div>
                                </div>
                                <span class="leaderboard-widget-score">{{ number_format($user['score']) }} pts</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('pages.compdisp') }}" class="view-full-leaderboard">Voir le classement complet <i class="fas fa-arrow-right ms-1"></i></a>
                </div>

                <!-- Widget Catégories -->
                <div class="sidebar-widget">
                    <h5><i class="fas fa-tags me-2"></i> Catégories</h5>
                    <div class="list-group list-group-flush category-list" id="category-list">
                        <a href="#" class="list-group-item list-group-item-action active" data-category="all">
                            <i class="fas fa-globe-africa me-2"></i> Toutes les discussions
                        </a>
                        @foreach(App\Models\Course::all() as $course)
                            @php
                                // Déterminer l'icône selon le titre du cours
                                $icon = 'fa-book';
                                $title = strtolower($course->title);
                                if (str_contains($title, 'html')) $icon = 'fab fa-html5';
                                elseif (str_contains($title, 'css')) $icon = 'fab fa-css3-alt';
                                elseif (str_contains($title, 'javascript') || str_contains($title, 'js')) $icon = 'fab fa-js-square';
                                elseif (str_contains($title, 'php')) $icon = 'fab fa-php';
                                elseif (str_contains($title, 'laravel')) $icon = 'fab fa-laravel';
                                elseif (str_contains($title, 'react')) $icon = 'fab fa-react';
                                elseif (str_contains($title, 'vue')) $icon = 'fab fa-vuejs';
                                elseif (str_contains($title, 'node')) $icon = 'fab fa-node-js';
                                elseif (str_contains($title, 'python')) $icon = 'fab fa-python';
                                elseif (str_contains($title, 'java')) $icon = 'fab fa-java';
                                elseif (str_contains($title, 'git')) $icon = 'fab fa-git-alt';
                                elseif (str_contains($title, 'docker')) $icon = 'fab fa-docker';
                                elseif (str_contains($title, 'aws')) $icon = 'fab fa-aws';
                                elseif (str_contains($title, 'database') || str_contains($title, 'sql')) $icon = 'fas fa-database';
                                elseif (str_contains($title, 'api')) $icon = 'fas fa-code';
                                elseif (str_contains($title, 'mobile')) $icon = 'fas fa-mobile-alt';
                                elseif (str_contains($title, 'web')) $icon = 'fas fa-globe';
                                elseif (str_contains($title, 'design')) $icon = 'fas fa-palette';
                                elseif (str_contains($title, 'projet')) $icon = 'fas fa-lightbulb';
                                elseif (str_contains($title, 'général') || str_contains($title, 'general')) $icon = 'fas fa-comments';
                            @endphp
                            <a href="#" class="list-group-item list-group-item-action category-filter-link" data-category="{{ $course->id }}">
                                <i class="{{ $icon }} me-2"></i> {{ $course->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Nouveau Sujet -->
    <div class="modal fade" id="newTopicModal" tabindex="-1" aria-labelledby="newTopicModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newTopicModalLabel"><i class="fas fa-plus-circle me-2"></i> Créer un Nouveau Sujet</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="newTopicForm" method="POST" action="{{ route('forum.store') }}">
              @csrf
              <div class="mb-3">
                <label for="course_id" class="form-label">Cours concerné <span class="text-danger">*</span></label>
                <select name="course_id" id="course_id" class="form-select" required>
                  <option value="">-- Sélectionner un cours --</option>
                  @foreach(App\Models\Course::all() as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                  @endforeach
                </select>
                @error('course_id')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label for="title" class="form-label">Titre du Sujet <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" required maxlength="255" value="{{ old('title') }}">
                @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label for="topicContent" class="form-label">Votre Message <span class="text-danger">*</span></label>
                <textarea name="content" id="topicContent" class="form-control" rows="6" required placeholder="Décrivez votre question ou sujet de discussion...">{{ old('content') }}</textarea>
                @error('content')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary" id="submitTopicBtn" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                    <span class="spinner-border spinner-border-sm d-none" id="submitSpinner" role="status" aria-hidden="true"></span>
                    <i class="fas fa-paper-plane me-1"></i> Publier le Sujet
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast de succès -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
      <div id="forumToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">
            Sujet publié avec succès !
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
        </div>
      </div>
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

            // --- Sample Data (Replace with API calls) ---
            const sampleTopics = [
                { id: 't1', title: "Comment utiliser Flexbox pour un layout complexe ?", author: 'Amina D.', authorId: 'u5', category: 'CSS', replies: 15, views: 102, lastPostAuthor: 'Expert A.', lastPostDate: 'Il y a 15min' },
                { id: 't2', title: "Erreur 'undefined' avec une variable JavaScript", author: 'Kwame N.', authorId: 'u23', category: 'JavaScript', replies: 8, views: 75, lastPostAuthor: 'Fatou S.', lastPostDate: 'Il y a 1h' },
                { id: 't3', title: "Installation de Laravel sur Ubuntu - Problème de permission", author: 'David O.', authorId: 'u8', category: 'Laravel', replies: 3, views: 45, lastPostAuthor: 'David O.', lastPostDate: 'Hier' },
                 { id: 't4', title: "Partage de projet : Application météo simple", author: 'Fatou S.', authorId: 'u12', category: 'Projets', replies: 22, views: 150, lastPostAuthor: 'Amina D.', lastPostDate: 'Aujourd\'hui 09:30' },
                 { id: 't5', title: "Questions générales sur le parcours Full-Stack", author: 'NouveauDev', authorId: 'u40', category: 'Général', replies: 0, views: 10, lastPostAuthor: 'NouveauDev', lastPostDate: 'Il y a 2h' },
            ];

            const sampleLeaderboard = [
                { rank: 1, id: 5, name: "Amina D.", avatar: 'https://via.placeholder.com/30/FF8E2A/FFFFFF?text=AD', score: 1520 },
                { rank: 2, id: 23, name: "Kwame N.", avatar: 'https://via.placeholder.com/30/E32D31/FFFFFF?text=KN', score: 1480 },
                { rank: 3, id: 12, name: "Fatou S.", avatar: 'https://via.placeholder.com/30/27B371/FFFFFF?text=FS', score: 1350 },
                 { rank: 4, id: 8, name: "David O.", avatar: 'https://via.placeholder.com/30/1EA38B/FFFFFF?text=DO', score: 1200 },
                 { rank: 5, id: 35, name: "Sarah K.", avatar: 'https://via.placeholder.com/30/cccccc/FFFFFF?text=SK', score: 1150 },
            ];

            const sampleCategories = [
                { id: 'html', name: 'HTML', icon: 'fa-html5' },
                { id: 'css', name: 'CSS', icon: 'fa-css3-alt' },
                { id: 'javascript', name: 'JavaScript', icon: 'fa-js-square' },
                { id: 'php', name: 'PHP & MySQL', icon: 'fa-php' },
                { id: 'laravel', name: 'Laravel', icon: 'fa-laravel' }, // Utiliser fab pour marques
                { id: 'react', name: 'ReactJS', icon: 'fa-react' },
                { id: 'projets', name: 'Showcase Projets', icon: 'fa-lightbulb' },
                { id: 'general', name: 'Discussion Générale', icon: 'fa-comments' },
            ];

            // --- DOM Elements ---
            const topicsListBody = document.getElementById('forum-topics-list');
            const leaderboardWidgetList = document.getElementById('leaderboard-widget');
            const categoryListContainer = document.getElementById('category-list');
            const newTopicCategorySelect = document.getElementById('topicCategory');
            const searchInput = document.getElementById('forum-search-input');
            const searchBtn = document.getElementById('forum-search-btn');
            const newTopicForm = document.getElementById('newTopicForm');

            // --- Functions ---
            function loadTopics(topics = sampleTopics) {
                topicsListBody.innerHTML = ''; // Clear placeholders/old data
                if (topics.length === 0) {
                    topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Aucun sujet trouvé.</td></tr>';
                    return;
                }
                topics.forEach(topic => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td data-label="Sujet">
                            <div class="topic-title">
                                <a href="/forum/${topic.id}">${escapeHtml(topic.title)}</a>
                            </div>
                            <div class="topic-meta">
                                Par <a href="/profil/${topic.authorId}" class="author-link">${escapeHtml(topic.author)}</a> - ${topic.lastPostDate} <!-- Date de création ? -->
                            </div>
                        </td>
                        <td data-label="Catégorie" class="text-center topic-category"><span>${escapeHtml(topic.category)}</span></td>
                        <td data-label="Statistiques" class="topic-stats">
                            <div><strong>${topic.replies}</strong> Réponses</div>
                            <div><strong>${topic.views}</strong> Vues</div>
                        </td>
                        <td data-label="Dernier Message" class="topic-last-post">
                            <a href="/forum/${topic.id}" title="Lire et répondre" class="me-2 text-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            Par <a href="#" class="author-link">${escapeHtml(topic.lastPostAuthor)}</a>
                            <span class="date">${topic.lastPostDate}</span>
                        </td>
                    `;
                    topicsListBody.appendChild(tr);
                });
            }

            function loadLeaderboardWidget() {
                // Afficher un indicateur de chargement
                leaderboardWidgetList.innerHTML = `
                    <li class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <small class="text-muted">Chargement du classement...</small>
                    </li>
                `;

                // Charger les vraies données du leaderboard via API
                fetch("{{ route('api.forum.leaderboard') }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayLeaderboardWidget(data.leaderboard);
                        } else {
                            console.error('Erreur API leaderboard:', data.error);
                            loadSampleLeaderboard();
                        }
                    })
                    .catch(error => {
                        console.error('Erreur chargement leaderboard:', error);
                        loadSampleLeaderboard();
                    });
            }

            function displayLeaderboardWidget(leaderboardData) {
                leaderboardWidgetList.innerHTML = ''; // Clear placeholders
                
                if (!leaderboardData || leaderboardData.length === 0) {
                    leaderboardWidgetList.innerHTML = `
                        <li class="text-center text-muted py-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>Aucun classement disponible</small>
                        </li>
                    `;
                    return;
                }

                // Afficher seulement les 3 premiers
                leaderboardData.slice(0, 3).forEach(user => {
                    let rankClass = '';
                    if (user.rank === 1) rankClass = 'rank-1';
                    else if (user.rank === 2) rankClass = 'rank-2';
                    else if (user.rank === 3) rankClass = 'rank-3';

                    // Générer les badges récents
                    const recentBadgesHTML = (user.recentBadges || []).map(icon => 
                        `<i class="fas ${icon} mx-1" title="Badge Récent" style="color: var(--secondary-color);"></i>`
                    ).join('');

                    const li = document.createElement('li');
                    li.className = 'leaderboard-widget-item';
                    li.innerHTML = `
                        <span class="leaderboard-widget-rank ${rankClass}">${user.rank}</span>
                        <img src="${user.avatar}" alt="Avatar" class="leaderboard-widget-avatar" onerror="this.src='https://via.placeholder.com/35/cccccc/FFFFFF?text=U'">
                        <div class="flex-grow-1">
                            <div class="leaderboard-widget-name">${escapeHtml(user.name)}</div>
                            <div class="leaderboard-widget-badges">${recentBadgesHTML || '<small class="text-muted">Aucun badge</small>'}</div>
                        </div>
                        <span class="leaderboard-widget-score">${user.score.toLocaleString()} pts</span>
                    `;
                    leaderboardWidgetList.appendChild(li);
                });
            }

            function loadSampleLeaderboard() {
                // Fallback avec des données de test (3 premiers seulement)
                const sampleLeaderboard = [
                    { 
                        rank: 1, 
                        id: 5, 
                        name: "Amina D.", 
                        avatar: 'https://via.placeholder.com/35/FF8E2A/FFFFFF?text=AD', 
                        score: 1520,
                        recentBadges: ['fa-trophy', 'fa-star']
                    },
                    { 
                        rank: 2, 
                        id: 23, 
                        name: "Kwame N.", 
                        avatar: 'https://via.placeholder.com/35/E32D31/FFFFFF?text=KN', 
                        score: 1480,
                        recentBadges: ['fa-medal']
                    },
                    { 
                        rank: 3, 
                        id: 12, 
                        name: "Fatou S.", 
                        avatar: 'https://via.placeholder.com/35/27B371/FFFFFF?text=FS', 
                        score: 1350,
                        recentBadges: ['fa-award']
                    },
                ];
                displayLeaderboardWidget(sampleLeaderboard);
            }

            function loadCategories() {
                // Les catégories sont maintenant chargées directement depuis Blade
                // Ajouter les event listeners pour le filtrage
                const categoryLinks = document.querySelectorAll('.category-filter-link');
                categoryLinks.forEach(link => {
                    link.addEventListener('click', handleCategoryFilter);
                });
                
                // Event listener pour "Toutes les discussions"
                const allDiscussionsLink = document.querySelector('[data-category="all"]');
                if (allDiscussionsLink) {
                    allDiscussionsLink.addEventListener('click', handleCategoryFilter);
                }
            }

            function handleCategoryFilter(event) {
                event.preventDefault();
                const selectedCategory = event.currentTarget.dataset.category;

                console.log("Clic sur catégorie:", selectedCategory);
                console.log("Élément cliqué:", event.currentTarget);

                // Update active state visually
                document.querySelectorAll('.category-filter-link, [data-category="all"]').forEach(link => {
                    link.classList.remove('active');
                });
                event.currentTarget.classList.add('active');

                // Afficher un message temporaire
                const categoryName = event.currentTarget.textContent.trim();
                console.log(`Filtrage par catégorie: ${categoryName} (ID: ${selectedCategory})`);
                
                // Filtrage avec les vraies données
                if (selectedCategory === 'all') {
                    console.log("Chargement de tous les sujets...");
                    loadRealTopics();
                } else {
                    console.log(`Filtrage par catégorie ID: ${selectedCategory}`);
                    loadRealTopics(selectedCategory);
                }
            }

            function loadRealTopics(categoryId = null) {
                // Construire l'URL de l'API
                let apiUrl = "{{ route('api.forum.topics') }}";
                if (categoryId) {
                    apiUrl += `?category=${categoryId}`;
                }

                // Mettre à jour le titre de la page pour indiquer le filtrage
                const pageTitle = document.querySelector('.page-header h1');
                if (categoryId && categoryId !== 'all') {
                    const activeCategory = document.querySelector(`[data-category="${categoryId}"]`);
                    if (activeCategory) {
                        const categoryName = activeCategory.textContent.trim();
                        pageTitle.innerHTML = `<i class="fas fa-comments me-2"></i> Forum - ${categoryName}`;
                    }
                } else {
                    pageTitle.innerHTML = '<i class="fas fa-comments me-2"></i> Forum des Apprenants';
                }

                // Afficher un indicateur de chargement
                topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Chargement...</div></td></tr>';

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayRealTopics(data.forums);
                        } else {
                            topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors du chargement des sujets.</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors du chargement des sujets.</td></tr>';
                    });
            }

            function displayRealTopics(forums) {
                topicsListBody.innerHTML = '';
                
                if (forums.length === 0) {
                    topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Aucun sujet trouvé dans cette catégorie.</td></tr>';
                    return;
                }

                forums.forEach(forum => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td data-label="Sujet">
                            <div class="topic-title">
                                <a href="/forum/${forum.id}">${escapeHtml(forum.title)}</a>
                            </div>
                            <div class="topic-meta">
                                Par <a href="/profil/${forum.user.id}" class="author-link">${escapeHtml(forum.user.first_name)} ${escapeHtml(forum.user.last_name)}</a> - ${forum.created_at}
                            </div>
                        </td>
                        <td data-label="Catégorie" class="text-center topic-category">
                            <span>${escapeHtml(forum.course.title)}</span>
                        </td>
                        <td data-label="Statistiques" class="topic-stats">
                            <div><strong>${forum.replies}</strong> Réponses</div>
                            <div><strong>${forum.views}</strong> Vues</div>
                        </td>
                        <td data-label="Dernier Message" class="topic-last-post">
                            <a href="/forum/${forum.id}" title="Lire et répondre" class="me-2 text-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            ${forum.last_post ? `
                                Par <a href="/profil/${forum.last_post.user.id}" class="author-link">${escapeHtml(forum.last_post.user.first_name)} ${escapeHtml(forum.last_post.user.last_name)}</a>
                                <span class="date">${forum.last_post.created_at}</span>
                            ` : '<span class="text-muted">Aucun message</span>'}
                        </td>
                    `;
                    topicsListBody.appendChild(tr);
                });
            }

            function handleSearch() {
                const searchTerm = searchInput.value.trim();
                console.log("Search for:", searchTerm);
                
                if (!searchTerm) {
                    // Si la recherche est vide, recharger tous les sujets
                    loadRealTopics();
                    return;
                }

                // Construire l'URL de l'API avec le terme de recherche
                const apiUrl = `{{ route('api.forum.topics') }}?search=${encodeURIComponent(searchTerm)}`;

                // Afficher un indicateur de chargement
                topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Recherche en cours...</div></td></tr>';

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayRealTopics(data.forums);
                            // Mettre à jour l'état actif des catégories
                            document.querySelectorAll('.category-filter-link, [data-category="all"]').forEach(link => {
                                link.classList.remove('active');
                            });
                            document.querySelector('[data-category="all"]').classList.add('active');
                        } else {
                            topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors de la recherche.</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors de la recherche.</td></tr>';
                    });
            }

            function handleNewTopicSubmit(event) {
                event.preventDefault();
                const title = document.getElementById('topicTitle').value.trim();
                const categoryId = document.getElementById('topicCategory').value;
                const content = document.getElementById('topicContent').value.trim();

                if (title && categoryId && content) {
                    // TODO: Send data to API: POST /api/forum/topics
                    console.log("New Topic Data:", { title, categoryId, content });
                    alert("Sujet publié (simulation) !");
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('newTopicModal'));
                    modal.hide();
                    // Reset form (optional)
                    newTopicForm.reset();
                    // Refresh topic list (simulation)
                    // In a real app, the API response might return the new topic, or you'd refetch
                    loadTopics();
                } else {
                    alert("Veuillez remplir tous les champs requis.");
                }
            }

            function escapeHtml(unsafe) {
                 if (typeof unsafe !== 'string') return '';
                return unsafe
                     .replace(/&/g, "&")
                     .replace(/</g, "<")
                     .replace(/>/g, ">")
                     .replace(/"/g, """)
                     .replace(/'/g, "'");
             }

            // --- Event Listeners ---
            searchBtn.addEventListener('click', handleSearch);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    handleSearch();
                }
            });
             newTopicForm.addEventListener('submit', handleNewTopicSubmit);

            // Event listener pour rafraîchir le leaderboard
            const refreshLeaderboardBtn = document.getElementById('refresh-leaderboard');
            if (refreshLeaderboardBtn) {
                refreshLeaderboardBtn.addEventListener('click', function() {
                    // Ajouter une animation de rotation
                    const icon = this.querySelector('i');
                    icon.classList.add('fa-spin');
                    
                    // Recharger la page pour avoir les données les plus récentes
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                });
            }

            // --- Initial Load ---
            setTimeout(() => { // Simulate loading delay
                loadRealTopics(); // Charger les vraies données au lieu des données de test
                loadCategories();
            }, 500);

            // Soumission AJAX du formulaire de création de sujet
            if (newTopicForm) {
                newTopicForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Nettoyer les erreurs
                    newTopicForm.querySelectorAll('.text-danger.small').forEach(el => el.textContent = '');
                    // Récupérer le contenu du textarea
                    const content = document.getElementById('topicContent').value;
                    const formData = new FormData(newTopicForm);
                    formData.set('content', content);
                    // Désactiver bouton + spinner
                    const submitBtn = document.getElementById('submitTopicBtn');
                    const spinner = document.getElementById('submitSpinner');
                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');
                    fetch("{{ route('forum.ajaxStore') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Fermer le modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('newTopicModal'));
                            modal.hide();
                            // Réinitialiser le formulaire
                            newTopicForm.reset();
                            // Afficher le toast
                            const toast = new bootstrap.Toast(document.getElementById('forumToast'));
                            toast.show();
                            // Ajouter le sujet en haut de la liste
                            const tbody = document.getElementById('forum-topics-list');
                            if (tbody) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td data-label="Sujet">
                                        <div class="topic-title">
                                            <a href="/forum/${data.forum.id}">${data.forum.title}</a>
                                        </div>
                                        <div class="topic-meta">
                                            Par <a href="/profil/${data.forum.user.id}" class="author-link">${data.forum.user.first_name ?? 'Utilisateur'} ${data.forum.user.last_name ?? ''}</a> - ${data.forum.created_at}
                                        </div>
                                    </td>
                                    <td data-label="Catégorie" class="text-center topic-category">
                                        <span>${data.forum.course.title}</span>
                                    </td>
                                    <td data-label="Statistiques" class="topic-stats">
                                        <div><strong>0</strong> Réponses</div>
                                        <div><strong>0</strong> Vues</div>
                                    </td>
                                    <td data-label="Dernier Message" class="topic-last-post">
                                        <span class="text-muted">Aucun message</span>
                                    </td>
                                `;
                                tbody.prepend(tr);
                            }
                        } else if (data.errors) {
                            // Afficher les erreurs sous chaque champ
                            for (const [field, messages] of Object.entries(data.errors)) {
                                const errorDiv = newTopicForm.querySelector(`[name='${field}']`)?.parentElement.querySelector('.text-danger.small');
                                if (errorDiv) errorDiv.textContent = messages[0];
                            }
                        } else {
                            alert(data.message || 'Erreur inconnue.');
                        }
                    })
                    .catch(() => {
                        alert('Erreur lors de la création du sujet.');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    });
                });
            }

        });
    </script>

</body>
</html>