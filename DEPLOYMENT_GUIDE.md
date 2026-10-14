# 🚀 Guide de Déploiement AfriCode

## Problèmes courants avec les tableaux de bord

### 1. **Assets non compilés**
```bash
# Compiler les assets
npm install
npm run build

# Ou utiliser le script fourni
chmod +x compile-assets.sh
./compile-assets.sh
```

### 2. **Permissions incorrectes**
```bash
# Permissions pour Laravel
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public/build

# Propriétaire (si nécessaire)
chown -R www-data:www-data storage bootstrap/cache public/build
```

### 3. **Cache Laravel**
```bash
# Vider tous les caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. **Base de données**
```bash
# Migrations
php artisan migrate

# Seeders (si nécessaire)
php artisan db:seed

# Vérifier la connexion
php artisan tinker
>>> DB::connection()->getPdo();
```

### 5. **Variables d'environnement**
Vérifiez votre fichier `.env` :
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=your_host
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. **Diagnostic automatique**
```bash
# Exécuter le script de diagnostic
php diagnostic-dashboard.php
```

## 🔧 Checklist de déploiement

- [ ] Assets compilés (`npm run build`)
- [ ] Permissions correctes sur `storage/` et `bootstrap/cache/`
- [ ] Base de données migrée (`php artisan migrate`)
- [ ] Variables d'environnement configurées
- [ ] Caches vidés et optimisés
- [ ] Serveur web configuré pour pointer vers `public/`
- [ ] SSL configuré (recommandé)
- [ ] Sauvegarde de la base de données

## 🚨 Solutions d'urgence

### Si les tableaux de bord ne s'affichent pas :

1. **Vérifiez les logs** :
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Testez les routes** :
   ```bash
   php artisan route:list | grep dashboard
   ```

3. **Vérifiez les vues** :
   ```bash
   php artisan view:clear
   ```

4. **Redémarrez le serveur web** :
   ```bash
   sudo systemctl restart nginx  # ou apache2
   ```

## 📞 Support

Si les problèmes persistent :
1. Exécutez `diagnostic-dashboard.php`
2. Vérifiez les logs d'erreur
3. Testez en mode debug temporairement
4. Contactez l'équipe de développement
