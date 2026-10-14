# Liste des problèmes du site AfriCode (à corriger en local)

Audit effectué sur les routes, vues, rôles, contrôleurs et fonctionnalités. À traiter en local avant déploiement.

---

## 1. Routes et middlewares

### 1.1 Middleware `isAdmin` inexistant (CRITIQUE)
- **Fichier :** `routes/web.php` (ligne ~517 et ~777)
- **Problème :** Les routes admin des compétitions utilisent `middleware(['auth', 'isAdmin'])` alors que dans `app/Http/Kernel.php` l’alias enregistré est **`admin`**, pas `isAdmin`.
- **Effet :** Erreur du type *"Unable to resolve middleware [isAdmin]"* sur `/admin/competitions/.../test-cases` et routes du groupe ligne 777.
- **Correction :** Remplacer `'isAdmin'` par `'admin'` dans ces deux groupes de routes.

### 1.2 Route formateur en double et non protégée (SÉCURITÉ)
- **Fichier :** `routes/web.php` (lignes 328 et 598-599)
- **Problème :** La route `GET /formateur/modules/{module}/gerer` est définie deux fois :
  - Dans le groupe formateur (avec auth + formateur) : `formateur.manage.module` avec paramètre `moduleId`.
  - **Hors groupe** (lignes 598-599) : `formateur.modules.manage` avec paramètre `{module}`, **sans middleware**.
- **Effet :** Accès possible à la gestion de module sans être connecté formateur.
- **Correction :** Supprimer le bloc lignes 598-599 (route en double) et s’assurer que partout on utilise `route('formateur.manage.module', ['moduleId' => $module->id])`.

### 1.3 Paramètres incorrects pour la route « Retour au module »
- **Fichier :** `resources/views/formateurs/create_quiz.blade.php` (ligne 8)
- **Problème :** `route('formateur.modules.manage', [$course, $module])` alors que la route n’attend qu’un paramètre (`module` ou `moduleId`).
- **Correction :** Utiliser `route('formateur.manage.module', ['moduleId' => $module->id])` (comme dans les autres vues formateur).

### 1.4 Incohérence sur « Forum des experts »
- **Fichier :** `routes/web.php`
- **Problème :** `/le-forum-des-experts` est enregistré deux fois : une fois vers `CoursController::forumexp` (l.451), puis une redirection vers `forum.index` (l.646). La dernière définition l’emporte, donc la première ne sert jamais. Le nom de route `pages.forumexp` est utilisé pour la route avec `{id}` (détail sujet).
- **Effet :** Confusion et risque d’appels à `route('pages.forumexp')` sans `id` (erreur de paramètres).
- **Correction :** Garder une seule définition pour `/le-forum-des-experts` (soit redirection vers `forum.index`, soit vers la vue liste) et un nom distinct pour la page détail (ex. `forum.show` ou `pages.forumexp.show`).

---

## 2. Rôles et permissions

### 2.1 Rôle admin vs administrateur (CRITIQUE)
- **Fichiers :** `app/Models/User.php` (méthode `isAdmin()`), `app/Http/Controllers/AdminController.php`, services (CourseMailService, NotificationService), `ProfileController`.
- **Problème :**
  - `User::isAdmin()` retourne `$this->role === 'administrateur'`.
  - AdminController valide le rôle avec `'required|in:admin,formateur,apprenant'` (donc **`admin`** en base).
  - Les notifications / mails cherchent les admins avec `User::where('role', 'administrateur')`.
- **Effet :** Un utilisateur créé en « admin » par l’admin ne sera pas reconnu comme admin par `isAdmin()`, ni par les notifications. Tableau de bord admin et accès admin peuvent être refusés.
- **Correction :** Choisir une seule valeur en base (`admin` ou `administrateur`) et l’utiliser partout :
  - Soit modifier `User::isAdmin()` en `return in_array($this->role, ['admin', 'administrateur']);` et continuer à créer des `admin` côté AdminController.
  - Soit passer toute la logique à `administrateur` (validation, seeders, notifications) et créer les comptes avec ce rôle.

### 2.2 Gate `manageSupport` non enregistrée
- **Fichier :** `routes/web.php` (l.560), `app/Http/Controllers/SupportController.php`
- **Problème :** Les routes admin support utilisent `middleware(['can:manageSupport'])` et le contrôleur fait `$this->authorize('manageSupport', Auth::user())`, mais aucune Gate `manageSupport` n’est définie dans `AppServiceProvider` ou `AuthServiceProvider`.
- **Effet :** Erreur 403 ou « Policy/Gate does not exist ».
- **Correction :** Dans `App\Providers\AuthServiceProvider` (ou équivalent), enregistrer une Gate du type : si `$user->isAdmin()` alors autoriser `manageSupport`.

---

## 3. Vues manquantes

### 3.1 Support (tickets et FAQ)
- **Contrôleur :** `SupportController`
- **Vues attendues mais absentes :**
  - `support.tickets.index`
  - `support.tickets.create`
  - `support.tickets.show`
  - `support.faq.index`
  - `support.faq.show`
  - `admin.support.tickets.index`
- **Effet :** Erreur « View [...] not found » dès qu’on accède au support ou à l’admin des tickets.
- **Correction :** Créer les dossiers et fichiers Blade correspondants sous `resources/views/support/` et `resources/views/admin/support/`, ou désactiver temporairement les routes / liens vers le support.

### 3.2 Admin – Événements et tâches
- **Contrôleur :** `AdminController`
- **Vues manquantes :**
  - `admin.events.create`
  - `admin.events.edit`
  - `admin.tasks.create`
  - `admin.tasks.edit`
- **Existant :** uniquement `admin/events/index.blade.php`. Aucun fichier dans `admin/tasks/`.
- **Effet :** Erreur « View not found » sur création/édition d’événements et de tâches.
- **Correction :** Créer les vues ou retourner une redirection / message « En construction » dans les méthodes concernées pour éviter l’exception.

### 3.3 Chat
- **Contrôleur :** `ChatController`
- **Vues attendues :** `chat.index`, `chat.show`
- **Problème :** Aucun fichier dans `resources/views/chat/`.
- **Effet :** Erreur « View not found » sur les pages chat.
- **Correction :** Créer les vues ou désactiver les routes/liens vers le chat.

### 3.4 Historique des paiements (utilisateur)
- **Contrôleur :** `PaymentController` → `view('payments.history', ...)`
- **Problème :** Pas de fichier `resources/views/payments/history.blade.php` (il n’y a que `admin/payments/`).
- **Effet :** Erreur sur la page « Historique des paiements » côté utilisateur.
- **Correction :** Créer `resources/views/payments/history.blade.php` ou adapter le nom de la vue (ex. réutiliser une vue apprenant existante).

### 3.5 Achievements (succès)
- **Contrôleur :** `AchievementController`
- **Vues attendues :** `achievements.index`, `achievements.show`, `achievements.leaderboard`
- **Problème :** Aucun fichier dans `resources/views/achievements/`.
- **Effet :** Erreur « View not found » sur les pages succès / leaderboard.
- **Correction :** Créer les vues ou masquer les liens vers ces pages.

### 3.6 Rewards – Vue « user-rewards »
- **Contrôleur :** `RewardController` → `view('rewards.user-rewards', ...)`
- **Problème :** Pas de fichier `resources/views/rewards/user-rewards.blade.php` (seuls `index` et `show` existent).
- **Effet :** Erreur sur la page des récompenses d’un utilisateur.
- **Correction :** Créer la vue ou rediriger vers une vue existante (ex. `rewards.index` avec filtre user).

---

## 4. Modèles et logique métier

### 4.1 Méthode `User::isStaff()` absente
- **Fichier :** `app/Http/Controllers/SupportController.php` (l.75)
- **Problème :** `Auth::user()->isStaff()` est appelé pour marquer une réponse comme « staff », mais la méthode n’existe pas sur `User`.
- **Effet :** Erreur fatale ou « method does not exist » à la création d’une réponse de ticket.
- **Correction :** Ajouter `isStaff()` sur `User` (ex. `return $this->isAdmin();` ou selon votre règle métier) ou retirer l’usage jusqu’à implémentation.

### 4.2 Statut des paiements : « completed » vs « succeeded »
- **Fichiers :** `AdminController` (dashboard) utilise `Payment::where('status', 'completed')` ; ailleurs (EnrollmentController, FormateurController, etc.) le statut utilisé est `succeeded`.
- **Effet :** Le tableau de bord admin peut afficher 0 revenu alors que les paiements sont bien en `succeeded`.
- **Correction :** Utiliser partout le même statut (recommandé : `succeeded`) ou prévoir les deux dans les requêtes (ex. `whereIn('status', ['completed', 'succeeded'])`) le temps d’unifier la base.

---

## 5. Admin – Méthodes sans retour ou vides

Dans `AdminController`, plusieurs méthodes ne retournent rien ou n’affichent pas de vue :

- `eventsStore`, `eventsShow`, `eventsUpdate`, `eventsDestroy`
- `tasksIndex`, `tasksStore`, `tasksUpdate`, `tasksDestroy`, `tasksMarkComplete`
- `notificationsIndex`

**Effet :** Réponses vides, 200 avec corps vide, ou erreurs si Laravel attend une réponse (redirect/view).

**Correction :** Pour chaque action (store/update/destroy), ajouter au minimum un `return redirect()->...->with(...)`. Pour les index/show, soit retourner une vue, soit une redirection vers une page existante ou un message « En construction ».

---

## 6. Configuration et déploiement

### 6.1 Clés FadaPay en dur
- **Fichier :** `app/Http/Controllers/EnrollmentController.php` (méthode `getFadaPayConfig()`)
- **Problème :** Des valeurs par défaut (ex. `sk_live_...`, `merchant_...`) sont codées en dur si la config n’est pas définie.
- **Effet :** Risque d’utiliser des clés de test ou incorrectes en production.
- **Correction :** Ne pas mettre de clés par défaut ; utiliser uniquement `config('services.fedapay.xxx')` et faire échouer proprement si absent (ou désactiver FadaPay si non configuré).

### 6.2 Asset Vite en production
- **Fichier :** `resources/views/layouts/app.blade.php` (l.41)
- **Problème :** Référence à `asset('build/assets/app-ClZCR8Ss.css')` (nom de fichier compilé avec hash).
- **Effet :** Après un nouveau `npm run build`, le hash change et le fichier peut être 404 si la vue n’utilise pas `@vite` ou `mix()`.
- **Correction :** Utiliser `@vite(['resources/css/app.css', 'resources/js/app.js'])` (ou l’équivalent du projet) pour que Laravel injecte les bons assets selon l’environnement.

---

## 7. Récapitulatif par priorité

| Priorité | Problème | Fichier(s) principal(aux) |
|----------|----------|----------------------------|
| Critique | Middleware `isAdmin` → utiliser `admin` | `routes/web.php` |
| Critique | Rôle admin vs administrateur | `User.php`, AdminController, services, ProfileController |
| Critique | Route formateur modules sans middleware | `routes/web.php` (l.598-599) |
| Haute | Vues support manquantes | `SupportController` + créer `resources/views/support/` |
| Haute | Gate `manageSupport` manquante | `AuthServiceProvider` ou `AppServiceProvider` |
| Haute | `User::isStaff()` manquant | `User.php`, `SupportController.php` |
| Haute | Statut paiement completed vs succeeded | `AdminController` (dashboard) |
| Moyenne | Vues admin events/tasks manquantes | `AdminController` + créer vues ou redirect |
| Moyenne | Vues chat, payments/history, achievements, rewards/user-rewards | Créer vues ou désactiver routes |
| Moyenne | Route « Retour au module » create_quiz | `formateurs/create_quiz.blade.php` |
| Moyenne | Méthodes admin vides (events, tasks, notifications) | `AdminController` |
| Basse | Double route /le-forum-des-experts et nom pages.forumexp | `routes/web.php` |
| Basse | FadaPay config par défaut | `EnrollmentController.php` |
| Basse | Asset CSS en dur | `layouts/app.blade.php` |

---

## 8. Parcours à tester après corrections

1. **Admin :** Connexion avec un compte `admin` ou `administrateur` (selon correction), dashboard (chiffres paiements), gestion utilisateurs, cours, formateurs, test-cases compétitions.
2. **Formateur :** Connexion, création cours/module/leçon/quiz, « Retour au module » depuis création quiz, accès gestion module (vérifier qu’on ne peut pas y accéder sans être formateur).
3. **Apprenant :** Inscription, paiement (Stripe/FadaPay/PayPal selon config), accès cours, leçons, quiz, certifications, historique paiements si implémenté.
4. **Support :** Création ticket, réponse (vérifier que `isStaff()` ne plante pas), admin tickets si Gate en place.
5. **Pages légales :** `/conditions-utilisation`, `/politique-confidentialite`, `/confidentialite` (vues existantes).
6. **Forum :** `/le-forum-des-experts`, `/le-forum-des-apprenants`, liste et détail des sujets.
7. **Compétitions / challenges :** Liste, détail, play, run code (selon configuration Piston).

Une fois ces points corrigés en local et les tests passés, vous pouvez déployer en toute confiance.
