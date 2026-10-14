# 📋 RÉCAPITULATIF DES VUES - SYSTÈME FORMATEURS

## 🎯 VUES ADMINISTRATEUR

### 1. Liste des formateurs
**Fichier** : `resources/views/admin/formateurs/index.blade.php`
**Route** : `/admin/formateurs`
**Contrôleur** : `AdminFormateurController@index`

**Contenu** :
- Tableau avec tous les formateurs
- Filtres : statut (candidat, actif, suspendu, inactif, banni)
- Recherche par nom/email
- Statistiques globales (total, actifs, candidats, suspendus)
- Actions rapides : Voir détails, Valider, Suspendre

**Colonnes du tableau** :
- Nom/Email
- Statut (badge coloré)
- Badge level (Bronze, Argent, Or, Platine)
- Nombre de cours
- Nombre d'étudiants
- Revenus totaux
- Note moyenne
- Dernière activité
- Actions

---

### 2. Détails d'un formateur
**Fichier** : `resources/views/admin/formateurs/show.blade.php`
**Route** : `/admin/formateurs/{id}`
**Contrôleur** : `AdminFormateurController@show`

**Sections** :
1. **Informations générales** :
   - Profil complet
   - Statut actuel
   - Badge level
   - Date d'inscription
   - Onboarding complété (oui/non)
   - Contrat signé (oui/non)

2. **Statistiques** :
   - Total cours créés
   - Cours publiés
   - Total étudiants
   - Revenus totaux
   - Revenus en attente
   - Note moyenne

3. **Cours du formateur** :
   - Liste des cours avec statut
   - Actions : Valider, Demander corrections, Rejeter

4. **Actions disciplinaires** :
   - Historique complet
   - Bouton : Ajouter avertissement
   - Bouton : Suspendre
   - Bouton : Lever suspension

5. **Retraits** :
   - Historique des retraits
   - Statuts (pending, processing, completed)

6. **Actions** :
   - Valider formateur (si candidat)
   - Rejeter formateur
   - Suspendre (7j ou 30j)
   - Lever suspension
   - Ajouter avertissement

---

### 3. Gestion des retraits
**Fichier** : `resources/views/admin/formateurs/withdrawals.blade.php`
**Route** : `/admin/formateurs/withdrawals`
**Contrôleur** : `AdminFormateurController@withdrawals`

**Contenu** :
- Liste de tous les retraits
- Filtres : statut (pending, processing, completed, failed)
- Informations par retrait :
  - Formateur
  - Montant
  - Méthode de paiement
  - Compte
  - Date de demande
  - Statut
- Actions :
  - Traiter (pending → processing)
  - Compléter (processing → completed)
  - Marquer comme échoué

---

### 4. Validation de cours (dans show.blade.php ou modal)
**Modal/Form** : Validation rapide d'un cours depuis la liste

**Actions** :
- Valider → approved → published
- Demander corrections → needs_revision
- Rejeter → rejected

---

## 👨‍🏫 VUES FORMATEUR

### 1. Dashboard revenus
**Fichier** : `resources/views/formateurs/earnings/index.blade.php`
**Route** : `/formateur/earnings`
**Contrôleur** : `FormateurEarningsController@index`

**Sections** :
1. **Résumé financier** :
   - Revenus totaux (grand chiffre)
   - Revenus en attente
   - Disponible pour retrait (si >= 25 000 FCFA)
   - Seuil minimum : 25 000 FCFA

2. **Demande de retrait** :
   - Formulaire :
     - Montant (min: 25 000 FCFA)
     - Méthode (Mobile Money, Carte, Virement)
     - Numéro de compte
   - Bouton : Demander retrait

3. **Historique des revenus** :
   - Tableau avec :
     - Date
     - Cours
     - Montant
     - Part formateur (77%)
     - Statut paiement
     - Actions : Voir détails

4. **Statistiques par cours** :
   - Graphique ou tableau
   - Revenus par cours
   - Nombre d'étudiants par cours

5. **Retraits récents** :
   - Liste des 10 derniers retraits
   - Statut de chaque retrait

---

### 2. Détails d'un revenu
**Fichier** : `resources/views/formateurs/earnings/show.blade.php`
**Route** : `/formateur/earnings/{id}`
**Contrôleur** : `FormateurEarningsController@show`

**Contenu** :
- Informations détaillées du revenu
- Cours concerné
- Période
- Calcul détaillé (prix, 77%, 23%, bonus)
- Statut du paiement
- Date de paiement (si payé)

---

### 3. Dashboard formateur (modifié)
**Fichier** : `resources/views/formateurs/dashboard.blade.php` (existant, à modifier)

**Ajouts nécessaires** :
- Section "Revenus" :
  - Revenus totaux
  - Revenus en attente
  - Lien vers page revenus
- Section "Statut" :
  - Badge actuel
  - Statut formateur
  - Onboarding complété
- Section "Actions disciplinaires" :
  - Avertissements actifs
  - Suspensions actives

---

### 4. Gestion de cours (modifié)
**Fichier** : `resources/views/formateurs/manage_course.blade.php` (existant, à modifier)

**Ajouts nécessaires** :
- Bouton "Soumettre pour validation" (si statut = draft)
- Affichage du statut de validation :
  - draft : Bouton soumettre
  - pending_validation : En attente de validation
  - needs_revision : Corrections demandées (afficher revision_notes)
  - approved : Validé
  - rejected : Rejeté (afficher admin_notes)
- Notes admin/revision affichées si présentes

---

## 📊 RÉSUMÉ DES FICHIERS À CRÉER

### Admin (3 fichiers)
1. ✅ `resources/views/admin/formateurs/index.blade.php`
2. ✅ `resources/views/admin/formateurs/show.blade.php`
3. ✅ `resources/views/admin/formateurs/withdrawals.blade.php`

### Formateur (2 fichiers)
1. ✅ `resources/views/formateurs/earnings/index.blade.php`
2. ✅ `resources/views/formateurs/earnings/show.blade.php`

### Modifications (2 fichiers)
1. 🔄 `resources/views/formateurs/dashboard.blade.php` (ajouter section revenus)
2. 🔄 `resources/views/formateurs/manage_course.blade.php` (ajouter bouton soumettre)

---

## 🎨 STRUCTURE DES VUES

Toutes les vues utilisent :
- Layout : `admin.layouts.app` (admin) ou `formateurs.layouts.app` (formateur)
- Styles : TailwindCSS + Bootstrap 5
- Composants : Cards, Tables, Badges, Buttons
- Responsive : Mobile-first

---

**Total** : 5 nouvelles vues + 2 modifications

