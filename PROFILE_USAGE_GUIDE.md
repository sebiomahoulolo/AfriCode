# GUIDE D'UTILISATION - PAGE DE PROFIL APPRENANTS AFRICODE

## 🚀 MISE EN SERVICE

### Étapes d'Activation
1. **Migration de base de données** (si pas déjà fait) :
   ```bash
   cd /home/fadel/Workspaces/AfriCode
   php artisan migrate
   ```

2. **Démarrage du serveur** :
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

3. **Accès à la page** :
   - URL : `http://localhost:8000/apprenant/profile`
   - Nécessite une connexion utilisateur avec rôle "apprenant"

## 📱 FONCTIONNALITÉS DISPONIBLES

### Onglet "Informations personnelles"
- **Modification du profil** : Nom, prénom, email, bio
- **Informations étendues** : Téléphone, date de naissance, ville, pays
- **Gestion d'avatar** : Upload, prévisualisation, suppression
- **Changement de mot de passe** : Sécurisé avec vérification

### Onglet "Activité récente"
- **Dernières leçons** : 10 dernières leçons complétées
- **Historique** : Dates et cours associés
- **Navigation** : Liens vers les cours

### Onglet "Certifications"
- **Grille de certifications** : Affichage visuel des certifications obtenues
- **Détails** : Informations sur chaque certification
- **Téléchargement** : Accès aux certificats (si disponible)

### Onglet "Sécurité"
- **2FA** : Interface pour l'authentification à deux facteurs
- **Sessions** : Gestion des sessions actives
- **Historique** : Informations de sécurité

## 🎨 PERSONNALISATION

### Variables CSS Disponibles
```css
:root {
    --africode-primary: #1EA38B;
    --africode-secondary: #27B371;
    --africode-accent: #FF8E2A;
    --africode-highlight: #E32D31;
}
```

### Modification des Couleurs
Pour personnaliser les couleurs, modifiez le fichier :
`public/css/africode-learner-theme.css`

## 🔧 CONFIGURATION

### Upload d'Images
- **Dossier** : `public/storage/profiles/`
- **Formats acceptés** : JPEG, PNG, JPG, GIF
- **Taille maximum** : 2MB
- **Avatar par défaut** : `assets/images/default-avatar.svg`

### Validation des Champs
- **Nom/Prénom** : Requis, 100 caractères max
- **Email** : Requis, unique, format valide
- **Téléphone** : Optionnel, 20 caractères max
- **Mot de passe** : 8 caractères min, confirmation requise

## 📊 DONNÉES AFFICHÉES

### Statistiques Calculées
- **Cours inscrits** : Nombre total d'inscriptions
- **Cours terminés** : Nombre de cours avec `completed_at` défini
- **Certifications** : Nombre de certificats obtenus
- **Temps d'apprentissage** : Estimation basée sur la durée des leçons

### Activités Récentes
- **Source** : Table `lesson_completions`
- **Limite** : 10 dernières activités
- **Tri** : Par date de completion décroissante

## 🛠️ MAINTENANCE

### Logs d'Erreur
Les erreurs sont loggées dans :
`storage/logs/laravel.log`

### Cache
Pour vider le cache si nécessaire :
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Assets
Les assets CSS/JS sont dans :
- `public/css/africode-learner-theme.css`
- `public/css/africode-fixes.css`

## 🔗 ROUTES UTILISÉES

### Routes Principales
- `GET /apprenant/profile` → Affichage du profil
- `PUT /apprenant/profile` → Mise à jour du profil

### Middleware Appliqué
- `auth` : Authentification requise
- `ApprenantMiddleware` : Rôle apprenant requis

## 🧪 TESTS DE FONCTIONNEMENT

### Test Manuel
1. Connexion avec un compte apprenant
2. Navigation vers `/apprenant/profile`
3. Test de chaque onglet
4. Test d'upload d'image
5. Test de modification de profil
6. Test de changement de mot de passe

### Pages de Test Créées
- `test_profile_page.html` : Test visuel du design
- `test_navigation.html` : Test des liens de navigation

## ⚠️ POINTS D'ATTENTION

### Sécurité
- ✅ Validation des uploads d'images
- ✅ Vérification du mot de passe actuel
- ✅ Protection CSRF
- ✅ Validation des données d'entrée

### Performance
- ✅ Requêtes optimisées avec `with()`
- ✅ Limitation des activités récentes
- ✅ Images redimensionnées automatiquement

### Responsive
- ✅ Breakpoints mobile/tablet/desktop
- ✅ Navigation adaptée
- ✅ Formulaires optimisés

## 📞 SUPPORT

### En cas de Problème
1. Vérifier les logs Laravel
2. Contrôler la migration de base de données
3. Vérifier les permissions des dossiers
4. Tester en mode debug (`APP_DEBUG=true`)

### Fichiers Clés à Vérifier
- `app/Http/Controllers/EtudiantController.php`
- `resources/views/apprenants/profile-complete.blade.php`
- `database/migrations/*add_profile_fields_to_users_table.php`
- `app/Models/User.php`

---

**✨ La page de profil AfriCode est maintenant prête à utiliser !**

*Guide mis à jour le 20 juin 2025*
