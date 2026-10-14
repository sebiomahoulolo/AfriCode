# Configuration des clés PayPal

## 🔑 Où mettre vos vraies clés PayPal

Dans le fichier `app/Services/PayPalService.php`, remplacez ces lignes :

```php
// Clés PayPal directement dans le code
$this->clientId = 'your_paypal_client_id_here';
$this->clientSecret = 'your_paypal_client_secret_here';
$this->mode = 'sandbox'; // 'sandbox' ou 'live'
```

Par vos vraies clés :

```php
// Clés PayPal directement dans le code
$this->clientId = 'VOTRE_VRAI_CLIENT_ID_PAYPAL';
$this->clientSecret = 'VOTRE_VRAIE_CLIENT_SECRET_PAYPAL';
$this->mode = 'sandbox'; // Changez en 'live' pour la production
```

## 🚀 Comment obtenir vos clés PayPal

1. **Allez sur** [https://developer.paypal.com/](https://developer.paypal.com/)
2. **Connectez-vous** avec votre compte PayPal
3. **Créez une nouvelle application** :
   - Nom : "AfriCode"
   - Type : "Web Application"
4. **Copiez les clés** :
   - Client ID
   - Client Secret
5. **Collez-les** dans le fichier `PayPalService.php`

## ✅ Test

Maintenant, quand vous allez sur `http://127.0.0.1:8000/enrollment/8`, vous devriez voir :

- ✅ **Option PayPal** (au lieu de Stripe)
- ✅ **Option Mobile Money** (FadaPay)
- ✅ **Bouton "Payer avec PayPal"** quand vous sélectionnez PayPal

## 🔄 Flux de paiement

1. **Sélectionnez PayPal** sur la page d'inscription
2. **Cliquez sur "Payer avec PayPal"**
3. **Redirection vers PayPal** pour le paiement
4. **Retour sur votre site** après paiement
5. **Inscription automatique** au cours

## 🛠️ Mode Production

Pour passer en production :
1. Changez `$this->mode = 'live';` dans `PayPalService.php`
2. Utilisez les clés de production de PayPal
3. Configurez les webhooks PayPal si nécessaire
