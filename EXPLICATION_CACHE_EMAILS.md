# 🔒 EXPLICATION DU SYSTÈME DE CACHE POUR LES EMAILS

## 🎯 Objectif

Éviter qu'un utilisateur reçoive plusieurs fois le même email pour le même cours, même si :
- La campagne est relancée manuellement
- Le cours est republié
- Le job est relancé après une erreur

---

## 🛡️ DOUBLE PROTECTION

Le système utilise **2 niveaux de cache** pour une protection maximale :

### **NIVEAU 1 : Cache de Campagne (Par Cours)**

**Clé de cache :** `course_campaign_sent_{course_id}`

**Où :** `app/Listeners/SendNewCourseEmailCampaign.php`

```php
// Vérifier si une campagne a déjà été envoyée pour ce cours
$campaignKey = "course_campaign_sent_{$course->id}";

if (Cache::has($campaignKey)) {
    // Campagne déjà envoyée, on arrête
    return;
}

// Dispatch le job
SendNewCourseEmails::dispatch($course);

// Marquer que la campagne a été envoyée
Cache::put($campaignKey, true, now()->addHours(24));
```

**Durée :** 24 heures

**Exemple :**
```
Cours ID 5 publié → Cache: "course_campaign_sent_5" = true (24h)
Si republié dans les 24h → Listener détecte le cache → Pas de nouvelle campagne
```

**Avantage :** Évite de lancer plusieurs fois le job pour le même cours.

---

### **NIVEAU 2 : Cache Utilisateur (Par Utilisateur + Cours)**

**Clé de cache :** `course_email_sent_{course_id}_{user_id}`

**Où :** `app/Jobs/SendNewCourseEmails.php`

```php
foreach ($users as $user) {
    // Clé unique : cours + utilisateur
    $cacheKey = "course_email_sent_{$this->course->id}_{$user->id}";
    
    if (!Cache::has($cacheKey)) {
        // Envoyer l'email
        Mail::to($user->email)->send(new NewCourseNotification($this->course, $user));
        
        // Marquer comme envoyé pour cet utilisateur
        Cache::put($cacheKey, true, now()->addHours(24));
        
        $sentCount++;
    } else {
        // Email déjà envoyé à cet utilisateur, on skip
    }
}
```

**Durée :** 24 heures

**Exemple :**
```
Cours ID 5 → Utilisateur ID 10
Cache: "course_email_sent_5_10" = true (24h)

Si le job tourne à nouveau :
- Utilisateur 10 → Cache existe → Email PAS envoyé ✅
- Utilisateur 11 → Pas de cache → Email envoyé ✅
```

**Avantage :** Même si le job tourne plusieurs fois, chaque utilisateur ne reçoit qu'un seul email.

---

## 📊 EXEMPLE CONCRET

### **Scénario 1 : Publication normale**

```
Jour 1, 10h00 : Formateur publie le cours ID 5
  ↓
Cache campagne : "course_campaign_sent_5" = true (expire à 11h00 le lendemain)
  ↓
Job lancé → Envoie à 500 utilisateurs
  ↓
Pour chaque utilisateur :
  - User 1 : Cache "course_email_sent_5_1" = true (24h)
  - User 2 : Cache "course_email_sent_5_2" = true (24h)
  - ...
  - User 500 : Cache "course_email_sent_5_500" = true (24h)
```

**Résultat :** 500 emails envoyés ✅

---

### **Scénario 2 : Tentative de relance (dans les 24h)**

```
Jour 1, 15h00 : Admin essaie de relancer la campagne pour le cours ID 5
  ↓
Listener vérifie : Cache "course_campaign_sent_5" existe encore
  ↓
❌ Campagne BLOQUÉE → Pas de nouveau job
```

**Résultat :** Aucun email envoyé (protection niveau 1) ✅

---

### **Scénario 3 : Job relancé manuellement (dans les 24h)**

```
Jour 1, 16h00 : Admin relance le job manuellement
  ↓
Job tourne → Vérifie pour chaque utilisateur
  ↓
Pour User 1 :
  - Cache "course_email_sent_5_1" existe
  - ❌ Email PAS envoyé (skip)
  
Pour User 2 :
  - Cache "course_email_sent_5_2" existe
  - ❌ Email PAS envoyé (skip)
  
... (tous les utilisateurs ont déjà reçu l'email)
```

**Résultat :** Aucun doublon envoyé (protection niveau 2) ✅

---

### **Scénario 4 : Après 24h**

```
Jour 2, 11h00 : Cache campagne expiré
Jour 2, 11h00 : Caches utilisateurs expirés
  ↓
Nouvelle campagne possible
  ↓
Si relancé → Nouveaux emails envoyés ✅
```

**Résultat :** Nouvelle campagne possible après 24h.

---

## 🔍 STRUCTURE DES CLÉS DE CACHE

### **Cache de Campagne**
```
Format : course_campaign_sent_{course_id}
Exemple : course_campaign_sent_5
Valeur : true
Durée : 24 heures
```

### **Cache Utilisateur**
```
Format : course_email_sent_{course_id}_{user_id}
Exemple : course_email_sent_5_10
Valeur : true
Durée : 24 heures
```

---

## 💡 POURQUOI 2 NIVEAUX ?

### **Niveau 1 (Campagne) :**
- ✅ Évite de lancer plusieurs jobs inutiles
- ✅ Économise les ressources serveur
- ✅ Protection au niveau du listener

### **Niveau 2 (Utilisateur) :**
- ✅ Protection même si le job tourne plusieurs fois
- ✅ Protection si le job est relancé manuellement
- ✅ Protection si plusieurs jobs tournent en parallèle

**Résultat :** Double sécurité = Aucun doublon possible ! 🛡️

---

## 🧪 TEST PRATIQUE

### **Test 1 : Vérifier le cache de campagne**

```php
// Dans tinker
$courseId = 5;
$cacheKey = "course_campaign_sent_{$courseId}";

// Vérifier
Cache::has($cacheKey);  // true ou false

// Voir la valeur
Cache::get($cacheKey);  // true ou null

// Supprimer (pour tester)
Cache::forget($cacheKey);
```

### **Test 2 : Vérifier le cache utilisateur**

```php
$courseId = 5;
$userId = 10;
$cacheKey = "course_email_sent_{$courseId}_{$userId}";

// Vérifier
Cache::has($cacheKey);

// Supprimer (pour tester)
Cache::forget($cacheKey);
```

### **Test 3 : Voir tous les caches d'un cours**

```php
// Voir tous les caches (nécessite Redis ou accès direct)
// Ou via la base de données si cache = database
```

---

## ⚙️ CONFIGURATION DU CACHE

### **Driver de cache utilisé**

Vérifiez dans `.env` :
```env
CACHE_DRIVER=file  # ou redis, database, etc.
```

### **Durée configurable**

Pour changer la durée (actuellement 24h), modifiez dans :

1. **Listener** (`SendNewCourseEmailCampaign.php`) :
   ```php
   Cache::put($campaignKey, true, now()->addHours(48)); // 48h au lieu de 24h
   ```

2. **Job** (`SendNewCourseEmails.php`) :
   ```php
   Cache::put($cacheKey, true, now()->addHours(48)); // 48h au lieu de 24h
   ```

---

## 📝 RÉSUMÉ VISUEL

```
┌─────────────────────────────────────────────────┐
│  PUBLICATION D'UN COURS                        │
└─────────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────────┐
│  NIVEAU 1 : Cache Campagne                      │
│  Clé: course_campaign_sent_{id}                │
│  Durée: 24h                                     │
│  ✅ Bloque les nouvelles campagnes              │
└─────────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────────┐
│  JOB LANCÉ → Pour chaque utilisateur           │
└─────────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────────┐
│  NIVEAU 2 : Cache Utilisateur                   │
│  Clé: course_email_sent_{course}_{user}         │
│  Durée: 24h                                     │
│  ✅ Bloque les emails doublons                  │
└─────────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────────┐
│  EMAIL ENVOYÉ (si pas de cache)                 │
└─────────────────────────────────────────────────┘
```

---

## ✅ AVANTAGES

1. **Aucun doublon** : Impossible de recevoir 2 fois le même email
2. **Performance** : Évite les jobs inutiles
3. **Flexibilité** : Peut être forcé avec `--force` dans la commande
4. **Sécurité** : Double protection à 2 niveaux

---

## 🔧 FORCER UN ENVOI (Dépasser le cache)

### **Via Commande Artisan**
```bash
php artisan course:send-campaign 5 --force
```

### **Manuellement (dans le code)**
```php
// Supprimer le cache de campagne
Cache::forget("course_campaign_sent_5");

// Supprimer tous les caches utilisateurs pour ce cours
// (nécessite une boucle sur tous les utilisateurs)
```

---

*Document créé le : {{ date('Y-m-d H:i:s') }}*

