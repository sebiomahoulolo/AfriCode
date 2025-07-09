# AfriCode - Architecture du Projet

## Structure des Assets

### CSS (resources/css/)
```
resources/css/
├── app.css                    # CSS principal de l'application
├── formateur.css             # Point d'entrée pour les styles formateur
├── components/               # Composants CSS réutilisables
│   ├── cards.css            # Styles pour les cartes UI
│   ├── header.css           # Styles pour le header
│   ├── responsive.css       # Styles responsive
│   ├── sidebar.css          # Styles pour la sidebar
│   └── course-showcase.css  # Styles pour la vitrine des cours
└── layouts/                 # Layouts CSS
    └── formateur.css        # Layout principal formateur
```

### JavaScript (resources/js/)
```
resources/js/
├── app.js                   # Point d'entrée principal
├── bootstrap.js             # Configuration Bootstrap/Axios
└── components/              # Composants JavaScript
    └── formateur.js         # Interface formateur (classe ES6)
```

## Fonctionnalités Clés

### Interface Formateur
- **Dashboard moderne** : Vue d'ensemble des statistiques et activités
- **Sidebar responsive** : Navigation adaptative avec état persistant
- **Gestion des cours** : Création, modification, et gestion complète
- **Système de quiz** : Création de quiz interactifs
- **Génération de certificats** : Certificats PDF automatiques

### Technologie Frontend
- **Framework CSS** : Bootstrap 5 + CSS Custom Properties
- **Animations** : AOS (Animate On Scroll)
- **JavaScript** : Vanilla JS avec classes ES6
- **Build Tool** : Vite pour la compilation et optimisation

## Configuration

### Compilation des Assets
```bash
# Développement
npm run dev

# Production
npm run build
```

### Variables CSS
Le projet utilise des variables CSS personnalisées pour maintenir la cohérence :
```css
--africode-primary: #1EA38B
--africode-secondary: #FF8E2A
--africode-accent-red: #E32D31
--africode-highlight-green: #27B371
```

## Optimisations Appliquées

### Performance
- **Compilation Vite** : Bundling optimisé avec code splitting
- **CSS Modulaire** : Séparation des composants pour un meilleur cache
- **JavaScript Classique** : Pas de frameworks lourds, performance native

### Maintenance
- **Structure claire** : Séparation logique des composants
- **Documentation** : Code documenté et commenté
- **Standards** : Respect des bonnes pratiques Laravel et CSS
