# AfriCode Homepage - Design inspiré de NetAcad

## Aperçu

Cette nouvelle page d'accueil d'AfriCode s'inspire du design moderne et professionnel de Cisco NetAcad tout en conservant l'identité visuelle unique d'AfriCode. Le design met l'accent sur la clarté, l'accessibilité et l'engagement utilisateur.

## Caractéristiques principales

### 🎨 Design et Interface
- **Charte graphique AfriCode** : Utilisation des couleurs primaires (#1EA38B, #FF8E2A) et des gradients cohérents
- **Layout moderne** : Inspiré de NetAcad avec une approche fluide et épurée
- **Responsive design** : Optimisé pour tous les appareils (mobile, tablette, desktop)
- **Animations subtiles** : Effets d'apparition progressive et micro-interactions

### 🔧 Fonctionnalités techniques
- **Performance optimisée** : CSS et JS modulaires avec Vite
- **Accessibilité** : Support des lecteurs d'écran et navigation clavier
- **SEO optimisé** : Meta tags, Schema.org, et structure sémantique
- **Lazy loading** : Chargement progressif des images et animations

### 📱 Sections principales

#### 1. Barre de navigation supérieure
- Navigation inspirée de NetAcad avec liens vers les différentes plateformes
- Design minimaliste avec hover effects

#### 2. Hero Section
- Titre impactant avec effet de gradient
- Statistiques d'impact en temps réel
- Illustration SVG personnalisée avec badges flottants
- Call-to-action clair vers l'inscription

#### 3. Programmes phares
- Cards modernes avec effets hover
- Badge "Populaire" pour le programme vedette
- Métadonnées claires (durée, niveau)
- Animations d'apparition progressive

#### 4. Section "Pourquoi AfriCode"
- Liste de features avec icônes
- Layout en colonnes avec illustration
- Effets de hover interactifs

#### 5. Témoignages
- Cards de témoignages avec photos
- Système de notation par étoiles
- Informations détaillées sur les apprenants

#### 6. Call-to-Action final
- Background gradient avec effet de transparence
- Boutons d'action multiples
- Design inspiré des sections CTA de NetAcad

## Structure des fichiers

```
resources/
├── views/
│   └── index.blade.php          # Page d'accueil principale
├── css/
│   ├── layouts/admin.css        # Styles existants
│   └── homepage.css             # Styles spécifiques homepage
└── js/
    └── homepage.js              # Scripts interactifs homepage
```

## Variables CSS (Charte graphique)

```css
:root {
    --africode-primary: #1EA38B;
    --africode-secondary: #FF8E2A;
    --africode-accent-red: #E32D31;
    --africode-highlight-green: #27B371;
    --africode-white: #FFFFFF;
    --africode-dark-text: #333333;
    --africode-gray-light: #F8F9FA;
    --africode-gray-medium: #E9ECEF;
    --africode-gray-dark: #6C757D;
}
```

## Animations et interactions

### Animations CSS
- **Float** : Badges flottants dans le hero
- **Fade in** : Apparition progressive des sections
- **Hover effects** : Transformations sur les cards et boutons
- **Gradient animations** : Effets de glow sur les éléments featured

### Interactions JavaScript
- **Scroll progress indicator** : Barre de progression en haut de page
- **Counter animations** : Animation des chiffres dans les statistiques
- **Parallax effect** : Effet de parallaxe subtil sur le hero
- **Smooth scrolling** : Navigation fluide vers les ancres
- **Intersection Observer** : Déclenchement des animations au scroll

## Performance et optimisation

### CSS
- Utilisation de `transform` et `opacity` pour les animations (GPU-accelerated)
- Variables CSS pour la cohérence et la maintenabilité
- Media queries pour le responsive design
- Minification automatique avec Vite

### JavaScript
- Debouncing des événements de scroll
- Intersection Observer pour les animations
- Lazy loading des images
- Performance monitoring en mode développement

## Accessibilité

- **ARIA labels** : Étiquettes descriptives pour les éléments interactifs
- **Keyboard navigation** : Support complet du clavier
- **Color contrast** : Respect des ratios de contraste WCAG
- **Reduced motion** : Respect des préférences utilisateur pour les animations
- **Screen readers** : Structure sémantique optimisée

## Responsive Design

### Breakpoints
- **Mobile** : < 576px
- **Tablet** : 576px - 992px
- **Desktop** : > 992px

### Adaptations mobiles
- Navigation simplifiée
- Hero section optimisé
- Cards en colonne unique
- Tailles de police ajustées
- Espacement réduit

## Installation et utilisation

1. **Compiler les assets** :
```bash
npm run dev
# ou pour la production
npm run build
```

2. **Lancer le serveur Laravel** :
```bash
php artisan serve
```

3. **Accéder à la homepage** :
```
http://localhost:8000
```

## Customisation

### Couleurs
Modifiez les variables CSS dans `homepage.css` pour adapter la charte graphique :

```css
:root {
    --africode-primary: #VotreCouleur;
    --africode-secondary: #VotreCouleur;
}
```

### Animations
Ajustez les durées et effets dans `homepage.js` :

```javascript
// Vitesse des animations
const animationDuration = 600; // ms

// Délai entre les animations
const animationDelay = 100; // ms
```

### Contenu
Modifiez directement dans `index.blade.php` :
- Textes et titres
- Images et illustrations
- Liens et CTAs
- Témoignages

## Compatibilité navigateurs

- **Chrome** : 80+
- **Firefox** : 78+
- **Safari** : 13+
- **Edge** : 80+

## Support et maintenance

Pour toute question ou amélioration :
1. Vérifiez la console navigateur pour les erreurs JavaScript
2. Validez le CSS avec les outils de développement
3. Testez sur différents appareils et tailles d'écran
4. Vérifiez l'accessibilité avec les outils WAVE ou axe

## TODO / Améliorations futures

- [ ] Intégration d'un système de A/B testing
- [ ] Ajout d'animations GSAP plus complexes
- [ ] Système de thème sombre/clair
- [ ] Intégration de vidéos de présentation
- [ ] Chatbot intégré
- [ ] Progressive Web App (PWA) features

---

**Développé avec ❤️ pour AfriCode**
