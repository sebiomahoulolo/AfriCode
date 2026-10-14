# Transformation AfriCode → Vision SaaS complète

Ce document décrit comment faire évoluer votre plateforme AfriCode (déjà fonctionnelle) vers un **SaaS multi-tenant** avec abonnements, plans tarifaires et facturation récurrente.

---

## 0. Le SaaS est-il une bonne option pour AfriCode ?

### Ce qu’est AfriCode aujourd’hui

- **Modèle actuel** : plateforme e-learning type **marketplace** — un seul catalogue, des formateurs qui créent des cours, des apprenants qui achètent et suivent.
- **Monétisation** : paiement à l’acte (par cours) via Stripe, FadaPay, PayPal.
- **Force du modèle** : un seul lieu (africode.com), visibilité pour les formateurs, choix et communauté pour les apprenants, contexte africain (FCFA, certifications, contenu local).

### Quand le SaaS multi-tenant est une **bonne** option

| Situation | Verdict |
|-----------|--------|
| Vous ciblez des **clients B2B** : écoles, universités, entreprises, centres de formation qui veulent **leur propre espace** (catalogue privé, leurs formateurs, leurs apprenants, données isolées). | **Oui** — le SaaS (multi-tenant) correspond à ce besoin. |
| Vous voulez du **revenu récurrent** en vendant un **logiciel** à des organisations (abonnement mensuel/annuel) plutôt qu’uniquement des ventes de cours. | **Oui** — le SaaS permet des abonnements par organisation. |
| Vous êtes prêt à gérer la **complexité** : multi-tenancy, facturation par tenant, onboarding d’organisations, support B2B. | **Oui** — c’est le coût normal d’un vrai SaaS. |

### Quand le SaaS multi-tenant n’est **pas** la priorité (ou pas tout de suite)

| Situation | Verdict |
|-----------|--------|
| Votre priorité est de **garder une seule grande marketplace** (un catalogue, une communauté, effet de réseau). | **Pas prioritaire** — découper en tenants fragmente le catalogue et peut réduire la découverte des cours. |
| Vous voulez surtout du **revenu récurrent côté apprenants** (ex. abonnement “accès à un bouquet de cours” type Netflix). | **Alternative plus simple** : abonnement **apprenant** (plans Free/Pro) **sans** multi-tenancy. Même revenu récurrent, moins de complexité. |
| Vous n’avez pas encore de **clients B2B** identifiés (écoles, entreprises) qui demandent leur propre instance. | **Attendre** — valider d’abord la demande avant d’investir lourdement dans le SaaS. |

### Trois stratégies possibles

1. **SaaS en plus du marketplace (recommandé si vous visez le B2B)**  
   - Garder **africode.com** tel quel : une marketplace pour formateurs et apprenants (paiement par cours).  
   - Proposer en parallèle **“AfriCode pour les organisations”** : produit SaaS (abonnement, multi-tenant) pour écoles/entreprises qui veulent leur propre espace.  
   - Deux offres distinctes, même codebase possible (avec tenant_id et middleware tenant).

2. **Tout en SaaS (remplacer le marketplace)**  
   - Chaque “client” = une organisation (tenant). Plus de grande marketplace unique.  
   - Adapté seulement si votre stratégie est **100 % B2B** (vendre à des organisations, pas au grand public).

3. **Rester marketplace + abonnement apprenant (sans multi-tenant)**  
   - Garder une seule instance AfriCode.  
   - Ajouter des **plans abonnés** pour les apprenants (ex. “AfriCode Pro” = X €/mois pour accès à un bouquet de cours).  
   - Revenu récurrent sans multi-tenancy, moins de complexité.

### Conclusion

- **Oui, le SaaS est une bonne option** si vous avez (ou visez) des **clients B2B** (écoles, entreprises) qui veulent leur propre plateforme et que vous acceptez la complexité technique et commerciale.
- **Ce n’est pas obligatoire** pour avoir du “SaaS” au sens “abonnements” : un abonnement **apprenant** sur la marketplace actuelle peut suffire pour du revenu récurrent.
- **Recommandation** : si vous ciblez à la fois grand public et organisations, garder la **marketplace** et ajouter le **SaaS comme offre séparée** (“AfriCode pour les organisations”) plutôt que de tout transformer en multi-tenant d’un coup.

---

### Rester dans la logique AfriCode : formations en ligne, ici en Afrique

**Oui : en faisant du SaaS, vous restez totalement dans la logique AfriCode.** Le SaaS ne change pas votre mission, il étend *à qui* vous livrez la même chose.

| Ce que vous faites (et qu'Udemy, Coursera, LinkedIn, 3W n'ont pas fait comme vous) | Reste vrai avec le SaaS ? |
|-----------------------------------------------------------------------------------|---------------------------|
| **Formations en ligne** — cours, modules, quiz, certifications, progression        | Oui. C'est toujours une plateforme de formation en ligne. |
| **Contexte africain** — FCFA, paiements locaux (FadaPay), contenu et langues adaptés | Oui. Chaque tenant peut garder FCFA, locale, etc. |
| **Démocratiser l'éducation numérique en Afrique** — apprendre, enseigner, grandir | Oui. La marketplace pour le grand public ; le SaaS pour les écoles/entreprises qui forment *leurs* publics. |
| **Un lieu pour formateurs et apprenants** — création de cours, inscriptions, certificats | Oui. Dans le SaaS, chaque organisation a *son* lieu, avec les mêmes fonctionnalités. |

En résumé :

- **AfriCode = formations en ligne**, avec tout ce que vous avez déjà (cours, quiz, certifications, paiements, forum, badges, etc.).
- **Udemy, Coursera, LinkedIn Learning, 3W** : ils ne font pas (ou pas bien) en Afrique ce que vous faites — paiement en FCFA, contenu pensé pour le terrain africain.
- **Le SaaS** ne remplace pas ça : c'est une **façon supplémentaire de livrer la même plateforme** à des **organisations** (écoles, centres de formation, entreprises) qui veulent *leur* espace de formation, sur votre stack, vos paiements, vos certifications.

Donc :

- **Marketplace africode.com** → formations en ligne pour tout le monde (ce que vous faites déjà, ce que les autres n'ont pas fait comme vous en Afrique).
- **SaaS "AfriCode pour les organisations"** → les mêmes formations en ligne, la même logique AfriCode, livrées à des structures qui ont besoin de leur propre espace (données isolées, branding, abonnement).

Vous restez donc **dans la logique AfriCode pour des formations en ligne** : vous faites en Afrique ce qu'Udemy, Coursera, LinkedIn, 3W n'ont pas fait (ou pas comme vous), et le SaaS est simplement une **option de distribution B2B** en plus de votre marketplace, sans changer le cœur métier.

---

## 1. Ce que signifie "Vision SaaS" dans votre contexte

| Aspect | Actuel (mono-tenant) | Cible (SaaS) |
|--------|----------------------|---------------|
| **Clients** | Une seule instance AfriCode | Plusieurs organisations (écoles, entreprises, centres de formation) |
| **Données** | Tous les cours/utilisateurs dans une seule base | Données isolées par **tenant** (organisation) |
| **Monétisation** | Paiement par cours (Stripe/FadaPay/PayPal) | **Abonnements** (mensuel/annuel) + éventuellement paiement par cours |
| **Accès** | Un seul site (ex: africode.com) | Sous-domaines ou slug : `ecole-x.africode.com` ou `africode.com/e/ecole-x` |
| **Personnalisation** | Un seul branding | **White-label** optionnel : logo, couleurs, nom par tenant |

---

## 2. Architecture cible recommandée

### 2.1 Modèle de multi-tenancy : **une base, colonne `tenant_id`**

- **Une seule base de données** avec une colonne `tenant_id` sur les tables "métier" (courses, users, enrollments, etc.).
- Avantage : déploiement simple, sauvegardes uniques, pas de multiplication de bases.
- Chaque requête est filtrée par le tenant courant (middleware + global scope).

### 2.2 Nouveaux concepts à introduire

```
Tenant (Organisation)
  ├── name, slug, domaine/sous-domaine
  ├── plan_id → Plan (Free, Pro, Enterprise)
  ├── subscription (Stripe Subscription)
  └── settings (logo, couleurs, timezone)

Plan
  ├── name (Free, Pro, Enterprise)
  ├── price_monthly, price_yearly
  ├── limits: max_courses, max_formateurs, max_students, storage_mb
  └── features (JSON ou table pivot)

Subscription (abonnement actif du tenant)
  ├── tenant_id
  ├── plan_id
  ├── stripe_subscription_id / stripe_price_id
  ├── status (active, canceled, past_due)
  └── current_period_ends_at
```

---

## 3. Plan de transformation par étapes

### Phase 1 : Fondations (sans casser l’existant)

1. **Créer les modèles et migrations**
   - `tenants` : id, name, slug, domain, plan_id, settings (JSON), stripe_customer_id, created_at, updated_at.
   - `plans` : id, name, slug, price_monthly, price_yearly, limits (JSON), features (JSON).
   - `subscriptions` : id, tenant_id, plan_id, stripe_subscription_id, status, current_period_ends_at.

2. **Tenant "par défaut"**
   - Créer un tenant "AfriCode" (slug `africode`) et associer toutes les données existantes à ce tenant (migration qui remplit `tenant_id` avec l’id du tenant par défaut).

3. **Ajouter `tenant_id` partout où il faut**
   - Tables à scoper : `users`, `courses`, `categories`, `enrollments`, `payments`, `certifications`, etc.
   - Faire des migrations qui ajoutent `tenant_id` (nullable puis rempli, puis non nullable si vous voulez).

### Phase 2 : Isolation par tenant

4. **Middleware "tenant courant"**
   - Détecter le tenant depuis : sous-domaine (`ecole.africode.com`), ou domaine personnalisé, ou slug dans l’URL (`/e/ecole-x`).
   - Stocker le tenant dans `app()->instance(Tenant::class, $tenant)` ou une facade `Tenancy::current()`.

5. **Global scopes sur les modèles**
   - Sur `Course`, `User`, `Enrollment`, etc. : `addGlobalScope(new TenantScope())` pour filtrer automatiquement par `tenant_id` du tenant courant.
   - Les utilisateurs "super-admin" (AfriCode) peuvent être exclus du scope pour gérer tous les tenants.

6. **Création de données**
   - À chaque création (course, user, etc.), remplir `tenant_id` avec le tenant courant.

### Phase 3 : Abonnements et facturation

7. **Stripe Billing (ou équivalent)**
   - Créer des **Products** et **Prices** dans Stripe pour chaque plan (mensuel / annuel).
   - À l’inscription d’un nouveau tenant : créer un **Customer** Stripe, puis une **Subscription**.
   - Webhooks Stripe : `customer.subscription.updated`, `invoice.paid`, `invoice.payment_failed` pour mettre à jour la table `subscriptions` et le statut du tenant.

8. **Limites par plan**
   - Avant de créer un cours / formateur / étudiant : vérifier les limites du plan du tenant (max_courses, max_formateurs, max_students).
   - Afficher dans le dashboard tenant un indicateur d’usage (ex : "12 / 50 cours utilisés").

9. **Portail client Stripe**
   - Lien "Gérer mon abonnement" qui redirige vers le Customer Portal Stripe (changement de plan, carte, factures).

### Phase 4 : Expérience utilisateur SaaS

10. **Inscription "Organisation"**
    - Page dédiée : "Créer mon espace formation" → choix du plan → création du tenant + premier admin du tenant → redirection vers Stripe Checkout ou Customer + Subscription.

11. **Connexion / domaine**
    - Connexion selon le contexte : si l’utilisateur arrive sur `ecole-x.africode.com`, après login il reste dans le contexte "ecole-x".
    - Gestion des domaines personnalisés (optionnel) : CNAME vers votre app + vérification DNS.

12. **White-label (optionnel)**
    - Champs `settings` du tenant : `logo_url`, `primary_color`, `app_name`.
    - Dans les vues (layouts), utiliser ces valeurs au lieu des valeurs par défaut AfriCode.

---

## 4. Fichiers et code à ajouter (résumé)

| Élément | Fichier / action |
|--------|-------------------|
| Modèle Tenant | `app/Models/Tenant.php` |
| Modèle Plan | `app/Models/Plan.php` |
| Modèle Subscription | `app/Models/Subscription.php` |
| Migrations | `tenant_id` sur users, courses, categories, enrollments, payments, … |
| Middleware | `app/Http/Middleware/SetCurrentTenant.php` (résolution par domaine/slug) |
| Global scope | `app/Models/Scopes/TenantScope.php` |
| Contrôleur abonnements | `app/Http/Controllers/SubscriptionController.php` (checkout, portal) |
| Webhooks Stripe | Étendre ou créer `WebhookController` pour subscription / invoice |
| Seeders | `PlansSeeder` (Free, Pro, Enterprise), `TenantAfriCodeSeeder` |

---

## 5. Ordre des migrations recommandé

1. `create_plans_table`
2. `create_tenants_table` (avec `plan_id` FK)
3. `create_subscriptions_table`
4. `add_tenant_id_to_users_table` (nullable, puis backfill, puis non nullable)
5. `add_tenant_id_to_courses_table`
6. Idem pour categories, enrollments, payments, certifications, etc.

Pour **ne pas casser l’existant** : garder `tenant_id` nullable au début, remplir toutes les lignes avec le tenant par défaut, puis passer en `nullable(false)`.

---

## 6. Règles métier à clarifier

- **Rôles** : Un même utilisateur peut-il appartenir à plusieurs tenants (ex : formateur dans deux écoles) ? Si oui, table pivot `tenant_user` avec rôle par tenant.
- **Paiement des cours** : Dans le SaaS, les revenus "cours" restent-ils pour le tenant (école) avec commission AfriCode, ou 100 % pour AfriCode ? Cela impacte Stripe (Connect vs simple Customer).
- **Premier tenant** : AfriCode = premier tenant ; les données actuelles lui sont attachées après backfill.

---

## 7. Prochaines actions concrètes

1. Créer les migrations `plans`, `tenants`, `subscriptions`.
2. Créer les modèles `Plan`, `Tenant`, `Subscription` et les relations.
3. Ajouter la colonne `tenant_id` à la table `users` (nullable), puis backfill avec le tenant "AfriCode".
4. Implémenter le middleware `SetCurrentTenant` (résolution par slug en premier, sous-domaine ensuite).
5. Ajouter le `TenantScope` sur le modèle `User` (puis sur Course, etc.) et s’assurer que le tenant courant est défini sur chaque requête.
6. Intégrer Stripe Billing pour un plan "Pro" test (création Customer + Subscription depuis une page "Choisir le plan").
7. Ajouter la vérification des limites (max_courses, etc.) avant création de cours.

En suivant ce plan, vous transformez progressivement votre plateforme complète et fonctionnelle en un **SaaS multi-tenant** sans tout réécrire d’un coup, tout en gardant la possibilité de faire coexister l’ancien usage (tenant unique AfriCode) pendant la transition.
