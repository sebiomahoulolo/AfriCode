# Structure du Projet AfriCode

## Architecture Organisée

### 📁 Structure des Ressources Frontend

```
resources/
├── css/
│   ├── app.css                    # CSS principal de l'application
│   ├── formateur.css             # CSS principal pour l'espace formateur
│   ├── components/               # Composants CSS modulaires
│   │   ├── cards.css            # Styles pour les cartes
│   │   ├── course-showcase.css  # Styles pour la présentation des cours
│   │   ├── header.css           # Styles pour l'en-tête
│   │   ├── responsive.css       # Styles responsive
│   │   └── sidebar.css          # Styles pour la sidebar
│   └── layouts/                 # Styles pour les layouts
│       └── formateur.css        # Layout spécifique aux formateurs
├── js/
│   ├── app.js                   # JavaScript principal
│   ├── bootstrap.js             # Configuration Bootstrap
│   └── components/              # Composants JavaScript modulaires
│       └── formateur.js         # Interface formateur
└── views/                       # Vues Blade organisées par rôle
    ├── admin/                   # Interface administrateur
    ├── apprenants/              # Interface apprenants
    ├── formateurs/              # Interface formateurs
    ├── auth/                    # Authentification
    ├── components/              # Composants Blade réutilisables
    └── layouts/                 # Layouts principaux
```

### 🎨 Système de Design

#### Variables CSS AfriCode
```css
:root {
    --africode-primary: #1EA38B;      /* Vert principal */
    --africode-secondary: #FF8E2A;    /* Orange secondaire */
    --africode-accent-red: #E32D31;   /* Rouge accent */
    --africode-highlight-green: #27B371; /* Vert highlight */
    --africode-white: #FFFFFF;        /* Blanc */
    --africode-dark-text: #333333;    /* Texte sombre */
    --africode-border-radius: 12px;   /* Radius moderne */
    --africode-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
```

#### Gradients
- **Primaire**: `linear-gradient(135deg, #1EA38B 0%, #27B371 100%)`
- **Accent**: `linear-gradient(135deg, #FF8E2A 0%, #FFB366 100%)`

#### Ombres
- **Petite**: `0 2px 4px rgba(0, 0, 0, 0.1)`
- **Moyenne**: `0 4px 8px rgba(0, 0, 0, 0.15)`
- **Grande**: `0 8px 25px rgba(0, 0, 0, 0.15)`

### 🔧 Compilation des Assets

#### Vite Configuration
```javascript
// vite.config.js
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/formateur.css',
                'resources/js/app.js',
                'resources/js/components/formateur.js'
            ],
            refresh: true,
        }),
    ],
});
```

#### Commandes
```bash
# Développement avec watch
npm run dev

# Production
npm run build
```

### 📱 Responsive Design

#### Breakpoints
- **Mobile**: `max-width: 768px`
- **Tablet**: `max-width: 992px`
- **Desktop**: `max-width: 1200px`

#### Fonctionnalités Responsive
- Sidebar collapsible sur desktop
- Menu overlay sur mobile
- Grille adaptative
- Composants flexibles

### 🧩 Composants Modulaires

#### CSS Components
- **Cards**: Cartes modernes avec hover effects
- **Buttons**: Boutons avec gradients AfriCode
- **Alerts**: Messages d'état stylisés
- **Sidebar**: Navigation latérale responsive

#### JavaScript Components
- **FormateurInterface**: Classe pour l'interface formateur
- **Sidebar Management**: Gestion état collapsed/expanded
- **Mobile Events**: Événements tactiles optimisés
- **AOS Integration**: Animations au scroll

### 🔄 Système d'États

#### Sidebar States
- **Collapsed**: Sidebar réduite (icônes seulement)
- **Expanded**: Sidebar complète (icônes + texte)
- **Mobile**: Overlay sur mobile avec animation

#### Persistance
- État de la sidebar sauvegardé dans `localStorage`
- Restauration automatique au chargement

### 🎯 Bonnes Pratiques Implémentées

#### CSS
- Variables CSS pour la cohérence
- Modules CSS organisés par composant
- Système de grille responsive
- Animations fluides avec `cubic-bezier`

#### JavaScript
- Classes ES6 pour l'organisation
- Gestion d'erreurs robuste
- Événements optimisés
- Compatibilité rétroactive

#### Blade Templates
- Layouts modulaires
- Composants réutilisables
- Sections bien définies
- Styles externalisés

### 📊 Performance

#### Optimisations
- CSS minifié en production
- JavaScript bundlé avec Vite
- Images optimisées
- Chargement conditionnel

#### Métriques
- Temps de chargement initial < 2s
- Transitions fluides 60fps
- Responsive instantané
- Pas de layout shifts

### 🔒 Sécurité

#### Pratiques
- Validation côté client et serveur
- Sanitisation des entrées
- Protection CSRF
- Authentification Laravel

### 🚀 Déploiement

#### Production
```bash
# Installation
composer install --no-dev --optimize-autoloader
npm install --production
npm run build

# Optimisations Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Cette structure professionnelle garantit :
- **Maintenabilité** : Code organisé et modulaire
- **Évolutivité** : Facilité d'ajout de nouvelles fonctionnalités
- **Performance** : Optimisations pour la production
- **Cohérence** : Système de design unifié
- **Accessibilité** : Interface responsive et inclusive
