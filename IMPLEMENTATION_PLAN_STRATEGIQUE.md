# 🚀 IMPLÉMENTATION DU PLAN STRATÉGIQUE FORMATEURS

## ✅ ÉTAPES COMPLÉTÉES

### 1. ✅ Document de plan stratégique
- Fichier créé : `PLAN_STRATEGIQUE_FORMATEURS.md`
- Contenu complet avec toutes les sections

### 2. ✅ Migrations créées
- `add_formateur_fields_to_users_table.php` - Champs formateur dans users
- `add_validation_fields_to_courses_table.php` - Champs validation dans courses
- `create_formateur_badges_table.php` - Table badges
- `create_course_validations_table.php` - Table validations
- `create_formateur_earnings_table.php` - Table rémunérations
- `create_course_quality_ratings_table.php` - Table notes qualité

### 3. ✅ Modèles créés
- `FormateurBadge.php`
- `CourseValidation.php`
- `FormateurEarning.php`
- `CourseQualityRating.php`

---

## 🔄 ÉTAPES À COMPLÉTER

### 1. Compléter les modèles

#### FormateurBadge.php
```php
protected $fillable = ['formateur_id', 'badge_type', 'badge_name', 'description', 'icon', 'criteria', 'earned_at'];
protected $casts = ['criteria' => 'array', 'earned_at' => 'datetime'];
public function formateur() { return $this->belongsTo(User::class, 'formateur_id'); }
```

#### CourseValidation.php
```php
protected $fillable = ['course_id', 'validator_id', 'status', 'clarity_check', 'structure_check', 'quality_check', 'technical_check', 'plagiarism_check', 'pedagogy_check', 'pedagogical_notes', 'technical_notes', 'general_notes', 'revision_notes', 'pedagogical_score', 'technical_score', 'overall_score', 'validated_at'];
protected $casts = ['validated_at' => 'datetime'];
public function course() { return $this->belongsTo(Course::class); }
public function validator() { return $this->belongsTo(User::class, 'validator_id'); }
```

#### FormateurEarning.php
```php
protected $fillable = ['formateur_id', 'course_id', 'enrollment_id', 'course_price', 'formateur_share', 'africode_share', 'bonus_amount', 'student_count', 'total_earnings', 'payment_status', 'payment_method', 'payment_reference', 'paid_at', 'period_start', 'period_end'];
protected $casts = ['paid_at' => 'datetime', 'period_start' => 'date', 'period_end' => 'date'];
public function formateur() { return $this->belongsTo(User::class, 'formateur_id'); }
public function course() { return $this->belongsTo(Course::class); }
```

#### CourseQualityRating.php
```php
protected $fillable = ['course_id', 'rated_by', 'overall_rating', 'clarity_rating', 'structure_rating', 'quality_rating', 'pedagogy_rating', 'content_rating', 'completion_rate', 'average_study_time', 'student_satisfaction', 'quiz_pass_rate', 'strengths', 'weaknesses', 'recommendations', 'action_required', 'action_notes', 'rated_at'];
protected $casts = ['rated_at' => 'datetime'];
public function course() { return $this->belongsTo(Course::class); }
public function rater() { return $this->belongsTo(User::class, 'rated_by'); }
```

### 2. Modifier FormateurController

#### Modifier publishCourse() → submitForValidation()
```php
public function submitForValidation($courseId)
{
    // Vérifications...
    // Changer le statut à 'pending_validation'
    $course->validation_status = 'pending_validation';
    $course->submitted_for_validation_at = now();
    $course->save();
    
    // Créer une entrée dans course_validations
    CourseValidation::create([
        'course_id' => $course->id,
        'status' => 'pending',
        // ...
    ]);
    
    // Notification admin
    // ...
}
```

### 3. Créer AdminFormateurController

#### Méthodes nécessaires :
- `index()` - Liste des formateurs
- `show($id)` - Détails d'un formateur
- `activate($id)` - Activer un formateur
- `suspend($id)` - Suspendre un formateur
- `ban($id)` - Bannir un formateur
- `updateBadge($id)` - Mettre à jour le badge
- `earnings($id)` - Voir les rémunérations
- `withdrawals()` - Gérer les retraits

### 4. Créer AdminCourseValidationController

#### Méthodes nécessaires :
- `pending()` - Cours en attente de validation
- `validate($id)` - Valider un cours
- `reject($id)` - Rejeter un cours
- `requestRevision($id)` - Demander des corrections
- `show($id)` - Détails de validation

### 5. Créer FormateurEarningController

#### Méthodes nécessaires :
- `index()` - Historique des gains
- `requestWithdrawal()` - Demander un retrait
- `calculateEarnings()` - Calculer les gains (77% formateur, 23% AfriCode)
- `applyBonuses()` - Appliquer les bonus (200+, 500+, 1000+ étudiants)

### 6. Créer les vues

#### Admin :
- `admin/formateurs/index.blade.php` - Liste des formateurs
- `admin/formateurs/show.blade.php` - Détails formateur
- `admin/formateurs/earnings.blade.php` - Rémunérations
- `admin/courses/validations/pending.blade.php` - Cours en attente
- `admin/courses/validations/show.blade.php` - Détails validation
- `admin/courses/validations/validate.blade.php` - Formulaire de validation

#### Formateur :
- `formateurs/courses/submit.blade.php` - Soumettre pour validation
- `formateurs/earnings/index.blade.php` - Mes gains
- `formateurs/earnings/withdraw.blade.php` - Demander un retrait

### 7. Ajouter les routes

```php
// Admin formateurs
Route::prefix('admin/formateurs')->group(function() {
    Route::get('/', [AdminFormateurController::class, 'index']);
    Route::get('/{id}', [AdminFormateurController::class, 'show']);
    Route::post('/{id}/activate', [AdminFormateurController::class, 'activate']);
    Route::post('/{id}/suspend', [AdminFormateurController::class, 'suspend']);
    Route::post('/{id}/ban', [AdminFormateurController::class, 'ban']);
    Route::post('/{id}/badge', [AdminFormateurController::class, 'updateBadge']);
    Route::get('/{id}/earnings', [AdminFormateurController::class, 'earnings']);
});

// Admin validations
Route::prefix('admin/courses/validations')->group(function() {
    Route::get('/pending', [AdminCourseValidationController::class, 'pending']);
    Route::get('/{id}', [AdminCourseValidationController::class, 'show']);
    Route::post('/{id}/validate', [AdminCourseValidationController::class, 'validate']);
    Route::post('/{id}/reject', [AdminCourseValidationController::class, 'reject']);
    Route::post('/{id}/revision', [AdminCourseValidationController::class, 'requestRevision']);
});

// Formateur earnings
Route::prefix('formateur/earnings')->middleware('auth')->group(function() {
    Route::get('/', [FormateurEarningController::class, 'index']);
    Route::post('/withdraw', [FormateurEarningController::class, 'requestWithdrawal']);
});
```

### 8. Services à créer

#### EarningService.php
- `calculateFormateurShare($price)` - Calculer 77%
- `calculateAfricodeShare($price)` - Calculer 23%
- `calculateBonus($studentCount)` - Calculer bonus
- `processWithdrawal($formateurId, $amount)` - Traiter retrait

#### ValidationService.php
- `submitForValidation($course)` - Soumettre pour validation
- `validateCourse($course, $validator, $scores)` - Valider un cours
- `requestRevision($course, $notes)` - Demander corrections
- `checkPlagiarism($course)` - Vérifier plagiat

#### BadgeService.php
- `updateBadge($formateur)` - Mettre à jour badge selon nombre de cours
- `awardBadge($formateur, $badgeType)` - Attribuer un badge

### 9. Événements à créer

- `CourseSubmittedForValidation` - Cours soumis
- `CourseValidated` - Cours validé
- `CourseRevisionRequested` - Corrections demandées
- `FormateurBadgeUpdated` - Badge mis à jour
- `WithdrawalRequested` - Retrait demandé

### 10. Jobs/Queues

- `ProcessEarnings` - Calculer les gains quotidiennement
- `CheckPlagiarism` - Vérifier plagiat en arrière-plan
- `UpdateBadges` - Mettre à jour badges automatiquement
- `SendValidationNotification` - Notifier admin de nouvelle soumission

---

## 📋 CHECKLIST D'IMPLÉMENTATION

### Phase 1 : Base de données ✅
- [x] Migrations créées
- [ ] Migrations exécutées (`php artisan migrate`)
- [ ] Modèles complétés
- [ ] Relations définies

### Phase 2 : Logique métier
- [ ] Services créés
- [ ] Contrôleurs créés
- [ ] Méthodes implémentées
- [ ] Validation des données

### Phase 3 : Interface utilisateur
- [ ] Vues admin créées
- [ ] Vues formateur créées
- [ ] Formulaires de validation
- [ ] Tableaux de bord

### Phase 4 : Intégration
- [ ] Routes ajoutées
- [ ] Middleware configuré
- [ ] Événements configurés
- [ ] Notifications configurées

### Phase 5 : Tests
- [ ] Tests unitaires
- [ ] Tests fonctionnels
- [ ] Tests d'intégration

---

## 🎯 PRIORITÉS

1. **URGENT** : Système de validation de cours (admin doit valider avant publication)
2. **IMPORTANT** : Système de rémunération (77/23)
3. **IMPORTANT** : Gestion admin des formateurs
4. **MOYEN** : Système de badges
5. **MOYEN** : Système de qualité/notation
6. **FAIBLE** : Anti-plagiat automatique (peut être manuel au début)

---

## 📝 NOTES

- Le statut `pending_approval` dans courses doit être remplacé par `pending_validation`
- Les formateurs ne peuvent plus publier directement, ils doivent soumettre pour validation
- Le calcul des gains doit se faire automatiquement à chaque inscription
- Les badges sont mis à jour automatiquement selon le nombre de cours créés

---

**Date de création** : 24 Novembre 2025
**Dernière mise à jour** : 24 Novembre 2025

