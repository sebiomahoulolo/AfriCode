<?php
/**
 * Test Final du Système de Vérification des Certificats
 * 
 * Ce script effectue un test complet et génère un rapport détaillé
 */

require_once 'vendor/autoload.php';

// Configuration Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Certification;
use App\Models\User;
use App\Models\Course;

echo "==========================================\n";
echo "   TEST FINAL - VÉRIFICATION CERTIFICATS\n";
echo "==========================================\n\n";

// Fonction pour tester une URL
function testUrl($url, $description) {
    echo "🔍 Test: {$description}\n";
    echo "   URL: {$url}\n";
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'method' => 'GET',
            'header' => "User-Agent: Certificate-Test-Script\r\n"
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response !== false && !empty($response)) {
        echo "   ✅ Succès - Page accessible\n";
        
        // Vérifications spécifiques selon le type de page
        if (strpos($url, 'verification') !== false) {
            if (strpos($response, 'verification_code') !== false) {
                echo "   ✅ Formulaire de vérification présent\n";
            } else {
                echo "   ⚠️  Formulaire de vérification non détecté\n";
            }
        }
        
        if (strpos($url, '/c/VERIFY-') !== false) {
            if (strpos($response, 'Moussa Diallo') !== false || strpos($response, 'certificat') !== false) {
                echo "   ✅ Données du certificat affichées\n";
            } else {
                echo "   ⚠️  Données du certificat non détectées\n";
            }
        }
        
        return true;
    } else {
        echo "   ❌ Échec - Page non accessible\n";
        return false;
    }
}

// Test des données
echo "1. VÉRIFICATION DES DONNÉES\n";
echo "----------------------------\n";

$certifications = Certification::with(['user', 'course', 'course.formateur', 'course.category'])->get();
echo "📊 Certificats en base: {$certifications->count()}\n";

if ($certifications->count() > 0) {
    $testCert = $certifications->first();
    echo "🎯 Certificat de test: {$testCert->verification_code}\n";
    echo "👤 Utilisateur: {$testCert->user->first_name} {$testCert->user->last_name}\n";
    echo "📚 Cours: {$testCert->course->title}\n";
    echo "📅 Date: {$testCert->issued_at->format('d/m/Y H:i')}\n";
    echo "🆔 Identifiant: {$testCert->certificate_identifier}\n\n";
} else {
    echo "❌ Aucun certificat de test disponible\n\n";
    exit(1);
}

// Test des routes
echo "2. TEST DES ROUTES ET PAGES\n";
echo "----------------------------\n";

$baseUrl = "http://localhost:8000";
$testCode = $testCert->verification_code;

$urlTests = [
    "{$baseUrl}" => "Page d'accueil",
    "{$baseUrl}/verification-certificat" => "Page de vérification moderne",
    "{$baseUrl}/verifier-certificat" => "Page de vérification classique",
    "{$baseUrl}/verifier-certificat/{$testCode}" => "Vérification directe avec code",
    "{$baseUrl}/c/{$testCode}" => "Affichage public du certificat"
];

$successCount = 0;
$totalTests = count($urlTests);

foreach ($urlTests as $url => $description) {
    if (testUrl($url, $description)) {
        $successCount++;
    }
    echo "\n";
}

// Test des fichiers de vues
echo "3. VÉRIFICATION DES VUES\n";
echo "------------------------\n";

$viewFiles = [
    'resources/views/pages/verification-form.blade.php' => 'Vue formulaire classique',
    'resources/views/pages/certificate-verification.blade.php' => 'Vue moderne',
    'resources/views/pages/public-certification.blade.php' => 'Vue certificat public'
];

foreach ($viewFiles as $file => $description) {
    if (file_exists($file)) {
        echo "✅ {$description}: {$file}\n";
        
        // Vérifier le contenu
        $content = file_get_contents($file);
        if (strpos($content, 'verification_code') !== false) {
            echo "   ✅ Contient le champ verification_code\n";
        }
        if (strpos($content, 'csrf') !== false || strpos($content, '@csrf') !== false) {
            echo "   ✅ Protection CSRF présente\n";
        }
    } else {
        echo "❌ {$description}: fichier manquant\n";
    }
    echo "\n";
}

// Test du contrôleur
echo "4. VÉRIFICATION DU CONTRÔLEUR\n";
echo "------------------------------\n";

$controllerFile = 'app/Http/Controllers/CertificateVerificationController.php';
if (file_exists($controllerFile)) {
    echo "✅ Contrôleur trouvé: {$controllerFile}\n";
    
    $controllerContent = file_get_contents($controllerFile);
    
    $methods = [
        'verifyCertificate' => 'Méthode de vérification',
        'showPublicCertificate' => 'Affichage public'
    ];
    
    foreach ($methods as $method => $description) {
        if (strpos($controllerContent, "function {$method}") !== false) {
            echo "✅ {$description}: méthode {$method} présente\n";
        } else {
            echo "❌ {$description}: méthode {$method} manquante\n";
        }
    }
} else {
    echo "❌ Contrôleur manquant\n";
}

echo "\n";

// Test de la navigation
echo "5. VÉRIFICATION DE LA NAVIGATION\n";
echo "--------------------------------\n";

$navbarFile = 'resources/views/components/navbar.blade.php';
if (file_exists($navbarFile)) {
    echo "✅ Navbar trouvée: {$navbarFile}\n";
    
    $navbarContent = file_get_contents($navbarFile);
    
    if (strpos($navbarContent, 'verification') !== false) {
        echo "✅ Lien de vérification présent dans la navbar\n";
    } else {
        echo "⚠️  Lien de vérification non détecté dans la navbar\n";
    }
} else {
    echo "❌ Navbar manquante\n";
}

echo "\n";

// Résumé final
echo "6. RÉSUMÉ FINAL\n";
echo "===============\n";

$percentage = round(($successCount / $totalTests) * 100);

echo "📊 STATISTIQUES:\n";
echo "• Routes testées: {$totalTests}\n";
echo "• Routes fonctionnelles: {$successCount}\n";
echo "• Taux de réussite: {$percentage}%\n\n";

if ($percentage >= 80) {
    echo "🎉 SYSTÈME OPÉRATIONNEL\n";
    echo "Le système de vérification des certificats fonctionne correctement!\n\n";
} else {
    echo "⚠️  SYSTÈME PARTIELLEMENT FONCTIONNEL\n";
    echo "Certaines routes nécessitent une attention particulière.\n\n";
}

echo "🔗 LIENS DE TEST:\n";
echo "• Vérification moderne: {$baseUrl}/verification-certificat\n";
echo "• Vérification classique: {$baseUrl}/verifier-certificat\n";
echo "• Test avec code: {$baseUrl}/verifier-certificat/{$testCode}\n";
echo "• Certificat public: {$baseUrl}/c/{$testCode}\n\n";

echo "📋 CODES DE TEST DISPONIBLES:\n";
foreach ($certifications as $cert) {
    echo "• {$cert->verification_code} → {$cert->user->first_name} {$cert->user->last_name} → {$cert->course->title}\n";
}

echo "\n✅ Test terminé avec succès!\n";
echo "==========================================\n";
