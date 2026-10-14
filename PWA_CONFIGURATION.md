# Configuration PWA AfriCode

La Progressive Web App (PWA) est configurée et opérationnelle.

## Fichiers concernés

| Élément | Fichier / emplacement |
|--------|------------------------|
| Config | `config/laravelpwa.php` |
| Page hors ligne | `resources/views/vendor/laravelpwa/offline.blade.php` |
| Service worker | `public/serviceworker.js` |
| Icônes | `public/images/icons/` (icônes 72–512px + splashs) |
| Route offline | `GET /offline` (dans `routes/web.php`) |

## Comportement

- **Manifest** : `GET /manifest.json` (nom, couleurs, icônes, raccourcis AfriCode).
- **Service worker** : enregistré via la directive `@laravelPWA` dans les layouts.
- **Stratégie de cache** :
  - Navigation : réseau d’abord, puis cache, puis page offline.
  - Autres ressources : cache d’abord, puis réseau, puis page offline.
- **Page offline** : page autonome (HTML + CSS inline), affichée quand il n’y a pas de réseau.

## Layouts incluant la PWA

La directive `@laravelPWA` est présente dans :

- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/layout.blade.php`
- `resources/views/apprenants/layouts/app.blade.php`
- `resources/views/admin/layouts/app.blade.php`
- `resources/views/formateurs/layouts/app.blade.php`

## Personnalisation

1. **Icônes** : remplacer les PNG dans `public/images/icons/` par vos icônes AfriCode (tailles 72, 96, 128, 144, 152, 192, 384, 512 px).
2. **Couleurs / nom** : modifier `config/laravelpwa.php` (`theme_color`, `short_name`, etc.).
3. **Page offline** : éditer `resources/views/vendor/laravelpwa/offline.blade.php`.

## Vérifications

- En production, utiliser **HTTPS** (obligatoire pour les PWA sauf sur localhost).
- Dans Chrome : DevTools → Application → Manifest et Service Workers.
- Tester le mode hors ligne : DevTools → Network → Offline, puis recharger.

## Mise à jour du service worker

Après modification de `public/serviceworker.js`, les utilisateurs reçoivent le nouveau worker au prochain chargement. Pour éviter un cache trop long du fichier, configurer le serveur web (ex. Nginx) avec par exemple :

```nginx
location = /serviceworker.js {
    add_header Cache-Control "no-cache, no-store, must-revalidate";
}
```
