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
        }
        .topic-title a:hover { text-decoration: underline; }
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
            padding: 8px 0;
            border-bottom: 1px dashed var(--border-color);
        }
        .leaderboard-widget-item:last-child { border-bottom: none; }
        .leaderboard-widget-rank {
            font-weight: bold;
            font-size: 1.1em;
            min-width: 30px;
            text-align: center;
            margin-right: 10px;
        }
        .rank-1 { color: var(--gold-color); }
        .rank-2 { color: var(--silver-color); }
        .rank-3 { color: var(--bronze-color); }
        .leaderboard-widget-avatar { width: 30px; height: 30px; border-radius: 50%; margin-right: 8px; }
        .leaderboard-widget-name { font-size: 0.9em; flex-grow: 1; font-weight: 500; }
        .leaderboard-widget-score { font-size: 0.85em; color: #555; font-weight: 600; }
        .view-full-leaderboard { display: block; text-align: center; margin-top: 15px; font-size: 0.9em; }

        /* Categories Widget */
        .category-list { list-style: none; padding: 0; margin: 0; }
        .category-list a {
            display: block;
            padding: 8px 10px;
            margin-bottom: 5px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9em;
            transition: background-color 0.2s ease;
        }
        .category-list a:hover { background-color: var(--hover-bg); }
        .category-list a.active { background-color: var(--active-bg); color: var(--primary-color); font-weight: 600; }
        .category-list i { margin-right: 8px; color: var(--primary-color); }

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
                            <!-- Loading Placeholder -->
                            <tr class="placeholder-glow">
                                <td data-label="Sujet"><span class="placeholder col-10 d-block mb-1"></span><span class="placeholder col-6 d-block"></span></td>
                                <td data-label="Catégorie" class="text-center"><span class="placeholder col-7"></span></td>
                                <td data-label="Statistiques" class="text-center"><span class="placeholder col-8"></span></td>
                                <td data-label="Dernier Message"><span class="placeholder col-9 d-block mb-1"></span><span class="placeholder col-5 d-block"></span></td>
                            </tr>
                            <tr class="placeholder-glow">
                                <td data-label="Sujet"><span class="placeholder col-9 d-block mb-1"></span><span class="placeholder col-7 d-block"></span></td>
                                <td data-label="Catégorie" class="text-center"><span class="placeholder col-6"></span></td>
                                <td data-label="Statistiques" class="text-center"><span class="placeholder col-7"></span></td>
                                <td data-label="Dernier Message"><span class="placeholder col-10 d-block mb-1"></span><span class="placeholder col-6 d-block"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                 <!-- TODO: Ajouter Pagination -->
            </div>

            <!-- Colonne Latérale : Compétition et Catégories -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <!-- Widget Compétition -->
                <div class="sidebar-widget">
                    <h5><i class="fas fa-trophy me-2" style="color: var(--gold-color);"></i> Top Apprenants</h5>
                    <ul class="leaderboard-widget-list" id="leaderboard-widget">
                        <!-- Loading Placeholders -->
                        <li class="leaderboard-widget-item placeholder-glow"><span class="placeholder col-10"></span></li>
                        <li class="leaderboard-widget-item placeholder-glow"><span class="placeholder col-9"></span></li>
                        <li class="leaderboard-widget-item placeholder-glow"><span class="placeholder col-8"></span></li>
                    </ul>
                    <a href="/competition" class="view-full-leaderboard">Voir le classement complet <i class="fas fa-arrow-right ms-1"></i></a>
                </div>

                <!-- Widget Catégories -->
                <div class="sidebar-widget">
                    <h5><i class="fas fa-tags me-2"></i> Catégories</h5>
                    <div class="list-group list-group-flush category-list" id="category-list">
                        <a href="#" class="list-group-item list-group-item-action active" data-category="all"><i class="fas fa-globe-africa"></i> Toutes les discussions</a>
                        <!-- Catégories chargées ici -->
                        <a href="#" class="list-group-item list-group-item-action placeholder-glow" aria-disabled="true"><span class="placeholder col-6"></span></a>
                        <a href="#" class="list-group-item list-group-item-action placeholder-glow" aria-disabled="true"><span class="placeholder col-7"></span></a>
                         <a href="#" class="list-group-item list-group-item-action placeholder-glow" aria-disabled="true"><span class="placeholder col-5"></span></a>
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
            <form id="newTopicForm">
              <div class="mb-3">
                <label for="topicTitle" class="form-label">Titre du Sujet <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="topicTitle" required>
              </div>
              <div class="mb-3">
                <label for="topicCategory" class="form-label">Catégorie <span class="text-danger">*</span></label>
                <select class="form-select" id="topicCategory" required>
                  <option selected disabled value="">Choisir une catégorie...</option>
                  <!-- Options de catégories chargées ici -->
                </select>
              </div>
              <div class="mb-3">
                <label for="topicContent" class="form-label">Votre Message <span class="text-danger">*</span></label>
                <textarea class="form-control" id="topicContent" rows="6" required placeholder="Décrivez votre question ou sujet de discussion..."></textarea>
                 <!-- TODO: Ajouter un éditeur de texte riche (TinyMCE, Quill) -->
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" form="newTopicForm" class="btn btn-primary" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                <i class="fas fa-paper-plane me-1"></i> Publier le Sujet
            </button>
          </div>
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
                                <a href="/forum/topic/${topic.id}">${escapeHtml(topic.title)}</a>
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
                            Par <a href="#" class="author-link">${escapeHtml(topic.lastPostAuthor)}</a>
                            <span class="date">${topic.lastPostDate}</span>
                        </td>
                    `;
                    topicsListBody.appendChild(tr);
                });
            }

            function loadLeaderboardWidget() {
                leaderboardWidgetList.innerHTML = ''; // Clear placeholders
                sampleLeaderboard.slice(0, 5).forEach(user => { // Show top 5
                    let rankClass = '';
                    if (user.rank === 1) rankClass = 'rank-1';
                    else if (user.rank === 2) rankClass = 'rank-2';
                    else if (user.rank === 3) rankClass = 'rank-3';

                    const li = document.createElement('li');
                    li.className = 'leaderboard-widget-item';
                    li.innerHTML = `
                        <span class="leaderboard-widget-rank ${rankClass}">${user.rank}</span>
                        <img src="${user.avatar}" alt="Avatar" class="leaderboard-widget-avatar">
                        <span class="leaderboard-widget-name">${escapeHtml(user.name)}</span>
                        <span class="leaderboard-widget-score">${user.score.toLocaleString()} pts</span>
                    `;
                    leaderboardWidgetList.appendChild(li);
                });
            }

            function loadCategories() {
                // Clear placeholders (except "All discussions")
                const placeholders = categoryListContainer.querySelectorAll('.placeholder-glow');
                placeholders.forEach(p => p.remove());

                // Add categories to list and modal select
                 newTopicCategorySelect.innerHTML = '<option selected disabled value="">Choisir une catégorie...</option>'; // Reset select

                sampleCategories.forEach(cat => {
                    // Add to sidebar list
                    const a = document.createElement('a');
                    a.href = '#';
                    a.className = 'list-group-item list-group-item-action category-filter-link';
                    a.dataset.category = cat.id;
                    // Utiliser fas pour la plupart, fab pour les marques comme Laravel/React/JS
                    let iconClass = (['laravel', 'react', 'js-square', 'html5', 'css3-alt', 'php'].includes(cat.icon)) ? 'fab' : 'fas';
                    a.innerHTML = `<i class="${iconClass} fa-${cat.icon} fa-fw"></i> ${escapeHtml(cat.name)}`;
                    a.addEventListener('click', handleCategoryFilter);
                    categoryListContainer.appendChild(a);

                     // Add to modal select
                     const option = document.createElement('option');
                     option.value = cat.id;
                     option.textContent = cat.name;
                     newTopicCategorySelect.appendChild(option);
                });
                 // Add event listener for "All discussions" link
                 categoryListContainer.querySelector('[data-category="all"]').addEventListener('click', handleCategoryFilter);
            }

             function handleCategoryFilter(event) {
                 event.preventDefault();
                 const selectedCategory = event.currentTarget.dataset.category;

                 // Update active state visually
                 categoryListContainer.querySelectorAll('.category-filter-link').forEach(link => {
                    link.classList.remove('active');
                 });
                 event.currentTarget.classList.add('active');

                 console.log("Filter by category:", selectedCategory);
                 // --- Actual Filtering Logic ---
                 // TODO: Replace with API call: GET /api/forum/topics?category=selectedCategory
                 let filteredTopics;
                 if (selectedCategory === 'all') {
                     filteredTopics = sampleTopics;
                 } else {
                     // Find category name from ID for comparison (case insensitive)
                     const categoryName = sampleCategories.find(c => c.id === selectedCategory)?.name;
                     filteredTopics = sampleTopics.filter(topic => topic.category.toLowerCase() === categoryName?.toLowerCase());
                 }
                 loadTopics(filteredTopics);
             }

             function handleSearch() {
                 const searchTerm = searchInput.value.trim().toLowerCase();
                 console.log("Search for:", searchTerm);
                 // TODO: Replace with API call: GET /api/forum/topics?search=searchTerm
                 const filteredTopics = sampleTopics.filter(topic =>
                    topic.title.toLowerCase().includes(searchTerm) ||
                    topic.author.toLowerCase().includes(searchTerm)
                 );
                 loadTopics(filteredTopics);
                  // Maybe clear category filter when searching? Or combine?
                  categoryListContainer.querySelectorAll('.category-filter-link').forEach(link => link.classList.remove('active'));
                  categoryListContainer.querySelector('[data-category="all"]').classList.add('active');
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

            // --- Initial Load ---
            setTimeout(() => { // Simulate loading delay
                loadTopics();
                loadLeaderboardWidget();
                loadCategories();
            }, 500);

        });
    </script>

</body>
</html>