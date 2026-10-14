# 🚀 IMPLÉMENTATION DU SYSTÈME FORMATEURS - AFRICODE

## 📋 Vue d'ensemble

Ce document décrit l'implémentation technique du Plan Stratégique Formateurs pour AfriCode.

---

## 🗄️ Base de données

### Tables créées

1. **formateur_disciplinary_actions** : Historique des actions disciplinaires
   - Avertissements, suspensions, bannissements
   - Suivi des résolutions

2. **formateur_withdrawals** : Demandes de retrait de revenus
   - Gestion des paiements aux formateurs
   - Suivi des statuts (pending, processing, completed, failed)

3. **formateur_community_events** : Événements de la communauté
   - Réunions, webinaires, ateliers
   - Suivi de participation

### Tables modifiées

1. **users** : Champs ajoutés pour les formateurs
   - `formateur_status` : candidat, actif, suspendu, inactif, banni
   - `badge_level` : bronze, argent, or, platine
   - `total_courses_created`, `total_students`, `total_earnings`
   - `average_rating`, `pending_earnings`
   - `payment_method`, `payment_account`, `withdrawal_threshold`
   - `onboarding_completed`, `onboarding_completed_at`
   - `contract_signed_at`, `contract_file_path`
   - `last_course_created_at`, `last_login_at`

2. **courses** : Champs de validation ajoutés
   - `validation_status` : draft, pending_validation, needs_revision, approved, rejected
   - `admin_notes`, `revision_notes`
   - `validated_by`, `validated_at`, `submitted_for_validation_at`
   - `quality_score`, `completion_rate`, `average_study_time`
   - `plagiarism_checked`, `plagiarism_score`
   - `last_updated_at`, `update_required`, `update_reason`

### Tables existantes utilisées

- `formateur_earnings` : Revenus des formateurs
- `formateur_badges` : Badges obtenus par les formateurs

---

## 🎯 Modèles créés

### 1. FormateurDisciplinaryAction
- Gestion des actions disciplinaires
- Méthodes : `isExpired()`, `resolve()`, `getActionLabelAttribute()`

### 2. FormateurWithdrawal
- Gestion des retraits
- Méthodes : `process()`, `complete()`, `fail()`, `getStatusLabelAttribute()`

### 3. FormateurCommunityEvent
- Gestion des événements communautaires
- Méthodes : `markAsCompleted()`, `addParticipant()`, `getEventTypeLabelAttribute()`

---

## 🎮 Contrôleurs créés

### 1. AdminFormateurController
Gestion complète des formateurs par l'admin :

**Méthodes principales** :
- `index()` : Liste des formateurs avec filtres
- `show($id)` : Détails d'un formateur
- `approve($id)` : Valider un formateur (candidat → actif)
- `reject($id)` : Rejeter un formateur
- `suspend($id)` : Suspendre un formateur (7 ou 30 jours)
- `unsuspend($id)` : Lever une suspension
- `warn($id)` : Ajouter un avertissement
- `validateCourse($courseId)` : Valider un cours
- `requestRevision($courseId)` : Demander des corrections
- `rejectCourse($courseId)` : Rejeter un cours
- `withdrawals()` : Liste des retraits
- `processWithdrawal($id)` : Traiter un retrait
- `completeWithdrawal($id)` : Compléter un retrait

### 2. FormateurEarningsController
Gestion des revenus par le formateur :

**Méthodes principales** :
- `index()` : Dashboard des revenus
- `requestWithdrawal()` : Demander un retrait
- `show($id)` : Détails d'un revenu

### 3. FormateurController (modifié)
Ajout de la méthode :
- `submitForValidation($courseId)` : Soumettre un cours pour validation

---

## 🛣️ Routes à ajouter

Ajouter dans `routes/web.php` :

```php
// Routes admin pour gestion des formateurs
Route::middleware(['auth', 'role:administrateur'])->prefix('admin/formateurs')->name('admin.formateurs.')->group(function () {
    Route::get('/', [AdminFormateurController::class, 'index'])->name('index');
    Route::get('/{id}', [AdminFormateurController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [AdminFormateurController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [AdminFormateurController::class, 'reject'])->name('reject');
    Route::post('/{id}/suspend', [AdminFormateurController::class, 'suspend'])->name('suspend');
    Route::post('/{id}/unsuspend', [AdminFormateurController::class, 'unsuspend'])->name('unsuspend');
    Route::post('/{id}/warn', [AdminFormateurController::class, 'warn'])->name('warn');
    
    // Validation des cours
    Route::post('/courses/{courseId}/validate', [AdminFormateurController::class, 'validateCourse'])->name('courses.validate');
    Route::post('/courses/{courseId}/revision', [AdminFormateurController::class, 'requestRevision'])->name('courses.revision');
    Route::post('/courses/{courseId}/reject', [AdminFormateurController::class, 'rejectCourse'])->name('courses.reject');
    
    // Retraits
    Route::get('/withdrawals', [AdminFormateurController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{id}/process', [AdminFormateurController::class, 'processWithdrawal'])->name('withdrawals.process');
    Route::post('/withdrawals/{id}/complete', [AdminFormateurController::class, 'completeWithdrawal'])->name('withdrawals.complete');
});

// Routes formateur pour revenus
Route::middleware(['auth', 'role:formateur'])->prefix('formateur/earnings')->name('formateur.earnings.')->group(function () {
    Route::get('/', [FormateurEarningsController::class, 'index'])->name('index');
    Route::post('/withdraw', [FormateurEarningsController::class, 'requestWithdrawal'])->name('withdraw');
    Route::get('/{id}', [FormateurEarningsController::class, 'show'])->name('show');
});

// Route pour soumettre un cours pour validation
Route::middleware(['auth', 'role:formateur'])->post('/formateur/courses/{courseId}/submit', [FormateurController::class, 'submitForValidation'])->name('formateur.courses.submit');
```

---

## 📊 Processus de création de cours

### 1. Création du cours
```
Formateur → Créer un cours
→ Formulaire (titre, description, prix, etc.)
→ Cours créé en statut "draft"
```

### 2. Ajout de contenu
```
→ Ajouter des modules
→ Pour chaque module : Ajouter des leçons
→ Optionnel : Ajouter des quiz
```

### 3. Soumission pour validation
```
→ Formateur clique "Soumettre pour validation"
→ Statut : draft → pending_validation
→ Admin reçoit notification
```

### 4. Validation par admin
```
Admin peut :
- Valider → approved → published (visible sur la plateforme)
- Demander corrections → needs_revision (formateur corrige)
- Rejeter → rejected (cours non publié)
```

### 5. Publication
```
Cours validé → Statut "published"
→ Visible sur la plateforme
→ Les étudiants peuvent s'inscrire
```

---

## 💰 Système de rémunération

### Calcul des revenus

Lorsqu'un étudiant s'inscrit à un cours payant :

1. **Paiement reçu** : Montant total du cours
2. **Calcul** :
   - Formateur : 77% du prix
   - AfriCode : 23% du prix
3. **Enregistrement** : Création d'un enregistrement dans `formateur_earnings`
4. **Mise à jour** : `pending_earnings` du formateur est incrémenté

### Retrait des revenus

1. **Formateur demande un retrait** :
   - Montant minimum : 25 000 FCFA
   - Vérification que `pending_earnings >= montant demandé`

2. **Création de la demande** :
   - Enregistrement dans `formateur_withdrawals`
   - Statut : `pending`
   - `pending_earnings` est décrémenté

3. **Traitement par admin** :
   - Admin traite le retrait (statut : `processing`)
   - Paiement effectué (Mobile Money, Carte, etc.)
   - Statut : `completed`
   - `total_earnings` est incrémenté

---

## 🏆 Système de badges

### Niveaux de badges

- **Bronze** : 1-5 cours créés
- **Argent** : 6-15 cours créés
- **Or** : 16-30 cours créés
- **Platine** : 30+ cours créés + Excellence pédagogique

### Attribution automatique

Lors de la création/publication d'un cours :
- Vérifier le nombre total de cours du formateur
- Mettre à jour le `badge_level` si nécessaire
- Créer un enregistrement dans `formateur_badges`

---

## ⚠️ Système disciplinaire

### Types d'actions

1. **Avertissement** : Notification, pas d'impact immédiat
2. **Suspension 7 jours** : Accès restreint, pas de création de cours
3. **Suspension 30 jours** : Accès restreint, cours non visibles
4. **Bannissement** : Compte banni, retrait des revenus

### Processus

1. **Admin crée une action disciplinaire** :
   - Type, raison, détails
   - Date d'expiration (pour suspensions)

2. **Mise à jour du statut formateur** :
   - `formateur_status` mis à jour si nécessaire

3. **Résolution** :
   - Action peut être résolue manuellement
   - Suspensions expirent automatiquement

---

## 📝 Prochaines étapes

### À implémenter

1. **Vues Blade** :
   - `admin/formateurs/index.blade.php` : Liste des formateurs
   - `admin/formateurs/show.blade.php` : Détails formateur
   - `admin/formateurs/withdrawals.blade.php` : Gestion retraits
   - `formateurs/earnings/index.blade.php` : Dashboard revenus
   - `formateurs/earnings/show.blade.php` : Détails revenu

2. **Système de badges automatique** :
   - Observer sur création de cours
   - Mise à jour automatique du badge_level

3. **Notifications** :
   - Email lors de validation/rejet de cours
   - Notification lors de suspension/avertissement
   - Notification lors de traitement de retrait

4. **Système anti-plagiat** :
   - Intégration d'une API de détection
   - Vérification automatique lors de soumission

5. **Onboarding** :
   - Module de formation obligatoire
   - Vérification de complétion avant création de cours

6. **Statistiques qualité** :
   - Calcul automatique de `quality_score`
   - Calcul de `completion_rate`
   - Calcul de `average_study_time`

---

## 🔧 Commandes Artisan utiles

```bash
# Lancer les migrations
php artisan migrate

# Créer un seeder pour les badges formateurs
php artisan make:seeder FormateurBadgeSeeder

# Créer une commande pour calculer les revenus
php artisan make:command CalculateFormateurEarnings

# Créer une commande pour mettre à jour les badges
php artisan make:command UpdateFormateurBadges
```

---

## 📚 Documentation complémentaire

- **Plan Stratégique** : `PLAN_STRATEGIQUE_FORMATEURS.md`
- **Documentation technique** : `PROJET_PRESENTATION_COMPLETE.md`

---

**Version** : 1.0  
**Date** : 2024  
**AfriCode** - Apprendre, Enseigner, Grandir ensemble 🚀

