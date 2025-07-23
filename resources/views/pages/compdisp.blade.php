@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')

<style>
    :root {
        --primary-color: #1EA38B; /* Vert émeraude */
        --secondary-color: #FF8E2A; /* Orange */
        --accent-color: #E32D31; /* Rouge */
        --highlight-color: #27B371; /* Vert clair */
        --background-color: #f4f7f6; /* Fond légèrement différent */
        --light-accent: #ECF0F1;
        --text-color: #333333;
        --card-bg: #ffffff;
        --gold-color: #FFD700;
        --silver-color: #C0C0C0;
        --bronze-color: #CD7F32;
    }

    body {
        background-color: var(--background-color);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-color);
    }

    .competition-header {
        background: linear-gradient(135deg, var(--primary-color), var(--highlight-color));
        color: white;
        padding: 40px 20px;
        margin-bottom: 30px;
        text-align: center;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .competition-header h1 {
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }
    .competition-header p {
        font-size: 1.1em;
        opacity: 0.9;
    }

    .section-title {
        font-size: 1.8em;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--secondary-color);
        display: inline-block;
    }

    .challenge-card {
        background-color: var(--card-bg);
        border: none;
        border-left: 5px solid var(--secondary-color);
        border-radius: 8px;
        margin-bottom: 20px;
        padding: 20px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .challenge-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    }
    .challenge-card .difficulty {
        font-size: 0.9em;
        font-weight: 500;
        padding: 3px 8px;
        border-radius: 15px;
        color: white;
    }
    .difficulty-debutant { background-color: var(--highlight-color); }
    .difficulty-intermediaire { background-color: var(--secondary-color); }
    .difficulty-avance { background-color: var(--accent-color); }

    .leaderboard-table {
        background-color: var(--card-bg);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }
    .leaderboard-table thead {
        background-color: var(--primary-color);
        color: white;
    }
    .leaderboard-table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }
    .leaderboard-table tbody tr:hover {
        background-color: var(--light-accent);
    }
    .leaderboard-table td, .leaderboard-table th {
        vertical-align: middle;
        padding: 12px 15px;
    }
    .leaderboard-rank {
        font-weight: bold;
        font-size: 1.1em;
        min-width: 40px;
        text-align: center;
    }
    .rank-1 { color: var(--gold-color); }
    .rank-2 { color: var(--silver-color); }
    .rank-3 { color: var(--bronze-color); }

    .leaderboard-user img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }
    .leaderboard-score {
        font-weight: 600;
        color: var(--primary-color);
    }

    .current-user-rank {
        background-color: rgba(255, 142, 42, 0.15) !important;
        border-top: 2px solid var(--secondary-color);
        border-bottom: 2px solid var(--secondary-color);
        font-weight: bold;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--highlight-color));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        margin-right: 10px;
        float: left;
    }

    .badge-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 20px;
        text-align: center;
    }
    .badge-item {
        background-color: var(--card-bg);
        padding: 20px 15px;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .badge-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }
    .badge-item.unlocked {
        border-color: var(--highlight-color);
        background: linear-gradient(135deg, var(--card-bg), rgba(39, 179, 113, 0.05));
    }
    .badge-item.unlocked:hover {
        border-color: var(--primary-color);
    }
    .badge-item.locked {
        opacity: 0.6;
        filter: grayscale(80%);
        background-color: #f8f9fa;
    }
    .badge-item.locked:hover {
        transform: none;
        opacity: 0.7;
    }
    .badge-icon {
        font-size: 2.5em;
        margin-bottom: 10px;
        display: block;
    }
    .badge-name {
        font-size: 0.9em;
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
        color: var(--text-color);
    }
    .badge-description {
        font-size: 0.8em;
        color: #666;
        line-height: 1.3;
    }
    .badge-status {
        font-size: 0.75em;
        font-weight: 500;
        margin-top: 8px;
        padding: 3px 8px;
        border-radius: 12px;
        display: inline-block;
    }
    .badge-status.unlocked {
        background-color: var(--highlight-color);
        color: white;
    }
    .badge-status.locked {
        background-color: #6c757d;
        color: white;
    }

    .filter-buttons .btn {
        margin-right: 10px;
        margin-bottom: 10px;
        background-color: var(--light-accent);
        border: 1px solid #ccc;
        color: var(--text-color);
    }
    .filter-buttons .btn.active {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        color: white;
    }

    .africode-footer {
        text-align: center;
        margin-top: 40px;
        padding: 20px;
        color: #777;
        font-style: italic;
    }
    .africode-footer img {
        height: 30px;
        margin-bottom: 5px;
    }

    @media (max-width: 768px) {
        .competition-header {
            padding: 30px 15px;
        }
        .competition-header h1 {
            font-size: 1.8em;
        }
        .section-title {
            font-size: 1.5em;
        }
        .badge-grid {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 15px;
        }
        .filter-buttons .btn {
            margin-right: 5px;
            margin-bottom: 5px;
            font-size: 0.9em;
        }
    }

    .loading {
        text-align: center;
        padding: 20px;
        color: #666;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #666;
        font-style: italic;
    }

    .recent-badges {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .recent-badge {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: white;
        background-color: var(--primary-color);
    }
</style>

<!-- En-tête de la page Compétition -->
<div class="competition-header">
    <h1><i class="fas fa-trophy me-2"></i>Espace Compétition AfriCode</h1>
    <p>Relevez les défis, grimpez dans le classement et gagnez des badges !</p>
</div>

<div class="container mt-4">
    <!-- Section Compétitions Actuelles -->
    <section id="competitions" class="mb-5">
        <h2 class="section-title"><i class="fas fa-trophy me-2"></i>Compétitions Actuelles</h2>
        <div class="row" id="competitions-list">
            @if(isset($competitions) && count($competitions) > 0)
                @foreach($competitions as $competition)
                    <div class="col-md-6 mb-3">
                        <div class="challenge-card" style="border-left: 5px solid #1EA38B;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="mb-0">{{ $competition['title'] }}</h5>
                                <span class="badge bg-success">Compétition</span>
                            </div>
                            <p class="text-muted mb-2">{{ $competition['description'] }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary">{{ $competition['participants'] ?? '-' }} participants max</span>
                                <small class="text-muted">
                                    {{ $competition['start'] ? (new \Carbon\Carbon($competition['start']))->format('d/m/Y H:i') : '' }}
                                    -
                                    {{ $competition['end'] ? (new \Carbon\Carbon($competition['end']))->format('d/m/Y H:i') : '' }}
                                </small>
                            </div>
                            <div class="mt-2 text-end">
                                <a href="{{ route('competitions.show', $competition['slug']) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="no-data">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucune compétition active pour le moment.
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Section Défis Actuels -->
    <section id="challenges" class="mb-5">
        <h2 class="section-title"><i class="fas fa-code me-2"></i>Défis Actuels</h2>
        <div class="row" id="challenges-list">
            @if(count($challenges) > 0)
                @foreach($challenges as $challenge)
                    <div class="col-md-6 mb-3">
                        <div class="challenge-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="mb-0">{{ $challenge['title'] ?? $challenge->name }}</h5>
                                <span class="difficulty difficulty-{{ strtolower($challenge['difficulty'] ?? $challenge->difficulty) }}">
                                    {{ $challenge['difficulty'] ?? ucfirst($challenge->difficulty) }}
                                </span>
                            </div>
                            <p class="text-muted mb-2">{{ $challenge['description'] ?? $challenge->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">{{ $challenge['points'] ?? ($challenge->points ?? 0) }} points</span>
                                <small class="text-muted">{{ $challenge['timeLeft'] ?? '' }}</small>
                            </div>
                            <div class="mt-2 text-end">
                                <a href="{{ route('challenges.show', $challenge['id'] ?? $challenge->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="no-data">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun défi actif pour le moment.
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Section Classement -->
    <section id="leaderboard" class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h2 class="section-title mb-0"><i class="fas fa-users me-2"></i>Classement</h2>
            <div class="filter-buttons">
                <button class="btn btn-sm active" data-filter="global">Global</button>
                <button class="btn btn-sm" data-filter="weekly">Hebdomadaire</button>
                <button class="btn btn-sm" data-filter="monthly">Mensuel</button>
            </div>
        </div>
        <div class="table-responsive leaderboard-table">
            <table class="table mb-0">
                <thead>
                    <tr>
                        
                        <th scope="col">Utilisateur</th>
                        <th scope="col" class="text-end">Score</th>
                     <th scope="col" class="text-center">Badges Récents</th>
                    </tr>
                </thead>
                <tbody id="leaderboard-body">
                    @if(count($leaderboardData) > 0)
                        @foreach($leaderboardData as $user)
                            <tr class="{{ $currentUser && $currentUser['id'] == $user['id'] ? 'current-user-rank' : '' }}">
                             
                                <td class="leaderboard-user">
                                    <div class="user-avatar">{{ substr($user['name'], 0, 2) }}</div>
                                    {{ $user['name'] }}
                                </td>
                                <td class="text-end leaderboard-score">{{ number_format($user['score']) }}</td>
                                <td class="text-center">
                                       <td class="text-center leaderboard-rank rank-{{ $user['rank'] }}">
                                    @if($user['rank'] == 1)
                                        <i class="fas fa-trophy text-warning"></i>
                                    @elseif($user['rank'] == 2)
                                        <i class="fas fa-medal text-secondary"></i>
                                    @elseif($user['rank'] == 3)
                                        <i class="fas fa-medal text-danger"></i>
                                    @else
                                        {{ $user['rank'] }}
                                    @endif
                                </td>
                                    {{-- <div class="recent-badges">
                                        @foreach($user['recentBadges'] as $badge)
                                            <div class="recent-badge" style="background-color: {{ $badge['color'] ?? '#1EA38B' }}" title="{{ $badge['name'] }}">
                                                <i class="{{ $badge['icon'] }}"></i>
                                            </div>
                                        @endforeach
                                    </div> --}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center p-5">
                                <div class="no-data">
                                    <i class="fas fa-users me-2"></i>
                                    Aucun utilisateur dans le classement.
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
                @if($currentUser && !collect($leaderboardData)->contains('id', $currentUser['id']))
                    <tbody id="current-user-leaderboard-body">
                        <tr class="current-user-rank">
                           
                            <td class="leaderboard-user">
                                <div class="user-avatar">{{ substr($currentUser['name'], 0, 2) }}</div>
                                {{ $currentUser['name'] }} (Vous)
                            </td>
                            <td class="text-end leaderboard-score">{{ number_format($currentUser['score']) }}</td>
                            <td class="text-center">-</td>
                        </tr>
                    </tbody>
                @endif
            </table>
        </div>
    </section>

    <!-- Section Badges -->
    <section id="badges" class="mb-5">
        <h2 class="section-title"><i class="fas fa-medal me-2"></i>Galerie des Badges</h2>
        <div class="badge-grid" id="badges-list">
            @if(count($badges) > 0)
                @foreach($badges as $badge)
                    <div class="badge-item {{ $badge['locked'] ?? false ? 'locked' : 'unlocked' }}" title="{{ $badge['description'] }}">
                        <i class="{{ $badge['icon'] ?? 'fas fa-medal' }} badge-icon" style="color: {{ $badge['color'] ?? '#1EA38B' }};"></i>
                        <span class="badge-name">{{ $badge['name'] }}</span>
                        <span class="badge-description">{{ $badge['description'] }}</span>
                        <span class="badge-status {{ $badge['locked'] ?? false ? 'locked' : 'unlocked' }}">
                            {{ ($badge['locked'] ?? false) ? 'Verrouillé' : 'Débloqué' }}
                        </span>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="no-data">
                        <i class="fas fa-medal me-2"></i>
                        Aucun badge disponible.
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des filtres de classement
    const filterButtons = document.querySelectorAll('.filter-buttons .btn');
    const leaderboardBody = document.getElementById('leaderboard-body');
    const currentUserBody = document.getElementById('current-user-leaderboard-body');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Retirer la classe active de tous les boutons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            this.classList.add('active');

            const filterType = this.getAttribute('data-filter');
            loadLeaderboard(filterType);
        });
    });

    function loadLeaderboard(type) {
        // Afficher un indicateur de chargement
        leaderboardBody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center p-5">
                    <div class="loading">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Chargement du classement...
                    </div>
                </td>
            </tr>
        `;

        // Faire la requête AJAX
        fetch(`/api/leaderboard?type=${type}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    leaderboardBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center p-5">
                                <div class="no-data">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Erreur lors du chargement du classement.
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                if (data.length === 0) {
                    leaderboardBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center p-5">
                                <div class="no-data">
                                    <i class="fas fa-users me-2"></i>
                                    Aucun utilisateur dans ce classement.
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                // Afficher les données
                leaderboardBody.innerHTML = data.map(user => `
                    <tr class="${user.isCurrentUser ? 'current-user-rank' : ''}">
                        <td class="text-center leaderboard-rank rank-${user.rank}">
                            ${user.rank === 1 ? '<i class="fas fa-trophy text-warning"></i>' :
                              user.rank === 2 ? '<i class="fas fa-medal text-secondary"></i>' :
                              user.rank === 3 ? '<i class="fas fa-medal text-danger"></i>' :
                              user.rank}
                        </td>
                        <td class="leaderboard-user">
                            <div class="user-avatar">${user.name.substring(0, 2)}</div>
                            ${user.name}
                        </td>
                        <td class="text-end leaderboard-score">${user.score.toLocaleString()}</td>
                        <td class="text-center">
                            <div class="recent-badges">
                                ${user.recentBadges.map(badge => `
                                    <div class="recent-badge" style="background-color: ${badge.color || '#1EA38B'}" title="${badge.name}">
                                        <i class="${badge.icon}"></i>
                                    </div>
                                `).join('')}
                            </div>
                        </td>
                    </tr>
                `).join('');
            })
            .catch(error => {
                console.error('Erreur:', error);
                leaderboardBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center p-5">
                            <div class="no-data">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Erreur lors du chargement du classement.
                            </div>
                        </td>
                    </tr>
                `;
            });
    }
});
</script>

@endsection