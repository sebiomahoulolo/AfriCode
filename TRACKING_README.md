# Tracking & Pixels – AfriCode

Système de tracking unifié pour tracer le trafic, les conversions et l’attribution (réseaux sociaux, campagnes) sur tout le site.

---

## Comment le programme fonctionne

### Au chargement de chaque page

1. Le **layout** inclut `<x-tracking-pixels />` dans le `<head>`.
2. Laravel lit **config/tracking.php** (variables `.env` : TRACKING_ENABLED, GTM_ID, META_PIXEL_ID, etc.).
3. Si **TRACKING_ENABLED=false** : rien n'est injecté.
4. Si **GTM_ID** est défini : un seul script GTM est chargé ; le dataLayer et le script UTM + AfriCodeTracking sont aussi injectés.
5. Si **GTM_ID** n'est pas défini : injection de chaque pixel activé (Meta, GA4, LinkedIn, TikTok, Twitter) + dataLayer + UTM + AfriCodeTracking.

### dataLayer et UTM

- **dataLayer** : rempli avec site, page_url, page_title, environment ; tous les événements y sont poussés.
- **UTM** : à l'arrivée avec `?utm_source=...&utm_campaign=...`, les paramètres sont stockés (sessionStorage + localStorage, clé `africode_utm`) et l'événement `utm_captured` est poussé dans le dataLayer.

### Événements automatiques

- **PageView** : toute page (envoyé par chaque pixel au chargement).
- **view_content** : fiche cours `/courses/{id}` (titre, id, catégorie).
- **purchase** : page `/payment/success` (montant, devise, cours acheté).

### Événements manuels

`window.AfriCodeTracking.track('sign_up', { method: 'email' });` — envoi vers dataLayer + pixels chargés.

### GTM body

Si GTM_ID est défini, `<x-tracking-pixels-body />` ajoute l'iframe noscript GTM au début du body.

---

## Où voir les résultats du tracking ?

- **Dans l’admin AfriCode** : **Tableau de bord admin → Tracking / Analytics** (`/admin/tracking`). Cette page affiche l’état de la configuration (GTM, Meta, GA4, etc.) et des **liens directs** vers les tableaux de bord où consulter les données :
  - **Google Tag Manager** : [tagmanager.google.com](https://tagmanager.google.com/)
  - **Google Analytics 4** : [analytics.google.com](https://analytics.google.com/)
  - **Meta (Facebook/Instagram)** : Gestionnaire d’événements dans [business.facebook.com](https://business.facebook.com/events_manager2/)
  - **LinkedIn, TikTok, Twitter/X** : selon les pixels activés, liens vers les interfaces publicitaires respectives.
- Les **résultats détaillés** (trafic, conversions, rapports) sont donc dans ces plateformes externes, pas dans Laravel. La page admin Tracking sert de **raccourci et de rappel de configuration**.

---

## Liste des fichiers créés ou modifiés

| Fichier | Statut | Rôle |
|---------|--------|------|
| `config/tracking.php` | **Créé** | Config centralisée (TRACKING_ENABLED, GTM_ID, META_*, GA4_*, etc.). |
| `resources/views/components/tracking-pixels.blade.php` | **Créé** | Head : dataLayer, GTM ou pixels, UTM, AfriCodeTracking. |
| `resources/views/components/tracking-pixels-body.blade.php` | **Créé** | Body : iframe noscript GTM. |
| `resources/views/layouts/layout.blade.php` | **Modifié** | Remplace Meta Pixel par x-tracking-pixels + x-tracking-pixels-body. |
| `resources/views/layouts/app.blade.php` | **Modifié** | Idem. |
| `resources/views/index.blade.php` | **Modifié** | Suppression pixel dupliqué. |
| `resources/views/payment/success.blade.php` | **Modifié** | Envoi événement purchase. |
| `resources/views/courses/show.blade.php` | **Modifié** | Envoi événement view_content. |
| `.env.example` | **Modifié** | Variables tracking ajoutées. |
| `.env` | **Modifié** | Vos valeurs. |
| `resources/js/tracking.js` | **Créé** | Optionnel (UTM). |
| `TRACKING_README.md` | **Créé** | Cette doc. |

**Résumé : 4 créés, 7 modifiés.**

---

## 1. Ce qui est en place

- **Un seul point d’injection** : composant Blade `<x-tracking-pixels />` (head) + `<x-tracking-pixels-body />` (body pour GTM).
- **Pixels configurables** (via `.env` et `config/tracking.php`) :
  - **Meta (Facebook / Instagram)** – activé par défaut avec votre ID actuel.
  - **Google Tag Manager (GTM)** – optionnel ; si `GTM_ID` est défini, un seul script charge tout.
  - **Google Analytics 4 (GA4)** – optionnel.
  - **LinkedIn Insight Tag** – optionnel.
  - **TikTok Pixel** – optionnel.
  - **Twitter / X** – optionnel.
- **dataLayer** : initialisé avec `site`, `page_url`, `page_title`, `environment`. Tous les événements personnalisés y sont poussés.
- **UTM** : capture automatique de `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content` ; stockage en `sessionStorage` et `localStorage` ; push dans le dataLayer (`event: 'utm_captured'`) pour attribution.
- **API globale** : `window.AfriCodeTracking.track(eventName, params)` envoie l’événement vers dataLayer, Meta, TikTok, GA4 (si chargés).
- **Événements métier déjà branchés** :
  - **PageView** : sur toutes les pages (via les pixels).
  - **view_content** : sur la fiche cours (`/courses/{id}`).
  - **purchase** : sur la page de succès de paiement (`/payment/success`).

---

## 2. Configuration (.env)

Variables utiles (voir `.env.example` pour le détail) :

```env
TRACKING_ENABLED=true
META_PIXEL_ENABLED=true
META_PIXEL_ID=9650375495040301

# Optionnel
# GTM_ID=GTM-XXXXXXX
# GA4_ENABLED=true
# GA4_MEASUREMENT_ID=G-XXXXXXXXXX
# LINKEDIN_PIXEL_ENABLED=true
# LINKEDIN_PARTNER_ID=1234567
# TIKTOK_PIXEL_ENABLED=true
# TIKTOK_PIXEL_ID=XXXXXXXX
# TWITTER_PIXEL_ENABLED=true
# TWITTER_PIXEL_ID=XXXXXXXX
```

Après modification : `php artisan config:clear`.

---

## 3. Où c’est inclus

- **Layout principal** : `resources/views/layouts/layout.blade.php` (head + body).
- **Layout app** : `resources/views/layouts/app.blade.php` (head + body).
- **Page d’accueil** : `index.blade.php` n’injecte plus de pixel en double ; tout passe par le layout.

---

## 4. Utiliser le tracking dans le code

### Côté JavaScript

```javascript
// Envoyer un événement vers tous les pixels + dataLayer
if (window.AfriCodeTracking && window.AfriCodeTracking.track) {
    window.AfriCodeTracking.track('sign_up', { method: 'email' });
    window.AfriCodeTracking.track('lead', { form_name: 'contact' });
    window.AfriCodeTracking.track('contact');
}
```

### Événements Meta recommandés

- `CompleteRegistration` → mappé depuis `sign_up`
- `Purchase` → mappé depuis `purchase` (déjà utilisé sur `/payment/success`)
- `ViewContent` → mappé depuis `view_content` (déjà utilisé sur la fiche cours)
- `Lead`, `Contact`, `Search` → utilisables tels quels ou via `track('lead', …)` etc.

### Récupérer l’attribution UTM (stockée)

```javascript
var stored = window.AfriCodeTracking.getStoredUtm();
// stored => { utm: { utm_source, utm_medium, ... }, landing: url, ts: timestamp }
```

---

## 5. Fichiers concernés

| Fichier | Rôle |
|--------|------|
| `config/tracking.php` | Configuration des pixels (activés, IDs). |
| `resources/views/components/tracking-pixels.blade.php` | Injection des scripts (dataLayer, GTM ou pixels individuels, UTM, `AfriCodeTracking`). |
| `resources/views/components/tracking-pixels-body.blade.php` | GTM noscript (iframe) si `GTM_ID` est défini. |
| `resources/views/layouts/layout.blade.php` | Inclut les deux composants. |
| `resources/views/layouts/app.blade.php` | Inclut les deux composants. |
| `resources/views/index.blade.php` | Pixel dupliqué supprimé. |
| `resources/views/payment/success.blade.php` | Envoi de l’événement `purchase`. |
| `resources/views/courses/show.blade.php` | Envoi de l’événement `view_content`. |
| `.env` / `.env.example` | Variables `TRACKING_*` et `*_PIXEL_*`. |

---

## 6. Trafic et stockage

- **Trafic** : chaque outil (Meta, GA4, LinkedIn, etc.) reçoit les événements selon sa config ; le dataLayer centralise les infos pour GTM/GA4.
- **Stockage** : UTM et landing sont conservés en `sessionStorage` et `localStorage` sous la clé `africode_utm` pour l’attribution (première visite / campagne).
- **RGPD** : pensez à adapter votre bandeau consentement (ex. ne charger les pixels qu’après accord, ou documenter l’usage dans votre politique de confidentialité).

---

## 7. Messages console (Tracking Prevention, ERR_BLOCKED_BY_CLIENT)

Les messages suivants dans la console **ne sont pas des bugs** du site. Ils viennent du **navigateur** ou d’extensions (blocage du tracking) :

| Message | Cause | À faire |
|--------|--------|--------|
| **Tracking Prevention blocked access to storage for &lt;URL&gt;** | Edge / Safari / Brave bloque l’accès au stockage (localStorage/sessionStorage) pour les scripts de suivi. | Rien à corriger dans le code. Le site fonctionne ; le stockage UTM est simplement ignoré pour ces visiteurs. |
| **Tracking Prevention blocked a Script resource... connect.facebook.net/en_US/fbevents.js** | Le navigateur bloque le chargement du script Meta (Facebook) Pixel. | Normal. Pour les utilisateurs avec « Tracking Prevention » ou un bloqueur de pub, le pixel Meta ne se charge pas. |
| **Failed to load resource: net::ERR_BLOCKED_BY_CLIENT** | Extension (AdBlock, uBlock, etc.) ou protection intégrée du navigateur bloque la requête (Facebook, Google Analytics, etc.). | Normal. Aucune modification côté code ne peut débloquer ces requêtes ; c’est voulu par l’utilisateur. |
| **WebSocket connection to 'ws://localhost:8081/' failed** | Outil de dev (ex. Vite) tente de se connecter au serveur de rechargement à chaud sur votre machine. | Sans impact en production. En local, normal si le serveur Vite n’est pas lancé. |

**Résumé** : pour les visiteurs **sans** protection anti-tracking, le tracking (Meta, GA4, GTM, UTM) fonctionne. Pour les autres, les scripts ou requêtes sont bloqués et les messages s’affichent dans la console ; le reste du site continue de fonctionner. Le code a été rendu **résilient** (try/catch sur le dataLayer et le stockage) pour éviter toute exception JavaScript si le stockage est bloqué.

---

## 8. Déploiement

Copier en production les fichiers listés au § 5, définir les variables d’environnement, puis exécuter :

```bash
php artisan config:clear
php artisan view:clear
```

Vérifier que les bons IDs (Meta, GTM, etc.) sont bien renseignés dans `.env` de production.
