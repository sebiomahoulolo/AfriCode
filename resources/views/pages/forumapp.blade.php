@extends('apprenants.layouts.app')

@section('title', 'Forum des Apprenants - AfriCode')
@section('page-title', 'Forum des Apprenants')

@push('styles')
<style>
    :root {
        --primary-color: #27B371;
        --secondary-color: #FF8E2A;
        --accent-color: #E32D31;
        --highlight-color: #1EA38B;
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

    .forum-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
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
        background-color: #e67e22;
        border-color: #e67e22;
    }

    .forum-list-container {
        background-color: var(--card-bg);
        border-radius: 8px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .forum-table thead {
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
        border-bottom: 2px solid var(--primary-color);
    }
    .forum-table th { padding: 12px 15px; font-size: 0.9em; text-transform: uppercase; letter-spacing: 0.5px; }
    .forum-table tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: background-color 0.2s ease;
    }
    .forum-table tbody tr:last-child { border-bottom: none; }
    .forum-table tbody tr:hover { background-color: var(--hover-bg); }
    .forum-table td { padding: 15px; vertical-align: middle; }

    .topic-title a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        font-size: 1.05em;
        transition: color 0.2s ease;
    }
    .topic-title a:hover { text-decoration: underline; color: var(--highlight-color); }
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
    .topic-stats strong { color: var(--text-color); font-size: 1.1em; }
    .topic-last-post { font-size: 0.85em; color: #666; line-height: 1.4; }
    .topic-last-post .date { display: block; }

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
        padding-left: 8px; padding-right: 8px;
        margin-left: -8px; margin-right: -8px;
    }
    .leaderboard-widget-rank {
        font-weight: bold;
        font-size: 1.2em;
        min-width: 35px;
        text-align: center;
        margin-right: 12px;
        border-radius: 50%;
        width: 35px; height: 35px;
        display: flex; align-items: center; justify-content: center;
        background-color: var(--light-accent);
    }
    .rank-1 { color: var(--gold-color); background-color: rgba(255,215,0,.1); border: 2px solid var(--gold-color); }
    .rank-2 { color: var(--silver-color); background-color: rgba(192,192,192,.1); border: 2px solid var(--silver-color); }
    .rank-3 { color: var(--bronze-color); background-color: rgba(205,127,50,.1); border: 2px solid var(--bronze-color); }
    .leaderboard-widget-avatar { width: 35px; height: 35px; border-radius: 50%; margin-right: 12px; border: 2px solid var(--border-color); object-fit: cover; }
    .leaderboard-widget-name { font-size: 0.95em; flex-grow: 1; font-weight: 500; color: var(--text-color); }
    .leaderboard-widget-score {
        font-size: 0.9em; color: var(--primary-color); font-weight: 600;
        background-color: rgba(39,179,113,.1);
        padding: 4px 8px; border-radius: 12px;
        border: 1px solid rgba(39,179,113,.2);
    }
    .leaderboard-widget-badges { font-size: 0.8em; margin-top: 2px; }
    .leaderboard-widget-badges i { font-size: 0.9em; }
    .view-full-leaderboard {
        display: block; text-align: center; margin-top: 15px; font-size: 0.9em;
        color: var(--primary-color); text-decoration: none;
        padding: 8px; border-radius: 6px; transition: all 0.3s ease;
    }
    .view-full-leaderboard:hover { background-color: var(--hover-bg); color: var(--highlight-color); }

    #refresh-leaderboard { border-color: var(--primary-color); color: var(--primary-color); padding: 4px 8px; font-size: 0.8em; transition: all 0.3s ease; }
    #refresh-leaderboard:hover { background-color: var(--primary-color); color: white; transform: scale(1.1); }

    .modal-header { background-color: var(--primary-color); color: white; }
    .modal-header .btn-close { filter: brightness(0) invert(1); }

    @media (max-width: 767px) {
        .forum-controls { flex-direction: column; align-items: stretch; }
        .forum-controls .input-group { margin-top: 10px; }
        .forum-table thead { display: none; }
        .forum-table tbody tr { display: block; margin-bottom: 15px; border: 1px solid var(--border-color); border-radius: 5px; }
        .forum-table tbody td { display: block; text-align: left; padding: 10px 15px; border-bottom: 1px dashed #eee; }
        .forum-table tbody td:last-child { border-bottom: none; }
        .forum-table tbody td::before {
            content: attr(data-label); font-weight: bold; display: block;
            margin-bottom: 5px; color: var(--primary-color); font-size: 0.8em; text-transform: uppercase;
        }
        .topic-stats, .topic-last-post { text-align: left; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-2">
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
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sort me-1"></i> Trier par
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item sort-option" href="#" data-sort="recent"><i class="fas fa-clock me-2"></i> Plus récent</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="oldest"><i class="fas fa-history me-2"></i> Plus ancien</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="replies"><i class="fas fa-comments me-2"></i> Plus de réponses</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="views"><i class="fas fa-eye me-2"></i> Plus de vues</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="title"><i class="fas fa-sort-alpha-down me-2"></i> Titre (A-Z)</a></li>
                    </ul>
                </div>
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
                                        Par <a href="{{ route('profile.show', $forum->user->id ?? 0) }}" class="author-link">{{ $forum->user->first_name ?? 'Utilisateur' }} {{ $forum->user->last_name ?? '' }}</a> - {{ $forum->created_at->diffForHumans() }}
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
                                    <a href="{{ route('forum.show', $forum->id) }}" title="Lire et répondre" class="me-2" style="color:#1EA38B;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @php $lastPost = $forum->posts()->latest()->first(); @endphp
                                    @if($lastPost)
                                        Lire <span class="date">{{ $lastPost->created_at->diffForHumans() }}</span>
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

        <!-- Colonne Latérale -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="sidebar-widget">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2" style="color:var(--gold-color);"></i> Top 3 Apprenants</h5>
                    <button class="btn btn-sm" id="refresh-leaderboard" title="Rafraîchir" style="border-color:#1EA38B;color:#1EA38B;">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <ul class="leaderboard-widget-list" id="leaderboard-widget">
                    @php
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
                                    $initials = strtoupper(substr($name, 0, 2));
                                    $colors = ['#27B371', '#FF8E2A', '#E32D31', '#1EA38B', '#9B59B6'];
                                    $color = $colors[array_rand($colors)];
                                    $avatar = "https://via.placeholder.com/35/{$color}/FFFFFF?text=" . urlencode($initials);
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
                        if (empty($leaderboardData)) {
                            $leaderboardData = [
                                ['rank' => 1, 'id' => 5, 'name' => 'Amina D.', 'score' => 1520, 'recentBadges' => ['fa-trophy', 'fa-star']],
                                ['rank' => 2, 'id' => 23, 'name' => 'Kwame N.', 'score' => 1480, 'recentBadges' => ['fa-medal']],
                                ['rank' => 3, 'id' => 12, 'name' => 'Fatou S.', 'score' => 1350, 'recentBadges' => ['fa-award']],
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
                                    return '<i class="fas ' . $icon . ' mx-1" title="Badge Récent" style="color:var(--secondary-color);"></i>';
                                })->join('');
                            }
                        @endphp
                        <li class="leaderboard-widget-item">
                            <span class="leaderboard-widget-rank {{ $rankClass }}">{{ $user['rank'] }}</span>
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
            <button type="submit" class="btn text-white" id="submitTopicBtn" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                <span class="spinner-border spinner-border-sm d-none" id="submitSpinner" role="status" aria-hidden="true"></span>
                <i class="fas fa-paper-plane me-1"></i> Publier le Sujet
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Toast succès sujet -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
  <div id="forumToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">Sujet publié avec succès !</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    function getProfileUrl(userId) {
        return `/profil/${userId}`;
    }

    document.addEventListener('DOMContentLoaded', function() {

        const topicsListBody = document.getElementById('forum-topics-list');
        const leaderboardWidgetList = document.getElementById('leaderboard-widget');
        const searchInput = document.getElementById('forum-search-input');
        const searchBtn = document.getElementById('forum-search-btn');
        const newTopicForm = document.getElementById('newTopicForm');

        function displayLeaderboardWidget(leaderboardData) {
            leaderboardWidgetList.innerHTML = '';
            if (!leaderboardData || leaderboardData.length === 0) {
                leaderboardWidgetList.innerHTML = '<li class="text-center text-muted py-3"><i class="fas fa-info-circle me-2"></i><small>Aucun classement disponible</small></li>';
                return;
            }
            leaderboardData.slice(0, 3).forEach(user => {
                let rankClass = '';
                if (user.rank === 1) rankClass = 'rank-1';
                else if (user.rank === 2) rankClass = 'rank-2';
                else if (user.rank === 3) rankClass = 'rank-3';
                const recentBadgesHTML = (user.recentBadges || []).map(icon =>
                    `<i class="fas ${icon} mx-1" title="Badge Récent" style="color:var(--secondary-color);"></i>`
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

        function loadRealTopics(categoryId = null) {
            let apiUrl = "{{ route('api.forum.topics') }}";
            if (categoryId) apiUrl += `?category=${categoryId}`;
            topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center p-4"><div class="spinner-border" role="status" style="color:#1EA38B;"></div><div class="mt-2">Chargement...</div></td></tr>';
            fetch(apiUrl)
                .then(r => r.json())
                .then(data => {
                    if (data.success) displayRealTopics(data.forums);
                    else topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors du chargement des sujets.</td></tr>';
                })
                .catch(() => { topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors du chargement des sujets.</td></tr>'; });
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
                        <div class="topic-title"><a href="/forum/${forum.id}">${escapeHtml(forum.title)}</a></div>
                        <div class="topic-meta">Par <a href="${getProfileUrl(forum.user.id)}" class="author-link">${escapeHtml(forum.user.first_name)} ${escapeHtml(forum.user.last_name)}</a> - ${forum.created_at}</div>
                    </td>
                    <td data-label="Catégorie" class="text-center topic-category"><span>${escapeHtml(forum.course.title)}</span></td>
                    <td data-label="Statistiques" class="topic-stats">
                        <div><strong>${forum.replies}</strong> Réponses</div>
                        <div><strong>${forum.views}</strong> Vues</div>
                    </td>
                    <td data-label="Dernier Message" class="topic-last-post">
                        <a href="/forum/${forum.id}" title="Lire et répondre" class="me-2" style="color:#1EA38B;"><i class="fas fa-eye"></i></a>
                        ${forum.last_post ? `Par <a href="${getProfileUrl(forum.last_post.user.id)}" class="author-link">${escapeHtml(forum.last_post.user.first_name)} ${escapeHtml(forum.last_post.user.last_name)}</a><span class="date">${forum.last_post.created_at}</span>` : '<span class="text-muted">Aucun message</span>'}
                    </td>
                `;
                topicsListBody.appendChild(tr);
            });
        }

        function handleSearch() {
            const searchTerm = searchInput.value.trim();
            if (!searchTerm) { loadRealTopics(); return; }
            const apiUrl = `{{ route('api.forum.topics') }}?search=${encodeURIComponent(searchTerm)}`;
            topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center p-4"><div class="spinner-border" role="status" style="color:#1EA38B;"></div><div class="mt-2">Recherche en cours...</div></td></tr>';
            fetch(apiUrl)
                .then(r => r.json())
                .then(data => {
                    if (data.success) displayRealTopics(data.forums);
                    else topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors de la recherche.</td></tr>';
                })
                .catch(() => { topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors de la recherche.</td></tr>'; });
        }

        function escapeHtml(unsafe) {
            if (typeof unsafe !== 'string') return '';
            return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function handleSort(sortType) {
            const apiUrl = `{{ route('api.forum.topics') }}?sort=${sortType}`;
            topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center p-4"><div class="spinner-border" role="status" style="color:#1EA38B;"></div><div class="mt-2">Tri en cours...</div></td></tr>';
            fetch(apiUrl)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        displayRealTopics(data.forums);
                        document.querySelectorAll('.sort-option').forEach(o => o.classList.remove('active'));
                        const el = document.querySelector(`[data-sort="${sortType}"]`);
                        if (el) el.classList.add('active');
                    } else {
                        topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors du tri.</td></tr>';
                    }
                })
                .catch(() => { topicsListBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted p-4">Erreur lors du tri.</td></tr>'; });
        }

        if (searchBtn) searchBtn.addEventListener('click', handleSearch);
        if (searchInput) searchInput.addEventListener('keypress', e => { if (e.key === 'Enter') handleSearch(); });

        document.querySelectorAll('.sort-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                handleSort(this.getAttribute('data-sort'));
            });
        });

        const refreshBtn = document.getElementById('refresh-leaderboard');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                this.querySelector('i').classList.add('fa-spin');
                setTimeout(() => window.location.reload(), 500);
            });
        }

        setTimeout(() => loadRealTopics(), 300);

        if (newTopicForm) {
            newTopicForm.addEventListener('submit', function(e) {
                e.preventDefault();
                newTopicForm.querySelectorAll('.text-danger.small').forEach(el => el.textContent = '');
                const formData = new FormData(newTopicForm);
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
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('newTopicModal')).hide();
                        newTopicForm.reset();
                        new bootstrap.Toast(document.getElementById('forumToast')).show();
                        const tbody = document.getElementById('forum-topics-list');
                        if (tbody) {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td data-label="Sujet">
                                    <div class="topic-title"><a href="/forum/${data.forum.id}">${data.forum.title}</a></div>
                                    <div class="topic-meta">Par <a href="${getProfileUrl(data.forum.user.id)}" class="author-link">${data.forum.user.first_name ?? 'Utilisateur'} ${data.forum.user.last_name ?? ''}</a> - ${data.forum.created_at}</div>
                                </td>
                                <td data-label="Catégorie" class="text-center topic-category"><span>${data.forum.course.title}</span></td>
                                <td data-label="Statistiques" class="topic-stats"><div><strong>0</strong> Réponses</div><div><strong>0</strong> Vues</div></td>
                                <td data-label="Dernier Message" class="topic-last-post"><span class="text-muted">Aucun message</span></td>
                            `;
                            tbody.prepend(tr);
                        }
                    } else if (data.errors) {
                        for (const [field, messages] of Object.entries(data.errors)) {
                            const errorDiv = newTopicForm.querySelector(`[name='${field}']`)?.parentElement.querySelector('.text-danger.small');
                            if (errorDiv) errorDiv.textContent = messages[0];
                        }
                    } else {
                        alert(data.message || 'Erreur inconnue.');
                    }
                })
                .catch(() => alert('Erreur lors de la création du sujet.'))
                .finally(() => { submitBtn.disabled = false; spinner.classList.add('d-none'); });
            });
        }

    });
</script>
@endpush
