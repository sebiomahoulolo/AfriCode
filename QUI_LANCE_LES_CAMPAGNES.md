# 👥 QUI LANCE LES CAMPAGNES EMAIL ?

## 📋 RÉSUMÉ

Les campagnes email peuvent être lancées par **3 acteurs différents** via **3 méthodes différentes** :

1. **AUTOMATIQUE** - Lors de la publication d'un cours (Formateur ou Admin)
2. **ADMIN** - Via l'interface d'administration
3. **DÉVELOPPEUR/ADMIN** - Via la ligne de commande (Artisan)

---

## 🚀 MÉTHODE 1 : AUTOMATIQUE (Lors de la publication)

### **Qui peut le faire :**
- ✅ **Formateur** (propriétaire du cours)
- ✅ **Admin** (peut publier n'importe quel cours)

### **Comment :**

#### **A. Par un Formateur**

**Fichier :** `app/Http/Controllers/FormateurController.php`
**Méthode :** `publishCourse()`
**Route :** `POST /formateur/courses/{courseId}/publish`

**Processus :**
```
1. Formateur va sur la page de gestion de son cours
2. Clique sur "Publier le cours"
3. Le cours passe en statut "published"
4. ⚡ ÉVÉNEMENT déclenché automatiquement
5. Campagne lancée automatiquement
```

**Code :**
```php
// Ligne 758-764
$course->status = 'published';
$course->published_at = now();
$course->save();

// ⚡ DÉCLENCHEMENT AUTOMATIQUE
event(new \App\Events\CoursePublished($course));
```

#### **B. Par un Admin**

**Fichier :** `app/Http/Controllers/AdminController.php`
**Méthode :** `coursesUpdate()`
**Route :** `PUT /admin/courses/{course}`

**Processus :**
```
1. Admin va sur la page d'édition d'un cours
2. Change le statut à "published"
3. Sauvegarde
4. ⚡ ÉVÉNEMENT déclenché automatiquement
5. Campagne lancée automatiquement
```

**Code :**
```php
// Ligne ~442-450
$wasPublished = $course->status === 'published';
$course->update($data);

// Si le cours vient d'être publié
if (!$wasPublished && $course->status === 'published') {
    $course->published_at = now();
    $course->save();
    event(new \App\Events\CoursePublished($course));
}
```

### **Avantages :**
- ✅ **Automatique** - Pas d'action supplémentaire
- ✅ **Immédiat** - Campagne lancée dès la publication
- ✅ **Sécurisé** - Protection cache intégrée

---

## 🎛️ MÉTHODE 2 : VIA INTERFACE ADMIN

### **Qui peut le faire :**
- ✅ **Admin uniquement**

### **Comment :**

**Fichier :** `app/Http/Controllers/AdminController.php`
**Méthode :** `sendEmailCampaign()`
**Route :** `POST /admin/email-campaigns/{course}/send`

**Processus :**
```
1. Admin se connecte
2. Va sur /admin/email-campaigns
3. Voit la liste des cours publiés
4. Clique sur "Envoyer la campagne" pour un cours
5. Campagne lancée manuellement
```

**Code :**
```php
public function sendEmailCampaign(Request $request, $courseId)
{
    $course = Course::findOrFail($courseId);
    
    if ($course->status !== 'published') {
        return back()->with('error', 'Le cours doit être publié...');
    }
    
    // ⚡ LANCEMENT MANUEL
    \App\Jobs\SendNewCourseEmails::dispatch($course);
    
    return back()->with('success', 'Campagne lancée !');
}
```

### **Quand l'utiliser :**
- Relancer une campagne pour un cours déjà publié
- Tester l'envoi d'emails
- Envoyer une campagne pour un cours publié avant l'implémentation du système

### **Avantages :**
- ✅ **Contrôle total** - L'admin décide quand envoyer
- ✅ **Interface graphique** - Facile à utiliser
- ✅ **Flexible** - Peut être lancé à tout moment

---

## 💻 MÉTHODE 3 : VIA LIGNE DE COMMANDE (Artisan)

### **Qui peut le faire :**
- ✅ **Développeur** (accès SSH/terminal)
- ✅ **Admin technique** (accès serveur)

### **Comment :**

**Fichier :** `app/Console/Commands/SendCourseEmailCampaign.php`
**Commande :** `php artisan course:send-campaign {course_id}`

**Processus :**
```bash
# Lancer une campagne pour le cours ID 5
php artisan course:send-campaign 5

# Forcer l'envoi même si déjà envoyé
php artisan course:send-campaign 5 --force
```

**Code :**
```php
public function handle()
{
    $courseId = $this->argument('course_id');
    $course = Course::find($courseId);
    
    // Vérifications...
    
    // ⚡ LANCEMENT MANUEL
    SendNewCourseEmails::dispatch($course);
    
    $this->info("✅ Campagne lancée !");
}
```

### **Quand l'utiliser :**
- Tests en développement
- Scripts automatisés (cron jobs)
- Relance après correction d'erreur
- Debugging

### **Avantages :**
- ✅ **Scriptable** - Peut être automatisé
- ✅ **Force option** - Peut dépasser le cache
- ✅ **Logs détaillés** - Voir les résultats en temps réel

---

## 📊 TABLEAU RÉCAPITULATIF

| Méthode | Qui | Quand | Automatique | Interface |
|---------|-----|-------|-------------|-----------|
| **Publication** | Formateur/Admin | Lors de la publication | ✅ Oui | Web |
| **Interface Admin** | Admin | À tout moment | ❌ Non | Web |
| **Commande Artisan** | Dev/Admin | À tout moment | ❌ Non | Terminal |

---

## 🔄 FLUX COMPLET

### **Scénario 1 : Publication normale (Automatique)**

```
FORMATEUR
  ↓
Publie son cours via l'interface
  ↓
FormateurController::publishCourse()
  ↓
event(new CoursePublished($course))
  ↓
SendNewCourseEmailCampaign (Listener)
  ↓
SendNewCourseEmails::dispatch() (Job)
  ↓
Queue Worker traite
  ↓
Emails envoyés ✅
```

### **Scénario 2 : Relance manuelle (Admin)**

```
ADMIN
  ↓
Va sur /admin/email-campaigns
  ↓
Clique "Envoyer la campagne"
  ↓
AdminController::sendEmailCampaign()
  ↓
SendNewCourseEmails::dispatch() (Job)
  ↓
Queue Worker traite
  ↓
Emails envoyés ✅
```

### **Scénario 3 : Commande Artisan (Développeur)**

```
DÉVELOPPEUR
  ↓
Ouvre le terminal
  ↓
php artisan course:send-campaign 5
  ↓
SendCourseEmailCampaign::handle()
  ↓
SendNewCourseEmails::dispatch() (Job)
  ↓
Queue Worker traite
  ↓
Emails envoyés ✅
```

---

## 🛡️ PROTECTIONS

### **Toutes les méthodes respectent :**

1. **Cache de campagne** (24h)
   - Empêche les doublons de campagne

2. **Cache utilisateur** (24h)
   - Empêche les doublons d'emails

3. **Vérification du statut**
   - Le cours doit être publié (sauf avec `--force`)

---

## 📝 EXEMPLES CONCRETS

### **Exemple 1 : Formateur publie un cours**

```
👤 Formateur "Jean Dupont"
📚 Cours "Maîtriser Bootstrap 5"
🕐 10h00

Action : Clique sur "Publier le cours"
  ↓
✅ Campagne lancée automatiquement
📧 500 emails envoyés aux apprenants
```

### **Exemple 2 : Admin relance une campagne**

```
👤 Admin "Marie Martin"
📚 Cours "Maîtriser Bootstrap 5" (déjà publié)
🕐 15h00

Action : Va sur /admin/email-campaigns
        Clique "Envoyer la campagne"
  ↓
⚠️ Cache vérifié → Campagne déjà envoyée
❌ Campagne BLOQUÉE (protection)
```

### **Exemple 3 : Développeur force l'envoi**

```
👨‍💻 Développeur
📚 Cours "Maîtriser Bootstrap 5"
🕐 16h00

Action : php artisan course:send-campaign 5 --force
  ↓
✅ Cache ignoré (--force)
📧 Nouveaux emails envoyés
```

---

## ✅ RÉSUMÉ

**Qui peut lancer les campagnes ?**

1. **Formateur** → Automatiquement en publiant son cours
2. **Admin** → Via l'interface `/admin/email-campaigns`
3. **Développeur** → Via la commande Artisan

**Toutes les méthodes :**
- ✅ Respectent le cache (sauf `--force`)
- ✅ Vérifient le statut du cours
- ✅ Utilisent la queue pour l'envoi
- ✅ Loggent les actions

---

*Document créé le : {{ date('Y-m-d H:i:s') }}*

