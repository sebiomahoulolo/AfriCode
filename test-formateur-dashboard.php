<?php
/**
 * Script de test pour le dashboard formateur
 * À exécuter pour vérifier pourquoi les cours n'apparaissent pas
 */

require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h1>🔍 Test Dashboard Formateur</h1>";

try {
    // Test 1: Vérifier la connexion à la base de données
    echo "<h2>1. Test de connexion à la base de données</h2>";
    $pdo = DB::connection()->getPdo();
    echo "✅ Connexion à la base de données réussie<br>";
    
    // Test 2: Vérifier les tables
    echo "<h2>2. Vérification des tables</h2>";
    $tables = ['users', 'courses', 'enrollments', 'payments', 'ratings'];
    foreach ($tables as $table) {
        $exists = DB::select("SHOW TABLES LIKE '$table'");
        if (count($exists) > 0) {
            echo "✅ Table '$table' existe<br>";
        } else {
            echo "❌ Table '$table' manquante<br>";
        }
    }
    
    // Test 3: Vérifier les utilisateurs formateurs
    echo "<h2>3. Vérification des formateurs</h2>";
    $formateurs = DB::table('users')->where('role', 'formateur')->get();
    echo "📊 Nombre de formateurs: " . $formateurs->count() . "<br>";
    
    if ($formateurs->count() > 0) {
        $formateur = $formateurs->first();
        echo "👤 Premier formateur: " . $formateur->first_name . " " . $formateur->last_name . " (ID: " . $formateur->id . ")<br>";
        
        // Test 4: Vérifier les cours du formateur
        echo "<h2>4. Vérification des cours du formateur</h2>";
        $courses = DB::table('courses')->where('formateur_id', $formateur->id)->get();
        echo "📚 Nombre de cours: " . $courses->count() . "<br>";
        
        if ($courses->count() > 0) {
            echo "📖 Cours trouvés:<br>";
            foreach ($courses as $course) {
                echo "- " . $course->title . " (Status: " . $course->status . ")<br>";
            }
        } else {
            echo "⚠️ Aucun cours trouvé pour ce formateur<br>";
        }
        
        // Test 5: Vérifier les inscriptions
        echo "<h2>5. Vérification des inscriptions</h2>";
        $enrollments = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('courses.formateur_id', $formateur->id)
            ->count();
        echo "👥 Nombre d'inscriptions: " . $enrollments . "<br>";
        
        // Test 6: Vérifier les paiements
        echo "<h2>6. Vérification des paiements</h2>";
        $payments = DB::table('payments')
            ->where('payable_type', 'App\\Models\\Course')
            ->whereIn('payable_id', $courses->pluck('id'))
            ->where('status', 'succeeded')
            ->sum('amount');
        echo "💰 Revenus totaux: " . $payments . "€<br>";
        
        // Test 7: Vérifier les notes
        echo "<h2>7. Vérification des notes</h2>";
        $ratings = DB::table('ratings')
            ->join('courses', 'ratings.course_id', '=', 'courses.id')
            ->where('courses.formateur_id', $formateur->id)
            ->avg('rating');
        echo "⭐ Note moyenne: " . round($ratings, 2) . "<br>";
        
    } else {
        echo "❌ Aucun formateur trouvé dans la base de données<br>";
    }
    
    // Test 8: Vérifier les modèles Laravel
    echo "<h2>8. Test des modèles Laravel</h2>";
    try {
        $user = \App\Models\User::where('role', 'formateur')->first();
        if ($user) {
            echo "✅ Modèle User fonctionne<br>";
            
            $courses = \App\Models\Course::where('formateur_id', $user->id)->get();
            echo "✅ Modèle Course fonctionne (cours trouvés: " . $courses->count() . ")<br>";
            
            $enrollments = \App\Models\Enrollment::whereIn('course_id', $courses->pluck('id'))->count();
            echo "✅ Modèle Enrollment fonctionne (inscriptions: " . $enrollments . ")<br>";
        }
    } catch (Exception $e) {
        echo "❌ Erreur avec les modèles Laravel: " . $e->getMessage() . "<br>";
    }
    
    // Test 9: Vérifier les routes
    echo "<h2>9. Vérification des routes</h2>";
    $routes = [
        'formateur.dashboard' => '/formateur/dashboard',
        'formateur.courses.create' => '/formateur/cours/creer',
        'formateur.courses.index' => '/formateur/cours'
    ];
    
    foreach ($routes as $name => $url) {
        try {
            $route = route($name);
            echo "✅ Route '$name': $route<br>";
        } catch (Exception $e) {
            echo "❌ Route '$name' non trouvée: " . $e->getMessage() . "<br>";
        }
    }
    
    echo "<h2>🎯 Recommandations</h2>";
    echo "<ul>";
    echo "<li>Si aucun formateur n'est trouvé, créez un utilisateur avec le rôle 'formateur'</li>";
    echo "<li>Si aucun cours n'est trouvé, créez des cours via l'interface admin</li>";
    echo "<li>Vérifiez que les relations entre les modèles sont correctement définies</li>";
    echo "<li>Vérifiez les permissions sur les dossiers storage/ et bootstrap/cache/</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h2>❌ Erreur générale</h2>";
    echo "Erreur: " . $e->getMessage() . "<br>";
    echo "Fichier: " . $e->getFile() . "<br>";
    echo "Ligne: " . $e->getLine() . "<br>";
}

echo "<p><strong>📝 Note:</strong> Supprimez ce fichier après le test pour des raisons de sécurité.</p>";
?>
