# ⚙️ CONFIGURATION COMPLÈTE - CAMPAGNE EMAIL NOUVEAUX COURS

## ✅ Routes ajoutées

Les routes ont été ajoutées dans `routes/web.php` :
```php
// --- Campagnes Email ---
Route::get('/email-campaigns', [AdminController::class, 'emailCampaigns'])->name('email-campaigns.index');
Route::post('/email-campaigns/{course}/send', [AdminController::class, 'sendEmailCampaign'])->name('email-campaigns.send');
```

---

## 🔧 Configuration de la Queue

### 1. Vérifier la migration des jobs

La migration existe déjà : `database/migrations/0001_01_01_000002_create_jobs_table.php`

Si les tables n'existent pas, exécutez :
```bash
php artisan migrate
```

### 2. Configurer la queue dans `.env`

Ouvrez le fichier `.env` et ajoutez/modifiez :

```env
# Queue Configuration
QUEUE_CONNECTION=database
```

**Options disponibles :**
- `database` : Utilise la base de données (recommandé pour commencer)
- `redis` : Utilise Redis (plus performant, nécessite Redis)
- `sync` : Exécution synchrone (pour les tests uniquement)

### 3. Lancer le worker de queue

**En développement (terminal séparé) :**
```bash
php artisan queue:work
```

**En production (avec Supervisor ou PM2) :**

#### Option A : Supervisor (Linux)
Créez le fichier `/etc/supervisor/conf.d/laravel-worker.conf` :
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /chemin/vers/votre/projet/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/chemin/vers/votre/projet/storage/logs/worker.log
stopwaitsecs=3600
```

Puis :
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

#### Option B : PM2 (Node.js)
Créez `ecosystem.config.js` :
```javascript
module.exports = {
  apps: [{
    name: 'laravel-queue',
    script: 'php',
    args: 'artisan queue:work --sleep=3 --tries=3',
    instances: 2,
    exec_mode: 'fork',
    watch: false,
    max_memory_restart: '1G',
    env: {
      APP_ENV: 'production'
    }
  }]
};
```

Puis :
```bash
pm2 start ecosystem.config.js
pm2 save
```

---

## 📧 Configuration Email (SMTP)

### 1. Configuration dans `.env`

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

### 2. Pour Gmail

1. Activez l'authentification à 2 facteurs
2. Générez un "Mot de passe d'application" :
   - Allez dans : https://myaccount.google.com/apppasswords
   - Sélectionnez "Mail" et "Autre (nom personnalisé)"
   - Utilisez ce mot de passe dans `MAIL_PASSWORD`

### 3. Pour autres fournisseurs

**Mailgun :**
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=votre-domaine.mailgun.org
MAILGUN_SECRET=votre-secret
MAILGUN_ENDPOINT=api.mailgun.net
```

**SendGrid :**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=votre-api-key-sendgrid
```

**SMTP personnalisé :**
```env
MAIL_MAILER=smtp
MAIL_HOST=votre-serveur-smtp.com
MAIL_PORT=587
MAIL_USERNAME=votre-username
MAIL_PASSWORD=votre-password
MAIL_ENCRYPTION=tls
```

---

## 🧪 Test de la configuration

### 1. Tester la queue

```bash
# Vérifier que les tables existent
php artisan migrate:status

# Tester un job simple
php artisan tinker
>>> \App\Jobs\SendNewCourseEmails::dispatch(\App\Models\Course::first());
```

### 2. Tester l'email

```bash
php artisan tinker
>>> Mail::raw('Test email', function($message) { $message->to('votre-email@test.com')->subject('Test'); });
```

### 3. Vérifier les logs

```bash
tail -f storage/logs/laravel.log
```

---

## 🚀 Utilisation

### Automatique
Quand un cours est publié (via formateur ou admin), la campagne se lance automatiquement.

### Manuelle via Commande
```bash
php artisan course:send-campaign {course_id}
php artisan course:send-campaign 1 --force
```

### Manuelle via Interface Admin
1. Connectez-vous en tant qu'admin
2. Allez sur `/admin/email-campaigns`
3. Sélectionnez un cours
4. Cliquez sur "Envoyer la campagne"

---

## 📊 Monitoring

### Vérifier les jobs en attente
```bash
php artisan queue:work --once
```

### Vérifier les jobs échoués
```bash
php artisan queue:failed
```

### Réessayer un job échoué
```bash
php artisan queue:retry {job_id}
php artisan queue:retry all  # Tous les jobs échoués
```

### Vider les jobs échoués
```bash
php artisan queue:flush
```

---

## 🔍 Dépannage

### Les emails ne partent pas

1. **Vérifier le worker :**
   ```bash
   ps aux | grep "queue:work"
   ```

2. **Vérifier les logs :**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Vérifier les jobs en attente :**
   ```sql
   SELECT * FROM jobs;
   ```

4. **Vérifier les jobs échoués :**
   ```bash
   php artisan queue:failed
   ```

### Erreur SMTP

1. Vérifier les credentials dans `.env`
2. Vérifier que le port n'est pas bloqué par le firewall
3. Pour Gmail, utiliser un "Mot de passe d'application"
4. Vérifier les logs : `storage/logs/laravel.log`

### Queue ne traite pas les jobs

1. Vérifier que `QUEUE_CONNECTION` est bien configuré
2. Vérifier que le worker tourne : `php artisan queue:work`
3. Vérifier les permissions sur `storage/logs/`

---

## 📝 Checklist de configuration

- [ ] Migration des jobs exécutée (`php artisan migrate`)
- [ ] `QUEUE_CONNECTION=database` dans `.env`
- [ ] Configuration SMTP dans `.env`
- [ ] Worker de queue lancé (`php artisan queue:work`)
- [ ] Routes ajoutées dans `routes/web.php`
- [ ] Méthodes ajoutées dans `AdminController.php`
- [ ] Test d'envoi réussi

---

## 🎯 Commandes utiles

```bash
# Lancer le worker
php artisan queue:work

# Lancer le worker avec plus d'options
php artisan queue:work --tries=3 --timeout=300

# Voir les jobs en attente
php artisan queue:monitor

# Nettoyer les anciens jobs
php artisan queue:prune-failed --hours=48

# Redémarrer le worker (après modification du code)
php artisan queue:restart
```

---

*Configuration créée le : {{ date('Y-m-d H:i:s') }}*

