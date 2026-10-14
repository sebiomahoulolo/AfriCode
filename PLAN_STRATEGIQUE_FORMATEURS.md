# 🎓 PLAN STRATÉGIQUE FORMATEURS – AFRICODE

## 🟦 INTRODUCTION

Le présent document définit l'ensemble des règles, processus et stratégies liés à la gestion des formateurs AfriCode.

Son objectif est de garantir :

• une qualité pédagogique uniforme,
• une expertise technique élevée,
• une collaboration efficace entre AfriCode et les formateurs,
• un modèle économique équilibré,
• et une communauté active, motivée et durable.

---

## 🟪 1. RECRUTEMENT DES FORMATEURS

### 🎯 Objectifs

• Attirer les meilleurs experts africains et internationaux du numérique.
• Vérifier les compétences techniques et pédagogiques.
• Mettre en place un réseau de formateurs fiables, actifs et engagés.

### ✔️ A. Conditions d'éligibilité

• Maîtrise d'un domaine numérique (Dev Web, Mobile, IA, Data, Cybersécurité…)
• Capacité pédagogique (expliquer clairement, structurer un cours)
• Portfolio ou preuves de projets
• Bonne communication
• Disponibilité pour créer des cours

### ✔️ B. Processus de candidature

1. **Candidature en ligne** : Le candidat remplit un formulaire avec :
   - Informations personnelles
   - Domaine d'expertise
   - Portfolio/projets réalisés
   - Expérience pédagogique (si applicable)
   - Motivation

2. **Validation du profil** :
   - Vérification des compétences techniques
   - Analyse du portfolio
   - Entretien vidéo (optionnel)

3. **Signature du contrat** :
   - Contrat de formateur AfriCode
   - Acceptation des conditions générales
   - Acceptation de la charte de déontologie

### ✔️ C. Validation finale

• Vérification du niveau technique
• Vérification de la pédagogie
• Vérification de la capacité à produire des cours régulièrement

**Statut initial** : `candidat` → Après validation : `actif`

---

## 🟧 2. ONBOARDING & FORMATION DES FORMATEURS

### 🎯 Objectifs

• Aider les formateurs à produire des cours de qualité.
• Harmoniser les méthodes pédagogiques.
• Simplifier leur travail.

### ✔️ A. Module obligatoire "Comment enseigner sur AfriCode ?"

Inclut :

• Structurer une formation
• Normes pédagogiques AfriCode
• Normes audio/vidéo
• Création de quiz
• Utilisation de l'interface formateur
• Publication et mise à jour d'un cours

**Obligatoire avant la création du premier cours.**

### ✔️ B. Ressources fournies

• Guide PDF complet
• Exemple de cours parfait
• Templates (PowerPoint, scripts vidéo, modèles de quiz)
• Tutoriels (montage vidéo, qualité audio, mini studio)

### ✔️ C. Support formateur

• Canal privé Discord / WhatsApp
• Assistance technique sous 24h
• Sessions de coaching mensuelles
• Correction et validation continue

**Indicateur** : `onboarding_completed = true` dans le profil formateur

---

## 🟥 3. CRÉATION ET VALIDATION DES COURS

### ✔️ A. Structure obligatoire

1. **Introduction** : Présentation du cours, objectifs, prérequis
2. **Modules** : Organisation en modules thématiques
   - Vidéos (hébergées en mode non répertorié, lien intégré dans les leçons)
   - Textes enrichis
   - Ressources téléchargeables (PDF, etc.)
3. **Exercices et quiz obligatoires** : Au moins un quiz par module
4. **Projet final** (si applicable) : Projet pratique pour valider les acquis
5. **Conclusion + certification interne** : Si cours certifiant

### ✔️ B. Cycle de validation

1. **Soumission du cours** : Le formateur soumet son cours pour validation
   - Statut : `draft` → `pending_validation`

2. **Contrôle qualité par AfriCode** :
   - Vérification technique
   - Vérification pédagogique
   - Vérification anti-plagiat

3. **Corrections si nécessaire** :
   - Statut : `needs_revision`
   - Notes admin avec corrections à apporter

4. **Re-soumission** :
   - Le formateur corrige et re-soumet

5. **Validation définitive** :
   - Statut : `approved` → `published`
   - Le cours devient visible sur la plateforme

⏳ **Délais maximum** : 24h pour la validation

### ✔️ C. Critères de validation

• **Clarté** : Explications claires et accessibles
• **Structure logique** : Progression cohérente
• **Qualité vidéo/audio** : Minimum 720p, audio clair
• **Exactitude technique** : Informations correctes et à jour
• **Absence de plagiat** : Contenu original vérifié
• **Bonne pédagogie** : Engagement et interactivité

### 📋 Processus technique

```
1. Formateur → Créer un cours
   ↓
2. Formulaire de création (titre, description, prix, etc.)
   ↓
3. Cours créé en "draft" → Redirection vers gestion de modules
   ↓
4. Ajouter des modules au cours
   ↓
5. Pour chaque module → Ajouter des leçons
   ↓
6. Pour chaque module → Optionnel : Ajouter un quiz
   ↓
7. Soumettre pour validation (draft → pending_validation)
   ↓
8. Admin valide ou demande des corrections
   ↓
9. Si corrections → needs_revision → formateur corrige
   ↓
10. Re-soumission → Validation finale → approved → published
```

---

## 🟩 4. POLITIQUE DE MISE À JOUR DES COURS

• **Mise à jour dès qu'une technologie évolue** : Obligatoire
• **Correction des erreurs signalées** : Sous 48h
• **Mise à jour minimum tous les 6 mois** : Recommandée
• **Les vidéos sont hébergées en mode non répertorié** : Le lien est intégré sur le site AfriCode dans les leçons
• **AfriCode peut imposer une mise à jour obligatoire** : En cas d'obsolescence technique

**Suivi** : Date de dernière mise à jour enregistrée pour chaque cours.

---

## 🟦 5. RÉMUNÉRATION DES FORMATEURS

### 🎯 Objectifs

• Motiver la création de contenus de haute qualité
• Récompenser la performance
• Assurer un modèle transparent et stable

### ✔️ Modèle officiel

• **77 % pour le formateur**
• **23 % pour AfriCode**

### ✔️ Paiements

• **Versement** : Tous les jours ouvrables
• **Seuil minimum** : 25 000 FCFA
• **Cours gratuit obligatoire** : Au moins 3 cours gratuits par formateur
• **Modes** : Mobile Money, Carte bancaire
• **Délai** : 24h après demande de retrait

### ✔️ Bonus

• **Cours dépassant 200 étudiants** : Bonus supplémentaire (à définir)

### 📊 Calcul des revenus

```
Revenu formateur = (Prix cours × 0.77) × Nombre d'inscriptions
Bonus = Si étudiants > 200, bonus additionnel
Total = Revenu + Bonus
```

**Suivi** : Table `formateur_earnings` pour l'historique complet.

---

## 🟨 6. SYSTÈME DE QUALITÉ

### 📊 Évaluation des cours

Chaque cours est évalué par :
- **Notes des étudiants** (1-5 étoiles)
- **Commentaires et avis**
- **Taux de complétion**
- **Taux de réussite aux quiz**

### 🎯 Seuils de qualité

• **Note moyenne ≥ 4.0/5** : Excellent
• **Note moyenne 3.5-3.9** : Bon
• **Note moyenne 3.0-3.4** : Acceptable
• **Note moyenne < 3.0** : ⚠️ Analyse + accompagnement

### ⚠️ Actions correctives

• **En dessous de 3.0** → Analyse + accompagnement
• **Maintien des mauvaises notes** → Avertissement
• **Persistance** → Suspension du cours
• **Non-respect répété** → Suppression du cours

---

## 🟫 7. CHARTE DE DÉONTOLOGIE

Le formateur doit :

• **Respecter les étudiants** : Communication respectueuse et professionnelle
• **Utiliser un langage professionnel** : Pas de langage offensant ou inapproprié
• **Produire des contenus originaux** : Pas de plagiat
• **Répondre aux questions sous 24-48h** : Engagement de disponibilité
• **Respecter la confidentialité** : Données des étudiants protégées
• **Ne jamais plagier** : Contenu 100% original
• **Ne pas vendre en privé les formations AfriCode** : Exclusivité plateforme
• **Représenter positivement la marque** : Image de marque AfriCode

**Violation** → Sanctions disciplinaires (voir section 12)

---

## 🟥 8. POLITIQUE ANTI-PLAGIAT

### 🚫 Interdiction stricte de :

• Copier des vidéos YouTube
• Copier des cours Udemy, Coursera…
• Copier des scripts d'autres formateurs
• Utiliser du contenu protégé par droits d'auteur sans autorisation

### 🔍 Vérification

• Vérification automatique lors de la soumission
• Vérification manuelle par l'équipe AfriCode
• Comparaison avec bases de données de contenu

### ⚠️ Sanctions

1. **Première infraction** : Avertissement + Retrait du cours
2. **Deuxième infraction** : Suspension 30 jours
3. **Troisième infraction** : Suppression définitive du compte + Retrait des revenus

---

## 🟦 9. COMMUNAUTÉ DES FORMATEURS

### 👥 Espace privé

• **WhatsApp** : Groupe privé formateurs AfriCode
• **Discord** : Canal dédié pour échanges techniques
• **Réunions mensuelles** : Webinaires et partages d'expérience

### 🏆 Système de badges

• **Bronze** : 1-5 cours créés
• **Argent** : 6-15 cours créés
• **Or** : 16-30 cours créés
• **Platine** : 30+ cours créés + Excellence pédagogique

### 🌟 Hall of Fame AfriCode

• Mise en avant des meilleurs formateurs
• Statistiques publiques (nombre d'étudiants, revenus, notes)
• Badge spécial "Formateur de l'année"

---

## 🟧 10. GESTION INTERNE

### 👨‍💼 Rôles

• **Responsable formateurs** : Gestion globale, recrutement
• **Contrôleur pédagogique** : Validation qualité pédagogique
• **Contrôleur technique** : Validation qualité technique
• **Responsable validation** : Validation finale des cours
• **Responsable paiements** : Gestion des rémunérations

### 📊 Tableaux de bord

• **Formateurs actifs/inactifs** : Suivi de l'activité
• **Cours populaires** : Statistiques de performance
• **Revenus** : Suivi financier
• **Notes moyennes** : Qualité des cours
• **Modules en attente** : Cours en validation

---

## 🟨 11. OBLIGATIONS DU FORMATEUR

### ✅ Obligations principales

• **Créer au moins 3 cours gratuits** : Obligatoire pour validation
• **Répondre aux étudiants** : Sous 24-48h
• **Publier régulièrement** : Minimum 1 cours tous les 3 mois
• **Participer aux réunions mensuelles** : Engagement communautaire
• **Maintenir ses contenus** : Mise à jour régulière
• **Produire des cours originaux** : Pas de plagiat
• **Accepter les corrections AfriCode** : Collaboration

### 📅 Suivi

• Dernière création de cours : `last_course_created_at`
• Dernière connexion : `last_login_at`
• Nombre total de cours : `total_courses_created`

---

## 🟫 12. CADRE DISCIPLINAIRE

### ⚠️ Système d'escalade

1. **Avertissement** : Notification écrite, pas d'impact immédiat
2. **Suspension 7 jours** : Accès restreint, pas de création de cours
3. **Suspension 30 jours** : Accès restreint, cours non visibles
4. **Suppression définitive** : Compte banni + Retrait des revenus

### 🚫 Motifs de sanctions

• **Plagiat** : Contenu copié
• **Absence** : Inactivité prolongée sans justification
• **Faible qualité récurrente** : Notes < 3.0 persistantes
• **Non-respect des règles** : Violation de la charte
• **Comportement non professionnel** : Manque de respect

### 📋 Historique

Toutes les sanctions sont enregistrées dans `formateur_disciplinary_actions`.

---

## 🟩 13. SYNTHÈSE

### ✅ AfriCode met en place :

• **Recrutement strict** : Validation des compétences
• **Onboarding professionnel** : Formation obligatoire
• **Guides et ressources** : Support complet
• **Contrôle qualité solide** : Validation en 24h
• **Rémunération attractive** : 77% pour le formateur
• **Communauté forte** : Espace privé et badges
• **Gestion interne organisée** : Rôles définis
• **Charte claire** : Règles explicites
• **Cadre légal complet** : Système disciplinaire

### 🎯 Résultat attendu

Une communauté de formateurs :
- **Qualifiés** : Expertise technique validée
- **Engagés** : Contenus réguliers et de qualité
- **Motivés** : Rémunération équitable
- **Unis** : Communauté active et solidaire

---

## 📝 ANNEXES

### A. Processus technique de création de cours

Voir documentation technique : `PROCESSUS_CREATION_COURS.md`

### B. Interface formateur

- Dashboard avec statistiques
- Gestion des cours (création, édition, publication)
- Suivi des revenus
- Badges et récompenses
- Messages étudiants

### C. Interface admin

- Gestion des formateurs (validation, suspension)
- Validation des cours
- Suivi des revenus
- Tableaux de bord qualité
- Gestion disciplinaire

---

**Version** : 1.0  
**Date** : 2024  
**AfriCode** - Apprendre, Enseigner, Grandir ensemble 🚀
