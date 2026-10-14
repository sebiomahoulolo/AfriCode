# Fichiers à déployer en ligne (validation formateur + email)

Après les modifications (envoi d’email quand l’admin valide un formateur), déployez **uniquement** les fichiers listés ci‑dessous sur votre serveur/hébergement.

---

## 1. Fichiers à envoyer (à modifier ou créer sur le serveur)

| Fichier | Action | Description |
|---------|--------|-------------|
| `app/Services/CourseMailService.php` | **Modifier** | Ajout de la méthode `sendFormateurAccountApproved()` pour l’email de validation du compte formateur. |
| `app/Http/Controllers/AdminFormateurController.php` | **Modifier** | Appel au service d’email après validation + import de `CourseMailService`. |
| `resources/views/emails/formateur/account-approved.blade.php` | **Créer** | Vue HTML de l’email « Compte formateur validé ». |
| `resources/views/emails/formateur/account-approved-text.blade.php` | **Créer** | Vue texte brut du même email. |
| `resources/views/admin/formateurs/show.blade.php` | **Modifier** | Texte d’action mis à jour : mention de l’envoi d’un email de confirmation. |

---

## 2. Chemins complets (depuis la racine du projet)

```
app/Services/CourseMailService.php
app/Http/Controllers/AdminFormateurController.php
resources/views/emails/formateur/account-approved.blade.php
resources/views/emails/formateur/account-approved-text.blade.php
resources/views/admin/formateurs/show.blade.php
```

---

## 3. Après le déploiement

1. **Vider les caches Laravel** (sur le serveur) :
   ```bash
   php artisan view:clear
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Vérifier la config email** : les variables `MAIL_*` (ou la config SMTP utilisée par PHPMailer dans `CourseMailService`) doivent être correctes sur l’environnement de production pour que l’email part bien.

3. **Test** : aller sur `https://africode.tech/admin/formateurs/{id}` (un formateur en statut « Candidat »), cliquer sur « Valider formateur (Candidat → Actif) » et vérifier que le formateur reçoit l’email.

---

## 4. Fichiers à ne pas déployer pour cette fonctionnalité

- `TRANSFORMATION_SAAS_VISION.md` : documentation uniquement, pas nécessaire au fonctionnement.
- `FICHIERS_A_DEPLOYER.md` : ce fichier, optionnel sur le serveur.
