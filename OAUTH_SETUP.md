# Configuration OAuth pour AfriCode

## Problème résolu ✅

L'erreur `redirect_uri_mismatch` était causée par un double slash dans l'URI de redirection. Le problème a été corrigé en :

1. **Définissant des URIs de redirection fixes** dans la configuration
2. **Utilisant des valeurs par défaut** pour éviter les problèmes d'environnement
3. **Améliorant la gestion d'erreurs** dans le contrôleur OAuth
4. **Ajoutant une commande de test** pour valider la configuration

### Configuration actuelle validée :
- ✅ Google : `https://africode.tech/auth/google/callback`
- ✅ GitHub : `https://africode.tech/auth/github/callback`  
- ✅ LinkedIn : `https://africode.tech/auth/linkedin/callback`
- ✅ Facebook : `https://africode.tech/auth/facebook/callback`

## Configuration requise

### Variables d'environnement (.env)

```env
# URL de base de votre application (sans slash final)
APP_URL=https://africode.tech

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=https://africode.tech/auth/google/callback

# GitHub OAuth
GITHUB_CLIENT_ID=your_github_client_id_here
GITHUB_CLIENT_SECRET=your_github_client_secret_here
GITHUB_REDIRECT_URI=https://africode.tech/auth/github/callback

# LinkedIn OAuth
LINKEDIN_CLIENT_ID=your_linkedin_client_id_here
LINKEDIN_CLIENT_SECRET=your_linkedin_client_secret_here
LINKEDIN_REDIRECT_URI=https://africode.tech/auth/linkedin/callback

# Facebook OAuth (optionnel)
FACEBOOK_CLIENT_ID=your_facebook_client_id_here
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret_here
FACEBOOK_REDIRECT_URI=https://africode.tech/auth/facebook/callback
```

## Configuration des providers OAuth

### 1. Google OAuth

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Créez un nouveau projet ou sélectionnez un projet existant
3. Activez l'API Google+ ou Google Identity
4. Créez des identifiants OAuth 2.0
5. Ajoutez ces URIs de redirection autorisées :
   - `https://africode.tech/auth/google/callback`
   - `http://localhost:8000/auth/google/callback` (pour le développement)

### 2. GitHub OAuth

1. Allez sur [GitHub Developer Settings](https://github.com/settings/developers)
2. Créez une nouvelle OAuth App ou modifiez l'existante
3. Définissez l'URL d'accueil : `https://africode.tech`
4. **IMPORTANT** : Définissez l'URI de redirection : `https://africode.tech/auth/github/callback`
   - ❌ **Ne pas utiliser** : `/callback/github` ou `http://localhost/callback/github`
   - ✅ **Utiliser** : `https://africode.tech/auth/github/callback`

### 3. LinkedIn OAuth

1. Allez sur [LinkedIn Developer Portal](https://www.linkedin.com/developers/)
2. Créez une nouvelle application ou modifiez l'existante
3. Dans les paramètres OAuth 2.0, ajoutez :
   - `https://africode.tech/auth/linkedin/callback`
4. **IMPORTANT** : Assurez-vous que l'URI correspond exactement à celle configurée

### 4. Facebook OAuth (optionnel)

1. Allez sur [Facebook Developers](https://developers.facebook.com/)
2. Créez une nouvelle application
3. Ajoutez le produit Facebook Login
4. Dans les paramètres, ajoutez l'URI de redirection :
   - `https://africode.tech/auth/facebook/callback`

## URIs de redirection correctes

Après les corrections, les URIs de redirection sont maintenant :
- Google : `https://africode.tech/auth/google/callback`
- GitHub : `https://africode.tech/auth/github/callback`
- LinkedIn : `https://africode.tech/auth/linkedin/callback`
- Facebook : `https://africode.tech/auth/facebook/callback`

## Test de la configuration

1. **Testez la configuration** avec la commande : `php artisan oauth:test-config`
2. **Videz le cache** si nécessaire : `php artisan config:clear`
3. **Testez chaque provider** de connexion sur votre site
4. **Vérifiez les logs** en cas d'erreur : `storage/logs/laravel.log`

### Commande de test disponible :
```bash
php artisan oauth:test-config
```

Cette commande vérifie :
- ✅ Présence des CLIENT_ID et CLIENT_SECRET
- ✅ Validité des URIs de redirection
- ✅ Absence de doubles slashes
- ✅ Configuration cohérente

## Dépannage

### Erreur "redirect_uri_mismatch"
- Vérifiez que l'URI de redirection dans votre provider OAuth correspond exactement à celle configurée
- Assurez-vous qu'il n'y a pas de slash final dans `APP_URL`

### Erreur "Invalid client"
- Vérifiez que les `CLIENT_ID` et `CLIENT_SECRET` sont corrects
- Assurez-vous que l'application OAuth est activée

### Erreur "Access denied"
- L'utilisateur a annulé l'autorisation
- Vérifiez les permissions demandées dans votre application OAuth
