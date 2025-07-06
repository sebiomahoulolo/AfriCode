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
        min-width: 40px; /* Espace pour le rang */
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

    /* Style pour la ligne de l'utilisateur connecté */
    .current-user-rank {
        background-color: rgba(255, 142, 42, 0.15) !important; /* Orange léger */
        border-top: 2px solid var(--secondary-color);
        border-bottom: 2px solid var(--secondary-color);
        font-weight: bold;
    }

    .badge-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 20px;
        text-align: center;
    }
    .badge-item {
        background-color: var(--card-bg);
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
        cursor: pointer;
    }
    .badge-item:hover {
        transform: scale(1.05);
    }
    .badge-item img {
        width: 60px;
        height: 60px;
        margin-bottom: 10px;
    }
    .badge-item span {
        font-size: 0.9em;
        font-weight: 500;
        display: block;
    }
    .badge-item.locked {
        opacity: 0.5;
        filter: grayscale(80%);
    }
    .badge-item.locked:hover {
        transform: none; /* Pas de zoom si verrouillé */
    }

    .filter-buttons .btn {
        margin-right: 10px;
        margin-bottom: 10px; /* Pour mobile */
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
        height: 30px; /* Logo AfriCode */
        margin-bottom: 5px;
    }

    /* Responsive design */
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
</style>

<!-- En-tête de la page Compétition -->
<div class="competition-header">
    <h1><i class="fas fa-trophy me-2"></i>Espace Compétition AfriCode</h1>
    <p>Relevez les défis, grimpez dans le classement et gagnez des badges !</p>
</div>

<div class="container mt-4">
    <!-- Section Défis Actuels -->
    <section id="challenges" class="mb-5">
        <h2 class="section-title"><i class="fas fa-code me-2"></i>Défis Actuels</h2>
        <div class="row" id="challenges-list">
            <!-- Les défis seront chargés ici par JS -->
            <div class="col-md-6 placeholder-glow">
                <div class="challenge-card">
                    <span class="placeholder col-8"></span>
                    <span class="placeholder col-4"></span>
                    <span class="placeholder col-6"></span>
                    <span class="placeholder col-8"></span>
                </div>
            </div>
            <div class="col-md-6 placeholder-glow">
                <div class="challenge-card">
                    <span class="placeholder col-7"></span>
                    <span class="placeholder col-4"></span>
                    <span class="placeholder col-4"></span>
                    <span class="placeholder col-6"></span>
                </div>
            </div>
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
                        <th scope="col" class="text-center">#</th>
                        <th scope="col">Utilisateur</th>
                        <th scope="col" class="text-end">Score</th>
                        <th scope="col" class="text-center">Badges Récents</th>
                    </tr>
                </thead>
                <tbody id="leaderboard-body">
                    <!-- Les lignes du classement seront chargées ici par JS -->
                    <tr><td colspan="4" class="text-center p-5 placeholder-glow"><span class="placeholder col-6"></span></td></tr>
                    <tr><td colspan="4" class="text-center p-5 placeholder-glow"><span class="placeholder col-5"></span></td></tr>
                    <tr><td colspan="4" class="text-center p-5 placeholder-glow"><span class="placeholder col-6"></span></td></tr>
                </tbody>
                <tbody id="current-user-leaderboard-body">
                    <!-- La ligne de l'utilisateur connecté sera ajoutée ici si hors top N -->
                </tbody>
            </table>
        </div>
    </section>

    <!-- Section Badges -->
    <section id="badges" class="mb-5">
        <h2 class="section-title"><i class="fas fa-medal me-2"></i>Galerie des Badges</h2>
        <div class="badge-grid" id="badges-list">
            <!-- Les badges seront chargés ici par JS -->
            <div class="badge-item placeholder-glow">
                <span class="placeholder" style="width:60px; height: 60px; border-radius: 50%; display: inline-block;"></span>
                <span class="placeholder col-6"></span>
            </div>
            <div class="badge-item placeholder-glow">
                <span class="placeholder" style="width:60px; height: 60px; border-radius: 50%; border-radius: 50%; display: inline-block;"></span>
                <span class="placeholder col-7"></span>
            </div>
            <div class="badge-item placeholder-glow locked">
                <span class="placeholder" style="width:60px; height: 60px; border-radius: 50%; display: inline-block;"></span>
                <span class="placeholder col-5"></span>
            </div>
            <div class="badge-item placeholder-glow locked">
                <span class="placeholder" style="width:60px; height: 60px; border-radius: 50%; display: inline-block;"></span>
                <span class="placeholder col-6"></span>
            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Données réelles du backend ---
    const currentUser = @json($currentUser ?? null);
    const sampleChallenges = @json($challenges ?? []);
    const sampleLeaderboard = @json($leaderboardData ?? []);
    const sampleBadges = @json($badges ?? []);

    console.log('Données reçues:', {
        challenges: sampleChallenges.length,
        leaderboard: sampleLeaderboard.length,
        badges: sampleBadges.length,
        currentUser: currentUser
    });

    // --- Fonctions utilitaires ---
    function getElementById(id) {
        const element = document.getElementById(id);
        if (!element) {
            console.error(`Élément avec l'ID "${id}" non trouvé`);
            return null;
        }
        return element;
    }

    function getDifficultyClass(difficulty) {
        const difficultyMap = {
            'débutant': 'difficulty-debutant',
            'intermédiaire': 'difficulty-intermediaire',
            'avancé': 'difficulty-avance'
        };
        return difficultyMap[difficulty.toLowerCase()] || 'bg-secondary';
    }

    function getRankClass(rank) {
        if (rank === 1) return 'rank-1';
        if (rank === 2) return 'rank-2';
        if (rank === 3) return 'rank-3';
        return '';
    }

    // --- Fonctions de rendu ---
    function renderChallenges(challenges) {
        const list = getElementById('challenges-list');
        if (!list) return;

        list.innerHTML = '';
        
        if (!challenges || challenges.length === 0) {
            list.innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun défi disponible pour le moment. Revenez bientôt !
                    </div>
                </div>
            `;
            return;
        }

        challenges.forEach(challenge => {
            const col = document.createElement('div');
            col.className = 'col-md-6';
            
            const difficultyClass = getDifficultyClass(challenge.difficulty);
            const isCompleted = challenge.timeLeft === 'Terminé';
            
            col.innerHTML = `
                <div class="challenge-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">${challenge.title}</h5>
                        <span class="badge ${difficultyClass}">${challenge.difficulty}</span>
                    </div>
                    <p class="text-muted small">${challenge.description}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted small">
                            <i class="fas fa-clock me-1"></i> ${challenge.timeLeft}
                        </span>
                        ${isCompleted ? 
                            `<span class="text-success small"><i class="fas fa-check-circle me-1"></i> Terminé</span>` :
                            `<a href="#" class="btn btn-sm btn-primary" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                                <i class="fas fa-arrow-right me-1"></i> Participer (${challenge.points} pts)
                            </a>`
                        }
                    </div>
                </div>
            `;
            list.appendChild(col);
        });
    }

    function renderLeaderboard(leaderboardData, topN = 10) {
        const tbody = getElementById('leaderboard-body');
        const currentUserTbody = getElementById('current-user-leaderboard-body');
        
        if (!tbody || !currentUserTbody) return;

        tbody.innerHTML = '';
        currentUserTbody.innerHTML = '';
        
        if (!leaderboardData || leaderboardData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center p-5">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucun classement disponible pour le moment.
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        let userInTopN = false;

        leaderboardData.slice(0, topN).forEach(user => {
            if (currentUser && user.id === currentUser.id) userInTopN = true;
            
            const tr = document.createElement('tr');
            if (currentUser && user.id === currentUser.id) {
                tr.classList.add('current-user-rank');
            }
            
            const rankClass = getRankClass(user.rank);
            const recentBadgesHTML = (user.recentBadges || []).map(icon => 
                `<i class="fas ${icon} mx-1" title="Badge Récent"></i>`
            ).join('');

            tr.innerHTML = `
                <td class="leaderboard-rank text-center ${rankClass}">${user.rank}</td>
                <td class="leaderboard-user">
                    <img src="${user.avatar}" alt="${user.name}" onerror="this.src='https://via.placeholder.com/40/cccccc/FFFFFF?text=U'">
                    ${user.name}
                </td>
                <td class="leaderboard-score text-end">${user.score.toLocaleString()} pts</td>
                <td class="text-center">${recentBadgesHTML || '-'}</td>
            `;
            tbody.appendChild(tr);
        });

        // Ajouter la ligne de l'utilisateur s'il n'est pas dans le top N affiché
        if (currentUser && !userInTopN && currentUser.rank > topN) {
            const tr = document.createElement('tr');
            tr.classList.add('current-user-rank');
            
            const recentBadgesHTML = ['fa-user-graduate'].map(icon => 
                `<i class="fas ${icon} mx-1" title="Badge Récent"></i>`
            ).join('');

            tr.innerHTML = `
                <td class="leaderboard-rank text-center">${currentUser.rank}</td>
                <td class="leaderboard-user">
                    <img src="${currentUser.avatar}" alt="${currentUser.name}" onerror="this.src='https://via.placeholder.com/40/cccccc/FFFFFF?text=U'">
                    ${currentUser.name} (Vous)
                </td>
                <td class="leaderboard-score text-end">${currentUser.score.toLocaleString()} pts</td>
                <td class="text-center">${recentBadgesHTML || '-'}</td>
            `;

            // Ajouter un séparateur visuel si nécessaire
            if (tbody.children.length > 0) {
                const separatorRow = document.createElement('tr');
                separatorRow.innerHTML = `<td colspan="4" class="text-center text-muted py-1" style="border:none; background: none !important;">...</td>`;
                currentUserTbody.appendChild(separatorRow);
            }

            currentUserTbody.appendChild(tr);
        }
    }

    function renderBadges(badges) {
        const list = getElementById('badges-list');
        if (!list) return;

        list.innerHTML = '';
        
        if (!badges || badges.length === 0) {
            list.innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun badge disponible pour le moment.
                    </div>
                </div>
            `;
            return;
        }

        badges.forEach(badge => {
            const div = document.createElement('div');
            div.className = `badge-item ${badge.locked ? 'locked' : ''}`;
            div.setAttribute('title', `${badge.name}${badge.locked ? ' (Verrouillé)' : ''} - ${badge.description}`);
            
            div.innerHTML = `
                <i class="fas ${badge.icon} fa-3x mb-2" style="color: ${badge.locked ? '#aaa' : (badge.color || 'var(--secondary-color)')};"></i>
                <span>${badge.name}</span>
            `;

            // Ajouter un popover Bootstrap pour plus de détails
            div.setAttribute('data-bs-toggle', 'popover');
            div.setAttribute('data-bs-trigger', 'hover focus');
            div.setAttribute('data-bs-placement', 'top');
            div.setAttribute('data-bs-content', badge.description);

            list.appendChild(div);
        });

        // Initialiser les popovers Bootstrap
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        popoverTriggerList.forEach(triggerEl => {
            new bootstrap.Popover(triggerEl);
        });
    }

    // --- Chargement Initial ---
    function initializePage() {
        try {
            console.log('Initialisation de la page...');
            renderChallenges(sampleChallenges);
            renderLeaderboard(sampleLeaderboard, 10);
            renderBadges(sampleBadges);
            console.log('Page initialisée avec succès');
        } catch (error) {
            console.error('Erreur lors de l\'initialisation de la page:', error);
        }
    }

    // Chargement immédiat (pas de délai)
    initializePage();

    // --- Gestion des Filtres (Classement) ---
    const filterButtons = document.querySelectorAll('.filter-buttons .btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            try {
                // Désactiver les autres boutons actifs
                const activeButton = document.querySelector('.filter-buttons .btn.active');
                if (activeButton) {
                    activeButton.classList.remove('active');
                }
                
                // Activer le bouton cliqué
                this.classList.add('active');
                
                const filterType = this.dataset.filter;
                console.log("Filtrer classement par :", filterType);
                
                // Appel API pour récupérer les données filtrées
                fetch(`/api/leaderboard?type=${filterType}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Erreur API:', data.error);
                            return;
                        }
                        renderLeaderboard(data, 10);
                    })
                    .catch(error => {
                        console.error('Erreur lors du filtrage:', error);
                        // Fallback : re-render les mêmes données
                        renderLeaderboard(sampleLeaderboard, 10);
                    });
            } catch (error) {
                console.error('Erreur lors du filtrage:', error);
            }
        });
    });

    // --- Gestion des erreurs d'images ---
    document.addEventListener('error', function(e) {
        if (e.target.tagName === 'IMG') {
            e.target.src = 'https://via.placeholder.com/40/cccccc/FFFFFF?text=U';
        }
    }, true);
});
</script>
@endpush 