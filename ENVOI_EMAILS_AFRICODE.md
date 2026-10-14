# Système d'envoi des e-mails – AfriCode

## Vue d'ensemble

**Tous les e-mails** du projet passent par le **même mécanisme : PHPMailer**, avec la même configuration SMTP (`.env`).

| Service | Rôle |
|---------|------|
| **NewsletterMailService** | Inscription newsletter → e-mail de bienvenue |
| **ContactMailService** | Formulaire contact → e-mail admin + confirmation visiteur |
| **RegistrationMailService** | Après vérification e-mail → e-mail de bienvenue |
| **CourseMailService** | Campagne « Nouveau cours » (apprenants) + notification formateur (cours validé et publié) |

Configuration : `config('mail.mailers.smtp.*')` et `config('mail.from')`. Une seule config SMTP pour tout.

---

## 1. Configuration requise (`.env`)

```env
# Mail (obligatoire pour envoi réel)
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@example.com
MAIL_PASSWORD=mot-de-passe-ou-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@africode.com"
MAIL_FROM_NAME="AfriCode"

# File d'attente (pour la campagne "nouveau cours")
QUEUE_CONNECTION=database
```

- **En local / tests** : configurez un SMTP (ex. Mailpit avec `MAIL_ENCRYPTION=` vide) ; sans SMTP valide, les envois échouent et sont logués.
- **En production** : utilisez un SMTP réel (Gmail, SendGrid, Mailgun, etc.) avec `MAIL_ENCRYPTION=tls` (ou `ssl`).

---

## 2. Où sont envoyés les e-mails ?

### 2.1 Newsletter (PHPMailer – `NewsletterMailService`)

- **Déclencheur** : inscription à la newsletter (controller `NewsletterController@subscribe`).
- **Service** : `App\Services\NewsletterMailService`.
- **Contenu** : e-mail de bienvenue avec lien de désabonnement.
- **Config** : `config('mail.mailers.smtp.*')` et `config('mail.from')`.

### 2.2 Formulaire de contact (PHPMailer – `ContactMailService`)

- **Déclencheur** : envoi du formulaire de contact (`ContactController@submit`).
- **Service** : `App\Services\ContactMailService`.
- **Envois** :
  1. E-mail à **africodesm@gmail.com** (message du visiteur).
  2. E-mail de **confirmation** à l’adresse du visiteur.
- Optionnel : inscription à la newsletter via `subscribeToNewsletter()` (même service).

### 2.3 Bienvenue après vérification d’e-mail (PHPMailer – `RegistrationMailService`)

- **Déclencheur** : événement Laravel `Illuminate\Auth\Events\Verified` (utilisateur a cliqué sur le lien de vérification).
- **Listener** : `App\Listeners\SendWelcomeEmailAfterVerification` → appelle `RegistrationMailService::sendWelcomeEmail($user)`.
- **Service** : `App\Services\RegistrationMailService`.
- **Contenu** : e-mail de bienvenue (lien tableau de bord, conseils, etc.).

### 2.4 Campagne « Nouveau cours » (PHPMailer – `CourseMailService` + file d’attente)

- **Déclencheur** : **uniquement quand l’admin publie un cours** (validation d’un cours formateur dans AdminFormateurController, ou création/édition avec statut « publié » dans AdminController). Événement `App\Events\CoursePublished`.
- **Listener** : `App\Listeners\SendNewCourseEmailCampaign` (en file d’attente) → dispatch le job `SendNewCourseEmails`.
- **Job** : `App\Jobs\SendNewCourseEmails` :
  - **Destinataires** : tous les **apprenants** (`role = apprenant`).
  - Envoi via **PHPMailer** : `CourseMailService::sendNewCourseNotification($course, $user)`.
- **Service** : `App\Services\CourseMailService`.
- **Vues** : `resources/views/emails/courses/new-course.blade.php` (HTML), `new-course-text.blade.php` (texte).
- **Cache** : évite d’envoyer deux fois le même e-mail pour le même cours/utilisateur (24 h).
- **Important** : le job est **queued** (`ShouldQueue`). Il faut qu’un worker tourne pour que les e-mails partent :
  ```bash
  php artisan queue:work
  ```
  Avec `QUEUE_CONNECTION=database`, les jobs sont dans la table `jobs`.

### 2.5 Notification formateur – cours validé et publié (PHPMailer – `CourseMailService`)

- **Déclencheur** : même événement `CoursePublished` (quand l’admin valide et publie un cours).
- **Listener** : `App\Listeners\NotifyFormateurCoursePublished` → appelle `CourseMailService::sendCourseValidatedToFormateur($course)`.
- **Destinataire** : le **formateur** du cours (`course->formateur`), s’il existe.
- **Contenu** : message pro et simple indiquant que le cours est validé et publié, avec liens « Voir et gérer le cours » et « Tableau de bord ».
- **Vues** : `resources/views/emails/formateur/course-validated.blade.php`, `course-validated-text.blade.php`.

### 2.6 Notification formateur – étudiant a payé sa formation (PHPMailer – `CourseMailService`)

- **Déclencheur** : événement `App\Events\PaymentSucceeded` (lorsqu’un paiement est confirmé : Stripe ou FadaPay).
- **Listener** : `App\Listeners\NotifyFormateurStudentPaid` → appelle `CourseMailService::sendStudentPaidToFormateur($enrollment, $payment)`.
- **Destinataire** : le **formateur** du cours acheté (`course->formateur`). Un seul email par paiement (cache 24 h).
- **Contenu** : message pro et simple (formation, nom de l’étudiant, montant) avec liens « Voir les étudiants de ce cours » et « Tableau de bord ». Uniquement pour les formations payantes (montant > 0).
- **Vues** : `resources/views/emails/formateur/student-paid.blade.php`, `student-paid-text.blade.php`.

---

## 3. Schéma récapitulatif

```
┌─────────────────────────────────────────────────────────────────────────┐
│              ENVOI D'E-MAILS AFRICODE (un seul mécanisme)                │
├─────────────────────────────────────────────────────────────────────────┤
│  .env (MAIL_*, QUEUE_CONNECTION)                                         │
│       │                                                                   │
│       └──► config('mail.mailers.smtp.*') + config('mail.from')            │
│                     │                                                     │
│                     ├──► NewsletterMailService   (inscription newsletter) │
│                     ├──► ContactMailService      (formulaire contact)     │
│                     ├──► RegistrationMailService (bienvenue après         │
│                     │                           vérification e-mail)     │
│                     └──► CourseMailService       (campagne nouveau cours)  │
│                                    │                                      │
│                                    └──► Job SendNewCourseEmails (queue)   │
│                                              déclenché par CoursePublished│
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 4. Vérifications rapides

1. **Variables d’environnement**
   - Vérifier que `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` sont renseignés pour un envoi SMTP réel.

2. **Campagne nouveau cours**
   - S’assurer que le worker tourne : `php artisan queue:work` (job `SendNewCourseEmails`).
   - Les erreurs sont loguées par `CourseMailService` et le job.

3. **Tous les services (PHPMailer)**
   - Les erreurs sont loguées avec `\Log::error(...)` dans chaque service.
   - En local avec Mailpit : `MAIL_ENCRYPTION=` vide, port 1025 ou 2525.

4. **Événements / files d’attente**
   - Vérifier que `CoursePublished` est bien déclenché quand un cours est publié (Admin / Formateur).
   - Vérifier que `Verified` est bien déclenché après clic sur le lien de vérification d’e-mail (Breeze / Laravel auth).

---

## 5. Résumé des fichiers importants

| Rôle | Fichier |
|------|---------|
| Config mail | `config/mail.php` |
| Newsletter | `app/Services/NewsletterMailService.php`, `app/Http/Controllers/NewsletterController.php` |
| Contact | `app/Services/ContactMailService.php`, `app/Http/Controllers/ContactController.php` |
| Bienvenue (vérification) | `app/Services/RegistrationMailService.php`, `app/Listeners/SendWelcomeEmailAfterVerification.php` |
| Campagne nouveau cours | `app/Services/CourseMailService.php`, `app/Jobs/SendNewCourseEmails.php`, `app/Listeners/SendNewCourseEmailCampaign.php` |
| Notification formateur (cours validé) | `app/Services/CourseMailService.php`, `app/Listeners/NotifyFormateurCoursePublished.php`, `resources/views/emails/formateur/` |
| Notification formateur (étudiant a payé) | `app/Events/PaymentSucceeded.php`, `app/Listeners/NotifyFormateurStudentPaid.php`, `app/Services/CourseMailService.php`, `resources/views/emails/formateur/student-paid*` |
| Événements | `app/Events/CourseCreated.php`, `app/Events/CoursePublished.php`, `app/Providers/EventServiceProvider.php` |
| Vues e-mail (cours) | `resources/views/emails/courses/new-course.blade.php`, `new-course-text.blade.php` |

---

## 6. Chiffrement SMTP (PHPMailer et Laravel)

Les quatre services PHPMailer respectent `MAIL_ENCRYPTION` :

- **`tls`** (défaut) : STARTTLS, port 587 recommandé.
- **`ssl`** : SMTPS, port 465.
- **Vide ou absent** : pas de chiffrement (utile pour Mailpit / SMTP local sur 1025 ou 2525).

Dans `.env` pour un SMTP local type Mailpit :

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=
```

En production, garder `MAIL_ENCRYPTION=tls` (ou `ssl`) et un port adapté (587 ou 465).

---

## 7. Checklist logique – tout le système mail

| # | Type d’email | Déclencheur | Destinataire | Service | Logique |
|---|--------------|-------------|--------------|---------|--------|
| 1 | Newsletter bienvenue | Inscription newsletter (formulaire) | L’abonné | NewsletterMailService | ✅ Un email par nouvelle inscription. |
| 2 | Contact | Envoi formulaire contact | Admin + confirmation au visiteur | ContactMailService | ✅ Deux envois (admin + visiteur). |
| 3 | Bienvenue (inscription) | Clic sur lien de vérification email (Verified) | Le nouvel utilisateur | RegistrationMailService | ✅ Un email après vérification. |
| 4 | Nouveau cours disponible | Admin publie un cours (CoursePublished) | Tous les apprenants | CourseMailService + job SendNewCourseEmails | ✅ Un email par apprenant, cache 24 h par cours/user, worker requis. |
| 5 | Cours validé et publié | Même événement CoursePublished | Le formateur du cours | CourseMailService (NotifyFormateurCoursePublished) | ✅ Un email au formateur si le cours a un formateur. |
| 6 | Apprenant a payé | Paiement réussi Stripe/FadaPay (PaymentSucceeded) | Le formateur du cours acheté | CourseMailService (NotifyFormateurStudentPaid) | ✅ Un email par paiement (cache anti-doublon), uniquement si montant > 0. |

**Cohérence globale :**

- Tous les envois passent par **PHPMailer** et la même config SMTP (`.env`).
- Chaque type a un **déclencheur clair** (controller ou événement).
- Les **erreurs** sont loguées dans chaque service ; les listeners ne font pas planter l’app.
- **Campagne nouveau cours** : seul flux en file d’attente → `php artisan queue:work` nécessaire en prod.
