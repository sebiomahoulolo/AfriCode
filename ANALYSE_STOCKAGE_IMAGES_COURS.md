# 📸 ANALYSE COMPLÈTE : STOCKAGE ET RÉCUPÉRATION DES IMAGES DE COURS

## 📋 Vue d'ensemble

Ce document analyse comment les images de couverture des cours sont **stockées** lors de la création et comment elles sont **récupérées et affichées** dans les vues Blade.

---

## 🔄 FLUX COMPLET DU PROCESSUS

### 1️⃣ **UPLOAD DANS LE FORMULAIRE** (`create_course.blade.php`)

**Fichier :** `resources/views/formateurs/create_course.blade.php`

```php
// Ligne 217-224
<label for="cover_image" class="form-label">Image de couverture</label>
<input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
       id="cover_image" name="cover_image" accept="image/*">
<div class="form-text">Format recommandé : 1200 x 600 pixels (16:9), max 2 MB.</div>
<div id="imagePreview" class="image-preview"></div>
```

**Caractéristiques :**
- ✅ Champ de type `file` avec `accept="image/*"`
- ✅ Aperçu en temps réel via JavaScript (lignes 351-361)
- ✅ Formulaire avec `enctype="multipart/form-data"` (ligne 184)

**Aperçu JavaScript :**
```javascript
// Lignes 351-361
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('imagePreview').style.backgroundImage = `url('${event.target.result}')`;
            document.getElementById('imagePreview').style.border = 'none';
        };
        reader.readAsDataURL(file);
    }
});
```

---

### 2️⃣ **TRAITEMENT DANS LE CONTRÔLEUR** (`FormateurController.php`)

**Fichier :** `app/Http/Controllers/FormateurController.php`  
**Méthode :** `storeCourse()` (lignes 202-281)

#### **A. Validation de l'image** (lignes 211-228)

```php
'cover_image' => [
    'nullable',           // L'image est optionnelle
    'image',              // Doit être une image
    'mimes:jpeg,png,jpg,gif,webp',  // Formats acceptés
    'max:2048',           // Taille max : 2 MB
],

// Validation supplémentaire du type MIME
if ($request->hasFile('cover_image')) {
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $mimeType = $request->file('cover_image')->getMimeType();
    if (!in_array($mimeType, $allowedMimes)) {
        return back()->withErrors(['cover_image' => 'Type de fichier non autorisé...'])->withInput();
    }
}
```

#### **B. Stockage de l'image** (lignes 241-252)

```php
// Gérer l'upload de l'image de couverture
$coverImagePath = null;
if ($request->hasFile('cover_image')) {
    try {
        $file = $request->file('cover_image');
        
        // Génération du nom de fichier unique
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        
        // Stockage dans le disque 'public' sous le dossier 'courses'
        $path = $file->storeAs('courses', $filename, 'public');
        
        // Chemin complet pour la base de données
        $coverImagePath = 'storage/' . $path;
        
    } catch (\Exception $e) {
        return back()->withErrors(['cover_image' => 'Erreur lors de l\'upload...'])->withInput();
    }
}
```

**Détails importants :**
- 📁 **Emplacement physique :** `storage/app/public/courses/`
- 📝 **Format du nom :** `{timestamp}_{nom-slugifie}.{extension}`
- 💾 **Chemin en BDD :** `storage/courses/{filename}`

**Exemple :**
- Fichier original : `Mon Image.jpg`
- Nom stocké : `1703123456_mon-image.jpg`
- Chemin BDD : `storage/courses/1703123456_mon-image.jpg`
- Chemin physique : `storage/app/public/courses/1703123456_mon-image.jpg`

#### **C. Enregistrement en base de données** (ligne 266)

```php
$course = Course::create([
    // ... autres champs
    'cover_image_path' => $coverImagePath,  // Ex: "storage/courses/1703123456_mon-image.jpg"
    // ...
]);
```

---

### 3️⃣ **RÉCUPÉRATION VIA LE MODÈLE** (`Course.php`)

**Fichier :** `app/Models/Course.php`  
**Accessor :** `getCoverImageUrlAttribute()` (lignes 59-74)

```php
/**
 * Accessor pour obtenir l'URL complète de l'image de couverture
 */
public function getCoverImageUrlAttribute()
{
    // Si pas d'image, retourner l'image par défaut
    if (!$this->cover_image_path) {
        return asset('assets/images/course-placeholder.jpg');
    }

    // Normaliser le chemin
    $path = $this->cover_image_path;
    
    // Si le chemin contient déjà "storage/", utiliser tel quel
    if (str_starts_with($path, 'storage/')) {
        return asset($path);  // Ex: asset('storage/courses/image.jpg')
    }
    
    // Sinon, ajouter le préfixe "storage/"
    return asset('storage/' . $path);
}
```

**Utilisation :**
```php
// Dans les vues, on peut utiliser :
$course->cover_image_url  // Retourne l'URL complète via l'accessor
// OU
$course->cover_image_path // Retourne le chemin brut de la BDD
```

---

### 4️⃣ **AFFICHAGE DANS LES VUES BLADE**

#### **A. Méthode 1 : Utilisation directe de `cover_image_path`**

**Exemple 1 :** `resources/views/courses/show.blade.php` (ligne 219)

```blade
<img src="{{ asset($course->cover_image_path ?? 'assets/images/course-placeholder.jpg') }}" 
     alt="Image de couverture du cours {{ $course->title }}" class="preview-image">
```

**Exemple 2 :** `resources/views/livewire/course-showcase.blade.php` (ligne 228)

```blade
@if($course->cover_image_path)
    <img src="{{ asset($course->cover_image_path) }}" class="card-img-top" alt="{{ $course->title }}">
@else
    <img src="{{ asset('assets/images/course-placeholder.jpg') }}" class="card-img-top" alt="Image du cours par défaut">
@endif
```

**Exemple 3 :** `resources/views/apprenants/my-courses.blade.php` (ligne 383-384)

```blade
@if($course->cover_image_path && file_exists(public_path($course->cover_image_path)))
    <img src="{{ asset($course->cover_image_path) }}" alt="{{ $course->title }}" class="course-image">
@else
    <div class="course-image" style="background: linear-gradient(...)">
        <i class="fas fa-graduation-cap"></i>
    </div>
@endif
```

#### **B. Méthode 2 : Utilisation de l'accessor `cover_image_url`**

**Exemple :** `resources/views/admin/courses/show.blade.php` (ligne 50)

```blade
@if($course->cover_image_path)
    <img src="{{ $course->cover_image_url }}" alt="{{ $course->title }}" class="admin-course-img">
@else
    <div class="admin-course-placeholder">
        <i class="fas fa-book"></i>
        <span>Pas d'image</span>
    </div>
@endif
```

#### **C. Méthode 3 : Utilisation en background-image**

**Exemple :** `resources/views/formateurs/manage_course.blade.php` (ligne 292)

```blade
<div class="course-header" style="background-image: url('{{ $course->cover_image_path ? asset($course->cover_image_path) : 'https://via.placeholder.com/1200x400?text='.urlencode($course->title) }}')">
```

#### **D. Méthode 4 : Aperçu dans le formulaire d'édition**

**Exemple :** `resources/views/formateurs/edit_course.blade.php` (ligne 199)

```blade
<div id="imagePreview" class="image-preview" 
     style="background-image: url('{{ $course->cover_image_path ? asset($course->cover_image_path) : '' }}'); 
            {{ $course->cover_image_path ? 'border: none;' : '' }}">
</div>
```

---

## 📊 RÉSUMÉ DES CHEMINS ET EMPLACEMENTS

### **Structure des fichiers :**

```
storage/
└── app/
    └── public/
        └── courses/
            ├── 1703123456_mon-image.jpg
            ├── 1703123457_autre-cours.png
            └── ...
```

### **Valeurs en base de données :**

| Champ | Valeur exemple | Description |
|-------|----------------|-------------|
| `cover_image_path` | `storage/courses/1703123456_mon-image.jpg` | Chemin relatif stocké en BDD |

### **URLs générées :**

| Méthode | URL générée | Exemple |
|---------|-------------|---------|
| `asset($course->cover_image_path)` | `http://domain.com/storage/courses/image.jpg` | URL complète |
| `$course->cover_image_url` | `http://domain.com/storage/courses/image.jpg` | Via accessor |

---

## 🔍 POINTS IMPORTANTS À RETENIR

### ✅ **Bonnes pratiques observées :**

1. **Validation stricte :** Double validation (règles Laravel + vérification MIME)
2. **Noms uniques :** Utilisation de `time()` + slug pour éviter les collisions
3. **Gestion d'erreurs :** Try-catch pour les uploads
4. **Images par défaut :** Fallback vers placeholder si pas d'image
5. **Stockage organisé :** Dossier dédié `courses/` dans `storage/app/public/`

### ⚠️ **Points d'attention :**

1. **Symlink storage :** Assurez-vous que `php artisan storage:link` a été exécuté
2. **Permissions :** Le dossier `storage/app/public/courses/` doit être accessible en écriture
3. **Taille des fichiers :** Limite à 2 MB (configurable dans `php.ini`)
4. **Suppression d'anciennes images :** Gérée lors de la mise à jour (lignes 364-370 du contrôleur)

---

## 🔄 MISE À JOUR D'IMAGE

**Fichier :** `app/Http/Controllers/FormateurController.php`  
**Méthode :** `updateCourse()` (lignes 363-377)

```php
// Gérer l'upload de l'image de couverture
if ($request->hasFile('cover_image')) {
    // Supprimer l'ancienne image si elle existe
    if ($course->cover_image_path) {
        $oldPath = str_replace('storage/', '', $course->cover_image_path);
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
    
    // Sauvegarder la nouvelle image
    $file = $request->file('cover_image');
    $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('courses', $filename, 'public');
    $course->cover_image_path = 'storage/' . $path;
}
```

**Fonctionnalités :**
- ✅ Suppression automatique de l'ancienne image
- ✅ Même processus de stockage que la création
- ✅ Mise à jour du champ `cover_image_path` en BDD

---

## 📝 CONCLUSION

Le système de gestion des images est **bien structuré** avec :

1. **Upload sécurisé** avec validation multiple
2. **Stockage organisé** dans `storage/app/public/courses/`
3. **Récupération flexible** via accessor ou chemin direct
4. **Affichage robuste** avec fallback sur placeholder
5. **Nettoyage automatique** lors des mises à jour

**Recommandation :** Utiliser `$course->cover_image_url` dans les vues pour bénéficier automatiquement de la gestion des images par défaut.

---

*Document généré le : {{ date('Y-m-d H:i:s') }}*

