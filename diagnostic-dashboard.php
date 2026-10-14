<?php
/**
 * Script de diagnostic pour les tableaux de bord AfriCode
 * À exécuter sur le serveur pour identifier les problèmes
 */

echo "<h1>🔍 Diagnostic des Tableaux de Bord AfriCode</h1>";

// 1. Vérifier la configuration Laravel
echo "<h2>1. Configuration Laravel</h2>";
echo "<p><strong>Version Laravel:</strong> " . app()->version() . "</p>";
echo "<p><strong>Environment:</strong> " . app()->environment() . "</p>";
echo "<p><strong>Debug mode:</strong> " . (config('app.debug') ? 'Activé' : 'Désactivé') . "</p>";

// 2. Vérifier la base de données
echo "<h2>2. Base de données</h2>";
try {
    $pdo = new PDO(
        'mysql:host=' . config('database.connections.mysql.host') . ';dbname=' . config('database.connections.mysql.database'),
        config('database.connections.mysql.username'),
        config('database.connections.mysql.password')
    );
    echo "<p>✅ Connexion à la base de données réussie</p>";
    
    // Vérifier les tables principales
    $tables = ['users', 'courses', 'enrollments', 'payments'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p>✅ Table '$table' existe</p>";
        } else {
            echo "<p>❌ Table '$table' manquante</p>";
        }
    }
} catch (Exception $e) {
    echo "<p>❌ Erreur de connexion à la base de données: " . $e->getMessage() . "</p>";
}

// 3. Vérifier les routes
echo "<h2>3. Routes</h2>";
$routes = [
    'admin.dashboard' => '/admin',
    'dashboard' => '/dashboard',
    'formateur.dashboard' => '/formateur/dashboard',
    'apprenant.dashboard' => '/apprenant/dashboard'
];

foreach ($routes as $name => $url) {
    try {
        $route = route($name);
        echo "<p>✅ Route '$name': $route</p>";
    } catch (Exception $e) {
        echo "<p>❌ Route '$name' non trouvée: " . $e->getMessage() . "</p>";
    }
}

// 4. Vérifier les vues
echo "<h2>4. Vues</h2>";
$views = [
    'admin.dashboard' => 'resources/views/admin/dashboard.blade.php',
    'pages.dashboard' => 'resources/views/pages/dashboard.blade.php',
    'admin.layouts.app' => 'resources/views/admin/layouts/app.blade.php',
    'layouts.app' => 'resources/views/layouts/app.blade.php'
];

foreach ($views as $view => $path) {
    if (file_exists($path)) {
        echo "<p>✅ Vue '$view': $path</p>";
    } else {
        echo "<p>❌ Vue '$view' manquante: $path</p>";
    }
}

// 5. Vérifier les assets
echo "<h2>5. Assets</h2>";
$assets = [
    'CSS Admin' => 'public/build/assets/admin.css',
    'JS Admin' => 'public/build/assets/admin.js',
    'CSS App' => 'public/build/assets/app.css',
    'JS App' => 'public/build/assets/app.js'
];

foreach ($assets as $name => $path) {
    if (file_exists($path)) {
        echo "<p>✅ $name: $path</p>";
    } else {
        echo "<p>❌ $name manquant: $path</p>";
    }
}

// 6. Vérifier les permissions
echo "<h2>6. Permissions</h2>";
$directories = ['storage', 'bootstrap/cache', 'public/build'];
foreach ($directories as $dir) {
    if (is_writable($dir)) {
        echo "<p>✅ $dir est accessible en écriture</p>";
    } else {
        echo "<p>❌ $dir n'est pas accessible en écriture</p>";
    }
}

// 7. Test des contrôleurs
echo "<h2>7. Test des contrôleurs</h2>";
try {
    // Test AdminController
    $adminController = new \App\Http\Controllers\AdminController();
    echo "<p>✅ AdminController peut être instancié</p>";
} catch (Exception $e) {
    echo "<p>❌ Erreur AdminController: " . $e->getMessage() . "</p>";
}

try {
    // Test DashboardController
    $dashboardController = new \App\Http\Controllers\DashboardController();
    echo "<p>✅ DashboardController peut être instancié</p>";
} catch (Exception $e) {
    echo "<p>❌ Erreur DashboardController: " . $e->getMessage() . "</p>";
}

// 8. Vérifier les middlewares
echo "<h2>8. Middlewares</h2>";
$middlewares = [
    'AdminMiddleware' => 'App\Http\Middleware\AdminMiddleware',
    'FormateurMiddleware' => 'App\Http\Middleware\FormateurMiddleware',
    'ApprenantMiddleware' => 'App\Http\Middleware\ApprenantMiddleware'
];

foreach ($middlewares as $name => $class) {
    if (class_exists($class)) {
        echo "<p>✅ $name existe</p>";
    } else {
        echo "<p>❌ $name manquant</p>";
    }
}

echo "<h2>🎯 Recommandations</h2>";
echo "<ul>";
echo "<li>Si des tables sont manquantes, exécutez: <code>php artisan migrate</code></li>";
echo "<li>Si des assets sont manquants, exécutez: <code>npm run build</code></li>";
echo "<li>Si les permissions sont incorrectes, exécutez: <code>chmod -R 755 storage bootstrap/cache</code></li>";
echo "<li>Videz le cache avec: <code>php artisan config:clear && php artisan route:clear && php artisan view:clear</code></li>";
echo "</ul>";

echo "<p><strong>📝 Note:</strong> Supprimez ce fichier après le diagnostic pour des raisons de sécurité.</p>";
?>
