# Où voir les pages (Admin, Support, Chat, Paiements, Gamification)

Connecte-toi avec le bon compte selon la section. Base URL : `http://localhost` ou l’URL de ton site.

---

## 1. Admin (compte **administrateur**)

**Connexion :** `/login` → utilisateur avec rôle **administrateur**.

### Dans le menu latéral admin (sidebar)

| Page | Lien dans le menu | URL directe |
|------|-------------------|-------------|
| **Événements** (liste) | **Événements** (icône calendrier) | `/admin/events` |
| **Événements** – créer | Depuis la liste → bouton « Nouvel événement » / « Créer un événement » | `/admin/events/create` |
| **Événements** – modifier / détail | Depuis la liste (à ajouter si liste avec liens) | `/admin/events/{id}/edit`, `/admin/events/{id}` |
| **Tâches** (liste) | **Pas dans le menu** → utiliser l’URL ci‑dessous | `/admin/tasks` |
| **Tâches** – créer | Depuis `/admin/tasks` → « Nouvelle tâche » | `/admin/tasks/create` |
| **Tâches** – modifier | Depuis la liste des tâches → « Modifier » | `/admin/tasks/{id}/edit` |
| **Support (tickets)** | **Pas dans le menu** → utiliser l’URL ci‑dessous | `/admin/support/tickets` |

**URLs admin à retenir :**
- Événements : `http://localhost/admin/events` (puis créer / modifier depuis cette page).
- Tâches : `http://localhost/admin/tasks` (pas de lien dans le menu actuellement).
- Tickets support : `http://localhost/admin/support/tickets` (pas de lien dans le menu actuellement).

---

## 2. Support (utilisateur connecté)

**Connexion :** `/login` avec n’importe quel compte.

| Page | URL directe |
|------|-------------|
| Liste des tickets | `/support/tickets` |
| Créer un ticket | `/support/tickets/create` |
| Détail d’un ticket | `/support/tickets/{id}` |
| FAQ (liste) | `/support/faq` |
| FAQ (article) | `/support/faq/{id}` |

**Pas de lien dans la navbar principale** → taper par exemple : `http://localhost/support/tickets` et `http://localhost/support/faq`.

---

## 3. Chat (utilisateur connecté)

| Page | URL directe |
|------|-------------|
| Liste des conversations | `/chat` |
| Conversation avec un utilisateur | `/chat/{user_id}` |

**Pas de lien dans la navbar** → `http://localhost/chat`.

---

## 4. Paiements / gamification (utilisateur connecté)

| Page | URL directe |
|------|-------------|
| Historique des paiements | `/payment/history` |
| Succès (achievements) | `/achievements` |
| Détail d’un succès | `/achievements/{id}` |
| Classement (leaderboard) | `/leaderboard` |
| Récompenses (liste) | `/rewards` |
| Récompenses d’un utilisateur | `/users/{user_id}/rewards` |

**Pas de lien dans la navbar** → ex. `http://localhost/payment/history`, `http://localhost/achievements`, `http://localhost/leaderboard`, `http://localhost/rewards`.

---

## 5. Récapitulatif des URLs

```
# Admin (après /login avec compte administrateur)
/admin/events
/admin/events/create
/admin/events/{id}
/admin/events/{id}/edit
/admin/tasks
/admin/tasks/create
/admin/tasks/{id}/edit
/admin/support/tickets

# Support
/support/tickets
/support/tickets/create
/support/tickets/{id}
/support/faq
/support/faq/{id}

# Chat
/chat
/chat/{user_id}

# Paiements & gamification
/payment/history
/achievements
/achievements/{id}
/leaderboard
/rewards
/users/{user_id}/rewards
```

---

## 6. Liens manquants dans le menu admin

Actuellement **dans** le menu admin :
- Tableau de bord, Utilisateurs, Cours, Formateurs, Retraits, Certifications, Paiements, Statistiques, Messages, **Événements**, Créer Défi/Compétition, Paramètres.

**Pas dans le menu** (accessible uniquement en tapant l’URL) :
- **Tâches** : `/admin/tasks`
- **Support (tickets)** : `/admin/support/tickets`

Si tu veux les avoir dans le menu, il faut ajouter deux entrées dans la sidebar du layout admin (`resources/views/admin/layouts/app.blade.php`), après « Événements » par exemple.
