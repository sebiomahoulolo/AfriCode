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
                    <!-- Ajouter d'autres filtres si nécessaire (par cours, etc.) -->
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
                     <span class="placeholder" style="width:60px; height: 60px; border-radius: 50%; display: inline-block;"></span>
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



    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- Simulation de données (remplacer par des appels API réels) ---
            const currentUser = { id: 101, name: "Mahoulolo SEBIO", avatar: 'https://via.placeholder.com/40/1EA38B/FFFFFF?text=MS', score: 850, rank: 15 };

            const sampleChallenges = [
                { id: 1, title: "Défi Quotidien : Fonction Inverse", description: "Écrire une fonction JS qui inverse une chaîne.", difficulty: "Débutant", points: 10, timeLeft: "23h 15m" },
                { id: 2, title: "Défi Hebdomadaire : API Météo", description: "Créer une page simple affichant la météo via une API publique.", difficulty: "Intermédiaire", points: 50, timeLeft: "5j 6h" },
                 { id: 3, title: "Challenge CSS : Bouton Animé", description: "Recréer un effet de survol complexe sur un bouton.", difficulty: "Débutant", points: 15, timeLeft: "10h 30m" },
                 { id: 4, title: "Challenge Backend : Authentification", description: "Mettre en place un système d'inscription/connexion basique en Laravel.", difficulty: "Avancé", points: 100, timeLeft: "Terminé" },
            ];

            const sampleLeaderboard = [
                { rank: 1, id: 5, name: "Amina Diallo", avatar: 'https://via.placeholder.com/40/FF8E2A/FFFFFF?text=AD', score: 1520, recentBadges: ['fa-star', 'fa-code'] },
                { rank: 2, id: 23, name: "Kwame Nkrumah", avatar: 'https://via.placeholder.com/40/E32D31/FFFFFF?text=KN', score: 1480, recentBadges: ['fa-fire'] },
                { rank: 3, id: 12, name: "Fatou Sow", avatar: 'https://via.placeholder.com/40/27B371/FFFFFF?text=FS', score: 1350, recentBadges: ['fa-cogs'] },
                { rank: 4, id: 8, name: "David Okoye", avatar: 'https://via.placeholder.com/40/1EA38B/FFFFFF?text=DO', score: 1200, recentBadges: [] },
                { rank: 5, id: 35, name: "Sarah Kone", avatar: 'https://via.placeholder.com/40/FF8E2A/FFFFFF?text=SK', score: 1150, recentBadges: ['fa-laptop-code'] },
                // ... autres utilisateurs
                 { rank: 14, id: 99, name: "Test User", avatar: 'https://via.placeholder.com/40/cccccc/FFFFFF?text=TU', score: 860, recentBadges: [] },
                 // { rank: currentUser.rank, id: currentUser.id, name: currentUser.name, avatar: currentUser.avatar, score: currentUser.score, recentBadges: ['fa-user-graduate'] }, // Utilisateur actuel
            ];

            const sampleBadges = [
                { id: 'b1', name: "Premier Code", icon: 'fa-play-circle', locked: false, description: "Avoir soumis son premier exercice." },
                { id: 'b2', name: "HTML Expert", icon: 'fa-html5', locked: false, description: "Avoir complété le parcours HTML/CSS." },
                { id: 'b3', name: "JS Ninja", icon: 'fa-js-square', locked: false, description: "Maîtriser les concepts avancés de JavaScript." },
                { id: 'b4', name: "Database Guru", icon: 'fa-database', locked: true, description: "Terminer le module PHP/MySQL." },
                { id: 'b5', name: "React Rockstar", icon: 'fa-react', locked: true, description: "Compléter le parcours ReactJS." },
                 { id: 'b6', name: "Full-Stack Dev", icon: 'fa-layer-group', locked: true, description: "Finir le parcours Full-Stack." },
                 { id: 'b7', name: "Serial Challenger", icon: 'fa-fire', locked: false, description: "Avoir complété 10 défis." },
                 { id: 'b8', name: "Top Contributor", icon: 'fa-users', locked: true, description: "Avoir aidé activement sur le forum." },
            ];

             // --- Fonctions de rendu ---

            function renderChallenges(challenges) {
                const list = document.getElementById('challenges-list');
                list.innerHTML = ''; // Vider les placeholders
                challenges.forEach(c => {
                     const col = document.createElement('div');
                     col.className = 'col-md-6';
                     let difficultyClass = '';
                    switch (c.difficulty.toLowerCase()) {
                        case 'débutant': difficultyClass = 'difficulty-debutant'; break;
                        case 'intermédiaire': difficultyClass = 'difficulty-intermediaire'; break;
                        case 'avancé': difficultyClass = 'difficulty-avance'; break;
                        default: difficultyClass = 'bg-secondary';
                    }
                    col.innerHTML = `
                        <div class="challenge-card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">${c.title}</h5>
                                <span class="badge ${difficultyClass}">${c.difficulty}</span>
                            </div>
                            <p class="text-muted small">${c.description}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="text-muted small"><i class="fas fa-clock me-1"></i> ${c.timeLeft}</span>
                                <a href="#" class="btn btn-sm btn-primary" style="background-color: var(--primary-color); border-color: var(--primary-color); ${c.timeLeft === 'Terminé' ? 'display: none;' : ''}">
                                    <i class="fas fa-arrow-right me-1"></i> Participer (${c.points} pts)
                                </a>
                                 <span class="text-success small" style="${c.timeLeft !== 'Terminé' ? 'display: none;' : ''}"><i class="fas fa-check-circle me-1"></i> Terminé</span>
                            </div>
                        </div>
                    `;
                    list.appendChild(col);
                });
            }

            function renderLeaderboard(leaderboardData, topN = 10) {
                const tbody = document.getElementById('leaderboard-body');
                const currentUserTbody = document.getElementById('current-user-leaderboard-body');
                tbody.innerHTML = ''; // Vider les placeholders
                currentUserTbody.innerHTML = ''; // Vider l'ancien rang utilisateur
                let userInTopN = false;

                leaderboardData.slice(0, topN).forEach(u => {
                    if (u.id === currentUser.id) userInTopN = true;
                     const tr = document.createElement('tr');
                     if (u.id === currentUser.id) {
                         tr.classList.add('current-user-rank'); // Style spécial pour l'utilisateur connecté
                     }
                     let rankClass = '';
                    if (u.rank === 1) rankClass = 'rank-1';
                    else if (u.rank === 2) rankClass = 'rank-2';
                    else if (u.rank === 3) rankClass = 'rank-3';

                    let recentBadgesHTML = u.recentBadges.map(icon => `<i class="fas ${icon} mx-1" title="Badge Récent"></i>`).join('');

                     tr.innerHTML = `
                        <td class="leaderboard-rank text-center ${rankClass}">${u.rank}</td>
                        <td class="leaderboard-user">
                            <img src="${u.avatar}" alt="${u.name}">
                            ${u.name}
                        </td>
                        <td class="leaderboard-score text-end">${u.score.toLocaleString()} pts</td>
                         <td class="text-center">${recentBadgesHTML || '-'}</td>
                    `;
                    tbody.appendChild(tr);
                });

                 // Ajouter la ligne de l'utilisateur s'il n'est pas dans le top N affiché
                if (!userInTopN && currentUser.rank > topN) {
                    const tr = document.createElement('tr');
                    tr.classList.add('current-user-rank');
                     let recentBadgesHTML = ['fa-user-graduate'].map(icon => `<i class="fas ${icon} mx-1" title="Badge Récent"></i>`).join(''); // Exemple badge pour user actuel
                    tr.innerHTML = `
                        <td class="leaderboard-rank text-center">${currentUser.rank}</td>
                        <td class="leaderboard-user">
                            <img src="${currentUser.avatar}" alt="${currentUser.name}">
                            ${currentUser.name} (Vous)
                        </td>
                        <td class="leaderboard-score text-end">${currentUser.score.toLocaleString()} pts</td>
                        <td class="text-center">${recentBadgesHTML || '-'}</td>
                    `;
                    // Ajouter un séparateur visuel si nécessaire
                    if (tbody.children.length > 0) { // S'il y a déjà des lignes dans le tbody principal
                         const separatorRow = document.createElement('tr');
                         separatorRow.innerHTML = `<td colspan="4" class="text-center text-muted py-1" style="border:none; background: none !important;">...</td>`;
                         currentUserTbody.appendChild(separatorRow);
                     }

                    currentUserTbody.appendChild(tr);
                }
            }

            function renderBadges(badges) {
                const list = document.getElementById('badges-list');
                list.innerHTML = ''; // Vider les placeholders
                badges.forEach(b => {
                     const div = document.createElement('div');
                     div.className = `badge-item ${b.locked ? 'locked' : ''}`;
                     div.setAttribute('title', `${b.name}${b.locked ? ' (Verrouillé)' : ''} - ${b.description}`); // Tooltip
                     div.innerHTML = `
                        <i class="fas ${b.icon} fa-3x mb-2" style="color: ${b.locked ? '#aaa' : 'var(--secondary-color)'};"></i>
                        <!-- Ou utiliser une image: <img src="/path/to/badge/${b.id}.png" alt="${b.name}"> -->
                        <span>${b.name}</span>
                    `;
                     // Ajouter un popover Bootstrap pour plus de détails au clic (optionnel)
                     div.setAttribute('data-bs-toggle', 'popover');
                     div.setAttribute('data-bs-trigger', 'hover focus');
                     div.setAttribute('data-bs-placement', 'top');
                     div.setAttribute('data-bs-content', b.description);

                    list.appendChild(div);
                });
                 // Initialiser les popovers Bootstrap après avoir ajouté les éléments
                 var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
                 var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                     return new bootstrap.Popover(popoverTriggerEl)
                 });
            }

             // --- Chargement Initial ---
            // Simuler un délai de chargement
            setTimeout(() => {
                renderChallenges(sampleChallenges);
                renderLeaderboard(sampleLeaderboard, 10); // Afficher le top 10
                renderBadges(sampleBadges);
            }, 500); // 0.5 seconde de délai

            // --- Gestion des Filtres (Classement) ---
            document.querySelectorAll('.filter-buttons .btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Désactiver les autres boutons actifs
                    document.querySelector('.filter-buttons .btn.active').classList.remove('active');
                    // Activer le bouton cliqué
                    this.classList.add('active');
                    const filterType = this.dataset.filter;
                    console.log("Filtrer classement par :", filterType);
                    // Ici, il faudrait refaire un appel API avec le filtre
                    // Pour la simulation, on peut juste re-render les mêmes données ou des données simulées différentes
                    // Exemple simple : re-render les mêmes données
                     renderLeaderboard(sampleLeaderboard, 10); // Remplacer par les données filtrées réelles
                });
            });

        });
    </script>

@endsection