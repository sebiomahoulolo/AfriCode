@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')



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
            --active-bg: #e6f4f1;
            --message-sent-bg: var(--primary-color);
            --message-received-bg: #e5e5ea;
            --message-received-text: #000000;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            height: 100vh;
            overflow: hidden; /* Empêcher le scroll global */
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .forum-header {
            background: linear-gradient(135deg, var(--primary-color), var(--highlight-color));
            color: white;
            padding: 15px 20px; /* Moins haut */
            flex-shrink: 0; /* Ne pas réduire */
        }
         .forum-header h1 { font-size: 1.5em; margin: 0; }

        .content-area {
            display: flex;
            flex-grow: 1; /* Prendre l'espace restant */
            overflow: hidden; /* Important */
            background-color: var(--card-bg);
        }

        /* --- Sidebar (Colonne Gauche) --- */
        .sidebar {
            width: 300px; /* Largeur fixe pour la sidebar */
            flex-shrink: 0; /* Ne pas réduire */
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            background-color: var(--card-bg);
        }

        .sidebar .nav-tabs {
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }
         .sidebar .nav-tabs .nav-link {
             color: var(--text-color);
             border-radius: 0;
             font-weight: 500;
             border: none;
             border-bottom: 3px solid transparent;
             padding: 12px 15px;
         }
         .sidebar .nav-tabs .nav-link.active {
             color: var(--primary-color);
             border-bottom: 3px solid var(--primary-color);
             background-color: transparent;
         }

        .sidebar .tab-content {
            flex-grow: 1;
            overflow-y: auto; /* Scroll si contenu dépasse */
        }

        .sidebar-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-list-item {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .sidebar-list-item:hover {
            background-color: var(--hover-bg);
        }
        .sidebar-list-item.active {
            background-color: var(--active-bg);
            border-left: 3px solid var(--primary-color);
             padding-left: 12px;
        }

        /* Style Forum Item */
        .forum-item h6 { font-size: 0.95em; margin-bottom: 3px; font-weight: 600; }
        .forum-item p { font-size: 0.8em; color: #666; margin-bottom: 0; }

        /* Style Message Item */
        .message-item { display: flex; align-items: center; }
        .message-item img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .message-item .contact-info { flex-grow: 1; overflow: hidden; }
        .message-item .contact-name { font-weight: 600; font-size: 0.9em; display: block; }
        .message-item .last-message { font-size: 0.85em; color: #555; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .message-item .timestamp { font-size: 0.75em; color: #888; flex-shrink: 0; margin-left: 5px; }
        .message-item .unread-indicator {
            width: 8px; height: 8px; background-color: var(--secondary-color); border-radius: 50%; display: inline-block; margin-left: 8px;
        }

        .new-discussion-btn {
            display: block;
            margin: 15px;
        }

        /* --- Main Content (Colonne Droite) --- */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Important */
        }

        .content-placeholder {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #aaa;
        }
         .content-placeholder i { font-size: 4em; margin-bottom: 15px; }

        /* --- Affichage Forum --- */
        .forum-view {
            display: flex; /* Default to none via JS */
            flex-direction: column;
            height: 100%; /* Prend toute la hauteur */
        }
         .forum-topic-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            background-color: #f9f9f9;
            flex-shrink: 0;
         }
         .forum-topic-header h4 { margin-bottom: 5px; }
         .forum-topic-header p { font-size: 0.9em; color: #666; margin-bottom: 0; }
         .forum-replies {
             flex-grow: 1;
             overflow-y: auto;
             padding: 20px;
         }
        .reply-card {
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }
         .reply-card.original-post { border-left: 4px solid var(--primary-color); }
         .reply-header { display: flex; align-items: center; margin-bottom: 10px; }
         .reply-header img { width: 30px; height: 30px; border-radius: 50%; margin-right: 10px; }
         .reply-header .author { font-weight: 600; font-size: 0.9em; }
         .reply-header .date { margin-left: auto; font-size: 0.8em; color: #888; }
         .reply-content { font-size: 0.95em; line-height: 1.6; }
         .reply-form {
             padding: 15px 20px;
             border-top: 1px solid var(--border-color);
             background-color: #f9f9f9;
             flex-shrink: 0;
         }
         .reply-form textarea { height: 80px; }

        /* --- Affichage Chat --- */
        .chat-view {
            display: flex; /* Default to none via JS */
            flex-direction: column;
            height: 100%; /* Prend toute la hauteur */
        }
        .chat-header {
            padding: 10px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
            flex-shrink: 0;
        }
        .chat-header img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .chat-header .contact-name { font-weight: 600; }
        .chat-header .status { font-size: 0.8em; color: var(--highlight-color); margin-left: auto; } /* Online/Offline */

        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column; /* Messages s'ajoutent en bas */
        }
        .message {
            max-width: 75%;
            padding: 10px 15px;
            border-radius: 18px;
            margin-bottom: 10px;
            line-height: 1.4;
            font-size: 0.95em;
            word-wrap: break-word;
        }
        .message.sent {
            background-color: var(--message-sent-bg);
            color: white;
            border-bottom-right-radius: 5px;
            align-self: flex-end; /* Aligner à droite */
        }
        .message.received {
            background-color: var(--message-received-bg);
            color: var(--message-received-text);
            border-bottom-left-radius: 5px;
            align-self: flex-start; /* Aligner à gauche */
        }
        .message-time {
             font-size: 0.75em;
             color: #888;
             margin-top: 5px;
             display: block;
             text-align: right; /* Align time to the right within bubble */
        }
        .message.sent .message-time { color: rgba(255,255,255,0.7); }

        .chat-input-area {
            display: flex;
            padding: 10px 20px;
            border-top: 1px solid var(--border-color);
            background-color: #f9f9f9;
            flex-shrink: 0;
        }
         .chat-input-area input[type="text"] {
             flex-grow: 1;
             border-radius: 20px;
             border: 1px solid var(--border-color);
             padding: 8px 15px;
             margin-right: 10px;
         }
         .chat-input-area input[type="text"]:focus {
             outline: none;
             border-color: var(--primary-color);
             box-shadow: 0 0 0 2px rgba(30, 163, 139, 0.2);
         }
         .chat-input-area button {
             border-radius: 50%;
             width: 40px;
             height: 40px;
             padding: 0;
             background-color: var(--primary-color);
             border-color: var(--primary-color);
         }
         .chat-input-area button i { font-size: 1.1em; }

    </style>
</head>
<body>

    <div class="main-wrapper">
        <header class="forum-header">
            <h1><i class="fas fa-comments me-2"></i> Forum & Messagerie</h1>
        </header>

        <div class="content-area">

            <!-- Sidebar -->
            <aside class="sidebar">
                <ul class="nav nav-tabs nav-fill" id="sidebarTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="forum-tab" data-bs-toggle="tab" data-bs-target="#forum-list-pane" type="button" role="tab" aria-controls="forum-list-pane" aria-selected="true">
                            <i class="fas fa-list-alt me-1"></i> Forum
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#message-list-pane" type="button" role="tab" aria-controls="message-list-pane" aria-selected="false">
                            <i class="fas fa-envelope me-1"></i> Messages <span class="badge bg-danger ms-1" id="unread-count">3</span>
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Forum List Pane -->
                    <div class="tab-pane fade show active" id="forum-list-pane" role="tabpanel" aria-labelledby="forum-tab">
                        <button class="btn btn-sm new-discussion-btn text-white" style="background-color: var(--secondary-color); border-color: var(--secondary-color);"><i class="fas fa-plus me-1"></i> Nouvelle Discussion</button>
                        <ul class="sidebar-list" id="forum-list">
                            <!-- Forum items loaded here -->
                             <li class="sidebar-list-item forum-item placeholder-glow">
                                 <span class="placeholder col-8 d-block mb-1"></span>
                                 <span class="placeholder col-5 d-block"></span>
                             </li>
                             <li class="sidebar-list-item forum-item placeholder-glow">
                                 <span class="placeholder col-7 d-block mb-1"></span>
                                 <span class="placeholder col-6 d-block"></span>
                             </li>
                             <li class="sidebar-list-item forum-item placeholder-glow">
                                 <span class="placeholder col-9 d-block mb-1"></span>
                                 <span class="placeholder col-4 d-block"></span>
                             </li>
                        </ul>
                    </div>
                    <!-- Messages List Pane -->
                    <div class="tab-pane fade" id="message-list-pane" role="tabpanel" aria-labelledby="messages-tab">
                        <ul class="sidebar-list" id="message-list">
                            <!-- Message items loaded here -->
                             <li class="sidebar-list-item message-item placeholder-glow">
                                 <span class="placeholder rounded-circle me-2" style="width:40px; height: 40px;"></span>
                                 <div style="flex-grow: 1;">
                                     <span class="placeholder col-5 d-block mb-1"></span>
                                     <span class="placeholder col-8 d-block"></span>
                                 </div>
                             </li>
                              <li class="sidebar-list-item message-item placeholder-glow">
                                 <span class="placeholder rounded-circle me-2" style="width:40px; height: 40px;"></span>
                                 <div style="flex-grow: 1;">
                                     <span class="placeholder col-6 d-block mb-1"></span>
                                     <span class="placeholder col-7 d-block"></span>
                                 </div>
                             </li>
                        </ul>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="main-content">
                <!-- Placeholder initial -->
                 <div class="content-placeholder" id="content-placeholder">
                     <i class="fas fa-inbox"></i>
                     <p>Sélectionnez un sujet de forum ou une conversation.</p>
                 </div>

                 <!-- Forum View (caché par défaut) -->
                 <div class="forum-view" id="forum-view" style="display: none;">
                     <div class="forum-topic-header">
                         <h4 id="forum-topic-title">Titre du Sujet...</h4>
                         <p id="forum-topic-meta">Posté par <span class="fw-bold">Auteur</span> le <span class="text-muted">Date</span></p>
                     </div>
                     <div class="forum-replies">
                         <div class="reply-card original-post" id="original-post">
                             <div class="reply-header">
                                 <img src="" alt="Avatar" id="op-avatar">
                                 <span class="author" id="op-author"></span>
                                 <span class="date" id="op-date"></span>
                             </div>
                             <div class="reply-content" id="op-content">
                                 Contenu du message original...
                             </div>
                         </div>
                         <hr>
                         <h5>Réponses</h5>
                         <div id="replies-list">
                             <!-- Replies loaded here -->
                         </div>
                     </div>
                     <div class="reply-form">
                         <form id="replyForm">
                             <div class="mb-2">
                                 <label for="replyText" class="form-label visually-hidden">Votre réponse</label>
                                 <textarea class="form-control" id="replyText" rows="3" placeholder="Écrivez votre réponse ici..."></textarea>
                             </div>
                             <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                                 <i class="fas fa-paper-plane me-1"></i> Envoyer la réponse
                             </button>
                         </form>
                     </div>
                 </div>

                 <!-- Chat View (caché par défaut) -->
                 <div class="chat-view" id="chat-view" style="display: none;">
                     <div class="chat-header">
                         <img src="" alt="Avatar" id="chat-partner-avatar">
                         <div>
                             <div class="contact-name" id="chat-partner-name">Nom du Contact</div>
                             <div class="status text-success" id="chat-partner-status">En ligne</div>
                         </div>
                     </div>
                     <div class="chat-messages" id="chat-messages-list">
                         <!-- Messages loaded here -->
                     </div>
                     <div class="chat-input-area">
                         <input type="text" class="form-control" id="chat-message-input" placeholder="Écrivez votre message...">
                         <button class="btn btn-primary" id="send-message-btn" type="button">
                             <i class="fas fa-paper-plane"></i>
                         </button>
                     </div>
                 </div>

            </main>

        </div> <!-- fin .content-area -->
    </div> <!-- fin .main-wrapper -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- DOM Elements ---
            const forumList = document.getElementById('forum-list');
            const messageList = document.getElementById('message-list');
            const contentPlaceholder = document.getElementById('content-placeholder');
            const forumView = document.getElementById('forum-view');
            const chatView = document.getElementById('chat-view');
            const forumTab = document.getElementById('forum-tab');
            const messagesTab = document.getElementById('messages-tab');

            // --- Sample Data (Replace with API calls) ---
            const sampleForumTopics = [
                { id: 'f1', title: "Problème avec boucle For en JavaScript", author: "Amina Diallo", date: "Hier", replies: 5, lastActivity: "Il y a 2h", content: "Bonjour, je n'arrive pas à faire fonctionner ma boucle for correctement. Voici mon code...", authorAvatar: 'https://via.placeholder.com/30/FF8E2A/FFFFFF?text=AD' },
                { id: 'f2', title: "Comment centrer une DIV en CSS ?", author: "David Okoye", date: "Lundi", replies: 12, lastActivity: "Il y a 5h", content: "Quelles sont les méthodes modernes pour centrer parfaitement une div horizontalement et verticalement ?", authorAvatar: 'https://via.placeholder.com/30/1EA38B/FFFFFF?text=DO' },
                { id: 'f3', title: "Besoin d'aide pour configurer Laravel", author: "Fatou Sow", date: "05/05/24", replies: 2, lastActivity: "Hier", content: "Je suis les étapes d'installation mais je bloque sur la configuration de la base de données...", authorAvatar: 'https://via.placeholder.com/30/27B371/FFFFFF?text=FS' },
            ];
             const sampleForumReplies = {
                'f1': [ { author: 'Expert AfriCode', avatar: 'https://via.placeholder.com/30/1EA38B/FFFFFF?text=EX', date: 'Il y a 1h', content: 'Peux-tu vérifier la condition de sortie de ta boucle ?' }, { author: 'David Okoye', avatar: 'https://via.placeholder.com/30/1EA38B/FFFFFF?text=DO', date: 'Il y a 30min', content: 'As-tu essayé avec `let i=0` au lieu de `var i=0` ?' } ],
                'f2': [ { author: 'Amina Diallo', avatar: 'https://via.placeholder.com/30/FF8E2A/FFFFFF?text=AD', date: 'Lundi', content: 'Flexbox est génial pour ça ! `display: flex; align-items: center; justify-content: center;` sur le parent.' } ],
                'f3': []
            };

            const sampleMessages = [
                 { id: 'm1', contactId: 'e1', contactName: "Expert AfriCode 1", avatar: 'https://via.placeholder.com/40/1EA38B/FFFFFF?text=E1', lastMessage: "Ok, je regarde votre code maintenant.", timestamp: "10:30", unread: true, online: true },
                 { id: 'm2', contactId: 'u5', contactName: "Amina Diallo", avatar: 'https://via.placeholder.com/40/FF8E2A/FFFFFF?text=AD', lastMessage: "Merci pour l'aide sur le forum !", timestamp: "Hier", unread: true, online: false },
                 { id: 'm3', contactId: 'e2', contactName: "Expert AfriCode 2", avatar: 'https://via.placeholder.com/40/1EA38B/FFFFFF?text=E2', lastMessage: "Avez-vous avancé sur le projet ?", timestamp: "Lundi", unread: false, online: true },
            ];
             const sampleChatHistory = {
                'e1': [ { type: 'received', text: "Bonjour ! Comment puis-je vous aider ?", time: '10:28' }, { type: 'sent', text: "J'ai un souci avec l'exercice sur React Hooks.", time: '10:29' }, { type: 'received', text: "Ok, je regarde votre code maintenant.", time: '10:30' } ],
                'u5': [ { type: 'received', text: "Merci pour l'aide sur le forum !", time: 'Hier 15:45' } ],
                'e2': [ { type: 'sent', text: "Bonjour, je voulais savoir si vous aviez des pistes pour l'optimisation de requêtes SQL.", time: 'Lundi 09:15' }, { type: 'received', text: "Oui, avez-vous pensé aux index ?", time: 'Lundi 09:18' }, { type: 'sent', text: "Oui, mais je ne suis pas sûr desquels créer.", time: 'Lundi 09:20' }, { type: 'received', text: "Avez-vous avancé sur le projet ?", time: 'Lundi 14:00' } ],
            };

             // --- Functions ---
            function loadForumList() {
                forumList.innerHTML = ''; // Clear placeholders
                sampleForumTopics.forEach(topic => {
                    const li = document.createElement('li');
                    li.className = 'sidebar-list-item forum-item';
                    li.dataset.topicId = topic.id;
                    li.innerHTML = `
                        <h6>${escapeHtml(topic.title)}</h6>
                        <p>Par ${escapeHtml(topic.author)} - ${topic.date} (${topic.replies} réponses)</p>
                    `;
                    li.addEventListener('click', () => displayForumTopic(topic.id));
                    forumList.appendChild(li);
                });
            }

            function loadMessageList() {
                messageList.innerHTML = ''; // Clear placeholders
                sampleMessages.forEach(msg => {
                    const li = document.createElement('li');
                    li.className = 'sidebar-list-item message-item';
                    li.dataset.conversationId = msg.contactId;
                    li.innerHTML = `
                        <img src="${msg.avatar}" alt="${escapeHtml(msg.contactName)}">
                        <div class="contact-info">
                            <span class="contact-name">${escapeHtml(msg.contactName)}</span>
                            <span class="last-message">${escapeHtml(msg.lastMessage)}</span>
                        </div>
                        <span class="timestamp">${msg.timestamp}</span>
                         ${msg.unread ? '<span class="unread-indicator"></span>' : ''}
                    `;
                    li.addEventListener('click', () => displayChat(msg.contactId));
                    messageList.appendChild(li);
                });
                 // Update unread count badge
                 const unreadCount = sampleMessages.filter(m => m.unread).length;
                 document.getElementById('unread-count').textContent = unreadCount > 0 ? unreadCount : '';
                 document.getElementById('unread-count').style.display = unreadCount > 0 ? 'inline-block' : 'none';
            }

            function displayForumTopic(topicId) {
                console.log("Displaying forum topic:", topicId);
                const topic = sampleForumTopics.find(t => t.id === topicId);
                if (!topic) return;

                // Update active state in sidebar
                updateActiveSidebarItem(forumList, topicId);

                // Show forum view, hide others
                contentPlaceholder.style.display = 'none';
                chatView.style.display = 'none';
                forumView.style.display = 'flex'; // Use flex

                // Populate forum view
                document.getElementById('forum-topic-title').textContent = topic.title;
                document.getElementById('forum-topic-meta').innerHTML = `Posté par <span class="fw-bold">${escapeHtml(topic.author)}</span> le <span class="text-muted">${topic.date}</span>`;

                 // Populate original post
                 document.getElementById('op-avatar').src = topic.authorAvatar;
                 document.getElementById('op-avatar').alt = topic.author;
                 document.getElementById('op-author').textContent = topic.author;
                 document.getElementById('op-date').textContent = topic.date; // Use original date
                 document.getElementById('op-content').textContent = topic.content;


                // Populate replies
                 const repliesContainer = document.getElementById('replies-list');
                 repliesContainer.innerHTML = ''; // Clear previous replies
                 const replies = sampleForumReplies[topicId] || [];
                 if (replies.length === 0) {
                     repliesContainer.innerHTML = '<p class="text-muted">Aucune réponse pour le moment.</p>';
                 } else {
                    replies.forEach(reply => {
                         const div = document.createElement('div');
                         div.className = 'reply-card';
                         div.innerHTML = `
                            <div class="reply-header">
                                <img src="${reply.avatar}" alt="${escapeHtml(reply.author)}">
                                <span class="author">${escapeHtml(reply.author)}</span>
                                <span class="date">${reply.date}</span>
                            </div>
                            <div class="reply-content">${escapeHtml(reply.content)}</div>
                         `;
                         repliesContainer.appendChild(div);
                    });
                 }
                 // Scroll replies to top
                 document.querySelector('.forum-replies').scrollTop = 0;
            }

            function displayChat(contactId) {
                console.log("Displaying chat with:", contactId);
                const conversation = sampleMessages.find(m => m.contactId === contactId);
                if (!conversation) return;

                 // Mark as read visually (in a real app, send API call)
                 conversation.unread = false;
                 loadMessageList(); // Reload list to remove indicator

                // Update active state in sidebar
                updateActiveSidebarItem(messageList, contactId);

                // Show chat view, hide others
                contentPlaceholder.style.display = 'none';
                forumView.style.display = 'none';
                chatView.style.display = 'flex'; // Use flex

                 // Populate chat header
                 document.getElementById('chat-partner-avatar').src = conversation.avatar;
                 document.getElementById('chat-partner-avatar').alt = conversation.contactName;
                 document.getElementById('chat-partner-name').textContent = conversation.contactName;
                 const statusElement = document.getElementById('chat-partner-status');
                 statusElement.textContent = conversation.online ? 'En ligne' : 'Hors ligne';
                 statusElement.className = `status ${conversation.online ? 'text-success' : 'text-muted'}`;


                 // Populate chat messages
                 const messagesContainer = document.getElementById('chat-messages-list');
                 messagesContainer.innerHTML = ''; // Clear previous messages
                 const history = sampleChatHistory[contactId] || [];
                 history.forEach(msg => {
                     appendChatMessage(msg.type, msg.text, msg.time);
                 });

                 // Scroll to bottom
                 messagesContainer.scrollTop = messagesContainer.scrollHeight;

                 // TODO: Associate send button logic with this specific contactId
                 document.getElementById('send-message-btn').dataset.contactId = contactId;
                 document.getElementById('chat-message-input').focus();
            }

             function appendChatMessage(type, text, time) {
                 const messagesContainer = document.getElementById('chat-messages-list');
                 const div = document.createElement('div');
                 div.className = `message ${type}`; // 'sent' or 'received'
                 div.innerHTML = `
                    ${escapeHtml(text)}
                    <span class="message-time">${time}</span>
                 `;
                 messagesContainer.appendChild(div);
                  // Scroll to bottom after adding
                 messagesContainer.scrollTop = messagesContainer.scrollHeight;
             }

             function handleSendMessage() {
                 const input = document.getElementById('chat-message-input');
                 const text = input.value.trim();
                 const contactId = document.getElementById('send-message-btn').dataset.contactId;

                 if (text && contactId) {
                     const now = new Date();
                     const timeString = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

                     // 1. Append visually
                     appendChatMessage('sent', text, timeString);

                     // 2. Clear input
                     input.value = '';
                     input.focus();

                     // 3. TODO: Send message to backend API
                     console.log(`Sending message to ${contactId}: ${text}`);

                     // 4. Simulate a reply (for demo)
                     setTimeout(() => {
                         const replyText = "Message reçu ! L'expert vous répondra bientôt.";
                         const replyTime = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                         appendChatMessage('received', replyText, replyTime);
                         // Update last message in sidebar list (simulation)
                         const convo = sampleMessages.find(m => m.contactId === contactId);
                         if(convo) {
                            convo.lastMessage = replyText;
                            convo.timestamp = replyTime;
                           // convo.unread = true; // If expert replies
                           // loadMessageList();
                         }
                     }, 1500);
                 }
             }


            function updateActiveSidebarItem(listElement, activeIdAttributeValue) {
                 const items = listElement.querySelectorAll('.sidebar-list-item');
                 items.forEach(item => {
                    item.classList.remove('active');
                    const idAttr = item.dataset.topicId || item.dataset.conversationId;
                     if (idAttr === activeIdAttributeValue) {
                         item.classList.add('active');
                     }
                 });
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
             // Tab switching logic (optional, default Bootstrap handles it, but useful for clearing content)
             forumTab.addEventListener('shown.bs.tab', function () {
                 contentPlaceholder.style.display = 'flex';
                 forumView.style.display = 'none';
                 chatView.style.display = 'none';
                 updateActiveSidebarItem(forumList, null); // Clear active state
             });
            messagesTab.addEventListener('shown.bs.tab', function () {
                contentPlaceholder.style.display = 'flex';
                forumView.style.display = 'none';
                chatView.style.display = 'none';
                 updateActiveSidebarItem(messageList, null); // Clear active state
            });

             // Send message listener
             document.getElementById('send-message-btn').addEventListener('click', handleSendMessage);
             document.getElementById('chat-message-input').addEventListener('keypress', function(e) {
                 if (e.key === 'Enter' && !e.shiftKey) { // Send on Enter, allow Shift+Enter for newline
                     e.preventDefault(); // Prevent default form submission/newline
                     handleSendMessage();
                 }
             });

             // Reply form submission (simulation)
             document.getElementById('replyForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const replyText = document.getElementById('replyText').value.trim();
                if (replyText) {
                     console.log("Submitting reply:", replyText);
                    // TODO: Append reply visually & send to backend
                     document.getElementById('replyText').value = '';
                    alert("Réponse envoyée (simulation) !");
                 }
             });

            // --- Initial Load ---
            setTimeout(() => { // Simulate loading delay
                loadForumList();
                loadMessageList();
            }, 500);

        });
    </script>


@endsection
