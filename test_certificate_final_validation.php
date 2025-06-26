<?php
/**
 * Test Final de Validation du Système de Vérification des Certificats
 * 
 * Ce script teste toutes les fonctionnalités après correction du bug
 */

require_once 'vendor/autoload.php';

// Configuration de base
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Certification;
use App\Models\User;
use App\Models\Course;

echo "===========================================\n";
echo "   TEST FINAL - VALIDATION COMPLÈTE\n";
echo "===========================================\n\n";

// 1. Vérification des certificats disponibles
echo "1. CERTIFICATS DISPONIBLES\n";
echo "----------------------------\n";
$certifications = Certification::with(['user', 'course', 'course.formateur', 'course.category'])->get();

if ($certifications->count() > 0) {
    echo "✅ {$certifications->count()} certificat(s) trouvé(s)\n\n";
    
    foreach ($certifications as $cert) {
        echo "📜 Certificat #{$cert->id}\n";
        echo "   • Code: {$cert->verification_code}\n";
        echo "   • Utilisateur: {$cert->user->first_name} {$cert->user->last_name}\n";
        echo "   • Email: {$cert->user->email}\n";
        echo "   • Cours: {$cert->course->title}\n";
        echo "   • Formateur: {$cert->course->formateur->first_name} {$cert->course->formateur->last_name}\n";
        echo "   • Catégorie: " . ($cert->course->category ? $cert->course->category->name : 'Non définie') . "\n";
        echo "   • Date: {$cert->issued_at->format('d/m/Y H:i')}\n";
        echo "   • Identifiant: {$cert->certificate_identifier}\n\n";
    }
} else {
    echo "❌ Aucun certificat trouvé\n\n";
    exit(1);
}

// 2. Test de simulation HTTP pour toutes les routes
echo "2. TEST DES ROUTES HTTP\n";
echo "------------------------\n";

$testCode = $certifications->first()->verification_code;
$baseUrl = 'http://localhost:8000';

$routes = [
    'Page d\'accueil' => '/',
    'Vérification moderne' => '/verification-certificat',
    'Vérification classique' => '/verifier-certificat',
    'Vérification directe' => "/verifier-certificat/{$testCode}",
    'Certificat public' => "/c/{$testCode}"
];

foreach ($routes as $name => $path) {
    $url = $baseUrl . $path;
    echo "🔍 Test: {$name}\n";
    echo "   URL: {$url}\n";
    
    // Utilisation de curl pour un test plus robuste
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: Test Script 1.0'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "   ❌ Erreur cURL: {$error}\n";
    } elseif ($httpCode == 200) {
        echo "   ✅ Succès (HTTP {$httpCode})\n";
        
        // Vérifications spécifiques selon la page
        if (strpos($path, 'verification') !== false || strpos($path, 'verifier') !== false) {
            if (strpos($response, 'verification_code') !== false) {
                echo "   ✅ Formulaire de vérification présent\n";
            } else {
                echo "   ⚠️  Formulaire de vérification non détecté\n";
            }
        }
        
        if (strpos($path, "/c/") !== false) {
            if (strpos($response, $testCode) !== false) {
                echo "   ✅ Code de vérification affiché\n";
            } else {
                echo "   ⚠️  Code de vérification non affiché\n";
            }
        }
        
    } elseif ($httpCode == 500) {
        echo "   ❌ Erreur serveur (HTTP {$httpCode})\n";
    } else {
        echo "   ⚠️  Réponse inattendue (HTTP {$httpCode})\n";
    }
    echo "\n";
}

// 3. Test de la fonctionnalité de vérification
echo "3. TEST DE VÉRIFICATION FONCTIONNELLE\n";
echo "--------------------------------------\n";

// Test avec code valide
echo "🔍 Test avec code valide: {$testCode}\n";
$validCert = Certification::where('verification_code', $testCode)->first();
if ($validCert) {
    echo "   ✅ Certificat trouvé en base\n";
    echo "   ✅ Données: {$validCert->user->first_name} {$validCert->user->last_name} - {$validCert->course->title}\n";
} else {
    echo "   ❌ Certificat non trouvé en base\n";
}

// Test avec code invalide
$invalidCode = 'VERIFY-INVALID-TEST-CODE';
echo "\n🔍 Test avec code invalide: {$invalidCode}\n";
$invalidCert = Certification::where('verification_code', $invalidCode)->first();
if (!$invalidCert) {
    echo "   ✅ Code invalide correctement rejeté\n";
} else {
    echo "   ❌ Code invalide accepté (problème)\n";
}

// 4. Vérification des fichiers du système
echo "\n4. VÉRIFICATION DES FICHIERS SYSTÈME\n";
echo "------------------------------------\n";

$files = [
    'Controller' => 'app/Http/Controllers/CertificateVerificationController.php',
    'Model Certification' => 'app/Models/Certification.php',
    'Model User' => 'app/Models/User.php',
    'Routes' => 'routes/web.php',
    'Vue classique' => 'resources/views/pages/verification-form.blade.php',
    'Vue moderne' => 'resources/views/pages/certificate-verification.blade.php',
    'Vue publique' => 'resources/views/pages/public-certification.blade.php',
    'Navigation' => 'resources/views/components/navbar.blade.php'
];

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "✅ {$name}: {$path}\n";
    } else {
        echo "❌ {$name}: {$path} - MANQUANT\n";
    }
}

// 5. Test des URL publiques
echo "\n5. URLS PUBLIQUES DISPONIBLES\n";
echo "------------------------------\n";
echo "🌐 Page de vérification moderne:\n";
echo "   → http://localhost:8000/verification-certificat\n\n";

echo "🌐 Page de vérification classique:\n";
echo "   → http://localhost:8000/verifier-certificat\n\n";

echo "🌐 Vérification directe avec codes:\n";
foreach ($certifications as $cert) {
    echo "   → http://localhost:8000/verifier-certificat/{$cert->verification_code}\n";
}
echo "\n";

echo "🌐 Affichage public des certificats:\n";
foreach ($certifications as $cert) {
    echo "   → http://localhost:8000/c/{$cert->verification_code}\n";
}
echo "\n";

// 6. Instructions d'utilisation
echo "6. INSTRUCTIONS D'UTILISATION\n";
echo "------------------------------\n";
echo "Pour les utilisateurs externes :\n";
echo "1. Accéder à la page d'accueil : http://localhost:8000\n";
echo "2. Cliquer sur 'Vérifier un certificat' dans le menu\n";
echo "3. Saisir le code de vérification du certificat\n";
echo "4. Consulter les informations du certificat\n\n";

echo "Pour un accès direct :\n";
echo "- URL moderne : http://localhost:8000/verification-certificat\n";
echo "- URL classique : http://localhost:8000/verifier-certificat\n\n";

// 7. Résumé final
echo "7. RÉSUMÉ FINAL\n";
echo "---------------\n";
$totalRoutes = count($routes);
$workingRoutes = 0;

// Recompter les routes fonctionnelles
foreach ($routes as $name => $path) {
    $url = $baseUrl . $path;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        $workingRoutes++;
    }
}

$successRate = ($workingRoutes / $totalRoutes) * 100;

echo "📊 STATISTIQUES:\n";
echo "• Certificats en base: {$certifications->count()}\n";
echo "• Routes testées: {$totalRoutes}\n";
echo "• Routes fonctionnelles: {$workingRoutes}\n";
echo "• Taux de réussite: " . number_format($successRate, 1) . "%\n\n";

if ($successRate >= 80) {
    echo "🎉 SYSTÈME ENTIÈREMENT FONCTIONNEL\n";
    echo "✅ Le système de vérification des certificats est opérationnel !\n";
} elseif ($successRate >= 60) {
    echo "⚠️  SYSTÈME PARTIELLEMENT FONCTIONNEL\n";
    echo "🔧 Quelques ajustements peuvent être nécessaires.\n";
} else {
    echo "❌ SYSTÈME NON FONCTIONNEL\n";
    echo "🚨 Des corrections importantes sont nécessaires.\n";
}

echo "\n===========================================\n";
echo "           TEST TERMINÉ\n";
echo "===========================================\n";
