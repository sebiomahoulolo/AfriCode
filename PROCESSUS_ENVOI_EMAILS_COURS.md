# 📧 PROCESSUS D'ENVOI DES EMAILS POUR NOUVEAUX COURS

## 🔄 Vue d'ensemble du flux

```
Publication d'un cours
        ↓
Événement CoursePublished déclenché
        ↓
Listener SendNewCourseEmailCampaign écoute
        ↓
Job SendNewCourseEmails mis en queue
        ↓
Worker de queue traite le job
        ↓
Pour chaque utilisateur :
  - Vérification cache (éviter doublons)
  - Envoi email via Mailable
  - Mise en cache (24h)
        ↓
Logs et statistiques
```

---

## 📋 PROCESSUS DÉTAILLÉ

### **ÉTAPE 1 : Publication d'un cours**

**Lieu :** `FormateurController::publishCourse()` ou `AdminController::coursesUpdate()`

```php
// Le cours est publié
$course->status = 'published';
$course->published_at = now();
$course->save();

// ⚡ DÉCLENCHEMENT DE L'ÉVÉNEMENT
event(new \App\Events\CoursePublished($course));
```

**Quand cela se produit :**
- Un formateur publie son cours via le bouton "Publier le cours"
- Un admin change le statut d'un cours à "published" dans l'interface admin

---

### **ÉTAPE 2 : Événement CoursePublished**

**Fichier :** `app/Events/CoursePublished.php`

```php
class CoursePublished
{
    public function __construct(
        public Course $course  // Le cours publié
    ) {}
}
```

**Rôle :** Transmet les informations du cours publié aux listeners.

---

### **ÉTAPE 3 : Listener SendNewCourseEmailCampaign**

**Fichier :** `app/Listeners/SendNewCourseEmailCampaign.php`

**Ce qui se passe :**

1. **Vérification du statut** : S'assure que le cours est bien publié
2. **Vérification du cache** : Vérifie si une campagne a déjà été envoyée (évite les doublons)
3. **Dispatch du Job** : Met le job `SendNewCourseEmails` en queue
4. **Marquage en cache** : Marque que la campagne a été lancée (24h)

```php
public function handle(CoursePublished $event): void
{
    $course = $event->course;
    
    // Vérifications...
    
    // ⚡ MISE EN QUEUE DU JOB
    SendNewCourseEmails::dispatch($course);
    
    // Cache pour éviter les doublons
    Cache::put("course_campaign_sent_{$course->id}", true, now()->addHours(24));
}
```

**Important :** Le listener est en queue (`ShouldQueue`), donc il ne bloque pas la réponse HTTP.

---

### **ÉTAPE 4 : Job SendNewCourseEmails (Queue)**

**Fichier :** `app/Jobs/SendNewCourseEmails.php`

**Ce qui se passe :**

1. **Récupération des utilisateurs** :
   ```php
   $users = User::where('is_active', true)
       ->where('email_verified_at', '!=', null)
       ->where('role', '!=', 'admin')
       ->where('role', '!=', 'formateur')
       ->get();
   ```

2. **Pour chaque utilisateur** :
   - Vérifie le cache (évite d'envoyer 2 fois le même email)
   - Envoie l'email via `Mail::to()->send()`
   - Met en cache (24h)
   - Pause de 0.1 seconde entre chaque email

3. **Logs** : Enregistre les statistiques (envoyés, échoués, total)

```php
foreach ($users as $user) {
    $cacheKey = "course_email_sent_{$course->id}_{$user->id}";
    
    if (!Cache::has($cacheKey)) {
        // 📧 ENVOI DE L'EMAIL
        Mail::to($user->email)->send(new NewCourseNotification($course, $user));
        
        // Cache pour éviter les doublons
        Cache::put($cacheKey, true, now()->addHours(24));
        
        $sentCount++;
        
        // Pause pour ne pas surcharger SMTP
        usleep(100000); // 0.1 seconde
    }
}
```

---

### **ÉTAPE 5 : Mailable NewCourseNotification**

**Fichier :** `app/Mail/NewCourseNotification.php`

**Rôle :** Prépare l'email avec :
- Le sujet : "🎓 Nouveau cours disponible : {titre}"
- Le template : `resources/views/emails/courses/new-course.blade.php`
- Les données : cours, utilisateur, URLs

```php
public function content(): Content
{
    return new Content(
        view: 'emails.courses.new-course',
        text: 'emails.courses.new-course-text',  // Version texte
        with: [
            'course' => $this->course,
            'user' => $this->user,
            'courseUrl' => route('courses.show', $this->course->slug),
            'unsubscribeUrl' => $unsubscribeUrl,
        ],
    );
}
```

---

### **ÉTAPE 6 : Template Email**

**Fichier :** `resources/views/emails/courses/new-course.blade.php`

**Contenu :**
- Design HTML responsive
- Image de couverture du cours
- Informations du cours (titre, description, niveau, prix)
- Bouton CTA vers le cours
- Footer avec lien de désabonnement

---

## 🚀 MODES D'ENVOI

### **1. Automatique (Recommandé)**

**Déclenchement :** Lors de la publication d'un cours

**Avantages :**
- ✅ Automatique, pas d'action manuelle
- ✅ Immédiat après publication
- ✅ Pas de risque d'oubli

**Processus :**
```
Formateur publie → Événement → Listener → Job en queue → Emails envoyés
```

---

### **2. Manuelle via Commande Artisan**

**Commande :**
```bash
php artisan course:send-campaign {course_id}
php artisan course:send-campaign 1 --force  # Force même si déjà envoyé
```

**Quand l'utiliser :**
- Relancer une campagne
- Tester l'envoi
- Envoyer pour un cours déjà publié

**Processus :**
```
Commande → Job dispatch → Queue → Emails envoyés
```

---

### **3. Manuelle via Interface Admin**

**URL :** `/admin/email-campaigns`

**Processus :**
1. Admin va sur la page des campagnes
2. Sélectionne un cours
3. Clique sur "Envoyer la campagne"
4. Job dispatch → Queue → Emails envoyés

---

## ⚙️ CONFIGURATION REQUISE

### **1. Queue Worker**

**Doit être lancé :**
```bash
php artisan queue:work
```

**En production :** Utiliser Supervisor ou PM2 pour le maintenir actif.

### **2. Configuration SMTP**

**Fichier `.env` :**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@africode.com
MAIL_FROM_NAME="AfriCode"
```

### **3. Queue Connection**

**Fichier `.env` :**
```env
QUEUE_CONNECTION=database
```

---

## 🔍 FILTRES DES UTILISATEURS

Les emails sont envoyés uniquement aux utilisateurs qui :

✅ **Sont actifs** : `is_active = true`
✅ **Ont vérifié leur email** : `email_verified_at != null`
✅ **Ne sont pas admin** : `role != 'admin'`
✅ **Ne sont pas formateur** : `role != 'formateur'`

**Résultat :** Seuls les apprenants actifs et vérifiés reçoivent les emails.

---

## 🛡️ PROTECTION CONTRE LES DOUBLONS

### **Niveau 1 : Cache de campagne**
```php
$campaignKey = "course_campaign_sent_{$course->id}";
Cache::put($campaignKey, true, now()->addHours(24));
```
**Durée :** 24 heures

### **Niveau 2 : Cache par utilisateur**
```php
$cacheKey = "course_email_sent_{$course->id}_{$user->id}";
Cache::put($cacheKey, true, now()->addHours(24));
```
**Durée :** 24 heures

**Résultat :** Un utilisateur ne peut recevoir qu'un seul email par cours toutes les 24h.

---

## 📊 LOGS ET STATISTIQUES

### **Logs générés :**

1. **Lancement de campagne** :
   ```php
   Log::info("Campagne email déclenchée pour le nouveau cours", [
       'course_id' => $course->id,
       'course_title' => $course->title,
   ]);
   ```

2. **Fin de campagne** :
   ```php
   Log::info("Campagne email nouveau cours terminée", [
       'course_id' => $course->id,
       'sent' => $sentCount,
       'failed' => $failedCount,
       'total' => $users->count(),
   ]);
   ```

3. **Erreurs individuelles** :
   ```php
   Log::error("Erreur envoi email nouveau cours à {$user->email}", [
       'course_id' => $course->id,
       'user_id' => $user->id,
   ]);
   ```

**Emplacement :** `storage/logs/laravel.log`

---

## ⏱️ PERFORMANCE

### **Pause entre emails**
```php
usleep(100000); // 0.1 seconde entre chaque email
```

**Pourquoi ?**
- Évite de surcharger le serveur SMTP
- Respecte les limites du fournisseur email
- Évite d'être blacklisté

### **Temps estimé**

Pour **1000 utilisateurs** :
- 1000 emails × 0.1 seconde = **100 secondes** (~1.7 minutes)
- Plus le temps d'envoi SMTP (~0.5-2 secondes par email)

**Total estimé :** ~15-30 minutes pour 1000 utilisateurs

---

## 🔄 RETRY ET GESTION D'ERREURS

### **Tentatives**
```php
public $tries = 3;  // 3 tentatives en cas d'échec
```

### **Timeout**
```php
public $timeout = 300;  // 5 minutes maximum
```

### **Jobs échoués**
Si un job échoue après 3 tentatives :
- Enregistré dans la table `failed_jobs`
- Peut être relancé avec : `php artisan queue:retry {job_id}`

---

## 📝 EXEMPLE CONCRET

### **Scénario : Publication d'un cours**

1. **Formateur publie le cours "Maîtriser Bootstrap 5"**
   ```
   POST /formateur/courses/{id}/publish
   ```

2. **Événement déclenché**
   ```php
   event(new CoursePublished($course));
   ```

3. **Listener écoute et dispatch le job**
   ```php
   SendNewCourseEmails::dispatch($course);
   ```

4. **Worker de queue traite le job**
   ```
   [2025-11-23 15:54:42] App\Jobs\SendNewCourseEmails RUNNING
   ```

5. **Pour chaque utilisateur (ex: 500 apprenants)**
   - Email 1 → user1@example.com ✅
   - Email 2 → user2@example.com ✅
   - Email 3 → user3@example.com ❌ (erreur SMTP, loggée)
   - ...
   - Email 500 → user500@example.com ✅

6. **Résultat**
   ```
   Sent: 498
   Failed: 2
   Total: 500
   ```

---

## 🎯 RÉSUMÉ

| Étape | Fichier | Action |
|-------|---------|--------|
| 1. Publication | `FormateurController` | `event(new CoursePublished($course))` |
| 2. Événement | `CoursePublished.php` | Transmet le cours |
| 3. Listener | `SendNewCourseEmailCampaign.php` | Dispatch le job |
| 4. Job | `SendNewCourseEmails.php` | Traite les utilisateurs |
| 5. Mailable | `NewCourseNotification.php` | Prépare l'email |
| 6. Template | `new-course.blade.php` | Génère le HTML |
| 7. SMTP | Configuration `.env` | Envoie l'email |

---

## ✅ CHECKLIST DE VÉRIFICATION

Pour que le système fonctionne :

- [ ] Queue worker lancé : `php artisan queue:work`
- [ ] SMTP configuré dans `.env`
- [ ] `QUEUE_CONNECTION=database` dans `.env`
- [ ] Tables `jobs` et `failed_jobs` créées
- [ ] EventServiceProvider configuré
- [ ] Routes admin ajoutées

---

*Document créé le : {{ date('Y-m-d H:i:s') }}*

