# 📧 SYSTÈME DE CAMPAGNE EMAIL POUR NOUVEAUX COURS

## ✅ Fichiers créés

### 1. **Mailable** - `app/Mail/NewCourseNotification.php`
Classe Mailable pour envoyer les emails de nouveaux cours.

### 2. **Job** - `app/Jobs/SendNewCourseEmails.php`
Job en queue pour envoyer les emails en masse de manière asynchrone.

### 3. **Event** - `app/Events/CoursePublished.php`
Événement déclenché lorsqu'un cours est publié.

### 4. **Listener** - `app/Listeners/SendNewCourseEmailCampaign.php`
Listener qui écoute l'événement `CoursePublished` et lance la campagne.

### 5. **Commande Artisan** - `app/Console/Commands/SendCourseEmailCampaign.php`
Commande pour lancer manuellement une campagne.

### 6. **Template Email** - `resources/views/emails/courses/new-course.blade.php`
Template HTML professionnel et responsive pour les emails.

---

## 🔧 Modifications nécessaires

### 1. **EventServiceProvider** ✅ (Déjà fait)
Le listener est enregistré dans `app/Providers/EventServiceProvider.php`.

### 2. **FormateurController** ✅ (Déjà fait)
La méthode `publishCourse()` déclenche l'événement `CoursePublished`.

### 3. **AdminController** ⚠️ (À ajouter)

Ajoutez ces méthodes dans `app/Http/Controllers/AdminController.php` :

```php
/**
 * Afficher la page de gestion des campagnes email
 */
public function emailCampaigns()
{
    $courses = Course::where('status', 'published')
        ->orderBy('published_at', 'desc')
        ->with('formateur')
        ->paginate(20);
    
    return view('admin.email-campaigns.index', compact('courses'));
}

/**
 * Lancer une campagne email pour un cours
 */
public function sendEmailCampaign(Request $request, $courseId)
{
    $course = Course::findOrFail($courseId);
    
    if ($course->status !== 'published') {
        return back()->with('error', 'Le cours doit être publié pour envoyer une campagne.');
    }
    
    try {
        \App\Jobs\SendNewCourseEmails::dispatch($course);
        
        return back()->with('success', 'Campagne d\'email lancée avec succès ! Les emails seront envoyés via la queue.');
    } catch (\Exception $e) {
        return back()->with('error', 'Erreur lors du lancement de la campagne: ' . $e->getMessage());
    }
}
```

### 4. **Routes** (À ajouter dans `routes/web.php`)

Dans le groupe de routes admin, ajoutez :

```php
// Campagnes email
Route::get('/email-campaigns', [AdminController::class, 'emailCampaigns'])->name('email-campaigns.index');
Route::post('/email-campaigns/{course}/send', [AdminController::class, 'sendEmailCampaign'])->name('email-campaigns.send');
```

---

## 🚀 Utilisation

### **Automatique** (Lors de la publication)
Quand un formateur ou admin publie un cours, la campagne est automatiquement déclenchée.

### **Manuelle via Commande Artisan**
```bash
php artisan course:send-campaign {course_id}
php artisan course:send-campaign 1 --force  # Forcer même si déjà envoyé
```

### **Manuelle via Interface Admin**
1. Aller sur `/admin/email-campaigns`
2. Sélectionner un cours
3. Cliquer sur "Envoyer la campagne"

---

## ⚙️ Configuration

### **Queue**
Assurez-vous que la queue est configurée dans `.env` :
```env
QUEUE_CONNECTION=database
# ou
QUEUE_CONNECTION=redis
```

Puis créez la table des jobs :
```bash
php artisan queue:table
php artisan migrate
```

Lancez le worker :
```bash
php artisan queue:work
```

### **Email**
Vérifiez la configuration SMTP dans `.env` :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@africode.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 📊 Fonctionnalités

✅ **Envoi automatique** lors de la publication d'un cours
✅ **Queue asynchrone** pour ne pas bloquer l'application
✅ **Protection contre les doublons** (cache 24h)
✅ **Filtrage des utilisateurs** (actifs, vérifiés, non-admin/formateur)
✅ **Template professionnel** et responsive
✅ **Logs détaillés** pour le suivi
✅ **Commande Artisan** pour les campagnes manuelles
✅ **Interface admin** (à créer)

---

## 🎨 Template Email

Le template inclut :
- Design moderne et responsive
- Image de couverture du cours
- Informations du cours (titre, description, niveau, prix)
- Bouton CTA vers le cours
- Lien de désabonnement
- Footer avec informations de contact

---

## 📝 Notes importantes

1. **Performance** : Les emails sont envoyés via la queue pour éviter de surcharger le serveur
2. **Limite** : Une pause de 0.1 seconde entre chaque email pour éviter la surcharge SMTP
3. **Cache** : Les campagnes sont mises en cache pour éviter les doublons (24h)
4. **Logs** : Tous les envois sont loggés pour le suivi
5. **Erreurs** : Les erreurs sont capturées et loggées sans interrompre le processus

---

## 🔍 Dépannage

### Les emails ne partent pas
1. Vérifiez que le worker de queue tourne : `php artisan queue:work`
2. Vérifiez les logs : `storage/logs/laravel.log`
3. Vérifiez la configuration SMTP dans `.env`

### Campagne déjà envoyée
Utilisez `--force` dans la commande Artisan ou supprimez le cache :
```php
Cache::forget("course_campaign_sent_{$courseId}");
```

---

*Système créé le : {{ date('Y-m-d H:i:s') }}*

