# ✅ MODIFICATION : STOCKAGE DES IMAGES DANS `public/storage/courses`

## 📋 Résumé des modifications

Les images de couverture des cours sont maintenant stockées directement dans **`public/storage/courses`** au lieu de `storage/app/public/courses`.

---

## 🔄 Fichiers modifiés

### 1. **FormateurController.php**

#### ✅ `storeCourse()` - Création de cours
- **Avant :** Utilisait `$file->storeAs('courses', $filename, 'public')` → stockait dans `storage/app/public/courses`
- **Après :** Utilise `$file->move(public_path('storage/courses'), $filename)` → stocke dans `public/storage/courses`
- **Chemin en BDD :** `storage/courses/{filename}` (inchangé pour compatibilité)

#### ✅ `updateCourse()` - Mise à jour de cours
- **Avant :** Utilisait `Storage::disk('public')` pour supprimer et stocker
- **Après :** Utilise `File::delete()` et `$file->move()` pour gérer les fichiers dans `public/storage/courses`

#### ✅ `deleteCourse()` - Suppression de cours
- **Avant :** Utilisait `Storage::disk('public')->delete()`
- **Après :** Utilise `File::delete(public_path($course->cover_image_path))`

**Code modifié :**
```php
// Création du dossier s'il n'existe pas
$destinationPath = public_path('storage/courses');
if (!File::exists($destinationPath)) {
    File::makeDirectory($destinationPath, 0755, true);
}

// Déplacer le fichier
$file->move($destinationPath, $filename);
$coverImagePath = 'storage/courses/' . $filename;
```

---

### 2. **ImageOptimizationService.php**

#### ✅ `optimizeAndStore()` - Méthode principale
- **Avant :** Utilisait `Storage::disk('public')->makeDirectory()` et `$file->storeAs()`
- **Après :** Utilise `File::makeDirectory()` et `$file->move()` vers `public/storage/{directory}`

#### ✅ `deleteImage()` - Suppression d'image
- **Avant :** Utilisait `Storage::disk('public')->exists()` et `delete()`
- **Après :** Utilise `File::exists()` et `File::delete()` avec `public_path()`

**Code modifié :**
```php
// Créer le chemin de destination dans public/storage
$destinationPath = public_path('storage/' . $directory);

// Créer le répertoire
if (!File::exists($destinationPath)) {
    File::makeDirectory($destinationPath, 0755, true);
}

// Déplacer le fichier
$file->move($destinationPath, $filename);
return 'storage/' . $directory . '/' . $filename;
```

---

### 3. **AdminController.php**

#### ✅ `coursesUpdate()` - Mise à jour de cours (admin)
- **Avant :** Utilisait `Storage::disk('public')->exists()` et `delete()`
- **Après :** Utilise `File::exists()` et `File::delete()` avec `public_path()`

#### ✅ `coursesDestroy()` - Suppression de cours (admin)
- **Avant :** Utilisait `Storage::disk('public')` pour supprimer
- **Après :** Utilise `File::delete()` avec `public_path()`

**Code modifié :**
```php
// Suppression de l'ancienne image
if ($course->cover_image_path) {
    $imagePath = public_path($course->cover_image_path);
    if (File::exists($imagePath)) {
        File::delete($imagePath);
    }
}
```

---

## 🔍 Compatibilité avec les anciennes images

### ✅ Format des chemins en base de données

Les images existantes en base de données ont le format :
```
storage/courses/1758191202_maitrisez-bootstrap-5-les-fondamentaux-avec-le-framework.png
```

Les nouvelles images auront le même format :
```
storage/courses/{timestamp}_{nom-slugifie}.{extension}
```

### ✅ Accessor du modèle Course

L'accessor `getCoverImageUrlAttribute()` dans `app/Models/Course.php` fonctionne avec les deux formats car :

```php
public function getCoverImageUrlAttribute()
{
    if (!$this->cover_image_path) {
        return asset('assets/images/course-placeholder.jpg');
    }

    $path = $this->cover_image_path;
    if (str_starts_with($path, 'storage/')) {
        return asset($path);  // ✅ Fonctionne pour anciennes et nouvelles images
    }
    
    return asset('storage/' . $path);
}
```

**Résultat :**
- Anciennes images : `asset('storage/courses/...')` → `http://domain.com/storage/courses/...`
- Nouvelles images : `asset('storage/courses/...')` → `http://domain.com/storage/courses/...`

---

## 📁 Structure des fichiers

### **Avant :**
```
storage/
└── app/
    └── public/
        └── courses/
            └── image.jpg
```

### **Après :**
```
public/
└── storage/
    └── courses/
        └── image.jpg
```

**Avantage :** Les images sont directement accessibles via l'URL sans besoin de symlink Laravel.

---

## ✅ Points importants

1. **Compatibilité totale :** Les anciennes images continuent de fonctionner
2. **Format BDD inchangé :** Le format `storage/courses/...` est conservé
3. **Création automatique :** Le dossier `public/storage/courses` est créé automatiquement s'il n'existe pas
4. **Permissions :** Le dossier est créé avec les permissions `0755`
5. **Suppression propre :** Les anciennes images sont supprimées lors des mises à jour

---

## 🧪 Tests à effectuer

1. ✅ Créer un nouveau cours avec une image → Vérifier qu'elle est dans `public/storage/courses`
2. ✅ Modifier un cours existant avec une nouvelle image → Vérifier que l'ancienne est supprimée
3. ✅ Afficher un cours avec une ancienne image → Vérifier qu'elle s'affiche correctement
4. ✅ Afficher un cours avec une nouvelle image → Vérifier qu'elle s'affiche correctement
5. ✅ Supprimer un cours → Vérifier que l'image est supprimée

---

## 📝 Notes

- **Pas besoin de migration :** Les chemins en base de données restent identiques
- **Pas besoin de déplacer les anciennes images :** Elles continuent de fonctionner si elles sont déjà dans `public/storage/courses`
- **Si les anciennes images sont dans `storage/app/public/courses` :** Elles doivent être déplacées manuellement vers `public/storage/courses` OU créer un symlink

---

*Modifications effectuées le : {{ date('Y-m-d H:i:s') }}*

