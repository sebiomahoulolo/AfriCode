# Configuration PayPal pour AfriCode

## Variables d'environnement requises

Ajoutez ces variables à votre fichier `.env` :

```env
# Configuration PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id_here
PAYPAL_CLIENT_SECRET=your_paypal_client_secret_here
PAYPAL_MODE=sandbox
PAYPAL_WEBHOOK_ID=your_paypal_webhook_id_here
```

## Étapes de configuration

### 1. Créer un compte PayPal Developer

1. Allez sur [https://developer.paypal.com/](https://developer.paypal.com/)
2. Connectez-vous avec votre compte PayPal
3. Créez une nouvelle application

### 2. Obtenir les clés API

1. Dans le tableau de bord PayPal Developer, sélectionnez votre application
2. Copiez le **Client ID** et le **Client Secret**
3. Ajoutez-les à votre fichier `.env`

### 3. Configuration des webhooks (optionnel)

1. Dans votre application PayPal, allez dans "Webhooks"
2. Créez un nouveau webhook avec l'URL : `https://votre-domaine.com/webhook/paypal`
3. Sélectionnez les événements : `PAYMENT.CAPTURE.COMPLETED`
4. Copiez l'ID du webhook dans votre `.env`

### 4. Mode de production

Pour passer en mode production :
1. Changez `PAYPAL_MODE=live` dans votre `.env`
2. Utilisez les clés de production de votre application PayPal

## Routes PayPal ajoutées

- `POST /payment/initiate/{course}` - Initier un paiement PayPal
- `GET /payment/paypal/success` - Succès du paiement
- `GET /payment/paypal/cancel` - Annulation du paiement
- `POST /webhook/paypal` - Webhook PayPal

## Fichiers modifiés

- `config/services.php` - Configuration PayPal
- `app/Services/PayPalService.php` - Service PayPal (nouveau)
- `app/Http/Controllers/PaymentController.php` - Contrôleur de paiement
- `app/Http/Controllers/EnrollmentController.php` - Contrôleur d'inscription
- `resources/views/payment/paypal.blade.php` - Vue de paiement PayPal (nouveau)
- `routes/web.php` - Routes PayPal

## Suppression de Stripe

Les fichiers suivants ont été supprimés/modifiés :
- `resources/views/payment/stripe.blade.php` - Supprimé
- Références Stripe supprimées des contrôleurs
- Routes Stripe remplacées par PayPal

## Test

1. Configurez les variables d'environnement
2. Testez avec un cours payant
3. Vérifiez que la redirection vers PayPal fonctionne
4. Testez le retour après paiement
