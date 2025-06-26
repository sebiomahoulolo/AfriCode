<?php
/**
 * TEST FINAL - SYSTÈME DE VÉRIFICATION CERTIFICATS
 * 
 * Ce script teste le système après toutes les corrections
 */

require_once 'vendor/autoload.php';

// Configuration de base
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Certification;
use App\Models\User;
use App\Models\Course;

echo "==========================================\n";
echo "   TEST FINAL - APRÈS CORRECTIONS\n";
echo "==========================================\n\n";

// 1. Vérifier les certificats disponibles
echo "1. CERTIFICATS DISPONIBLES\n";
echo "----------------------------\n";

$certifications = Certification::with(['user', 'course', 'course.formateur', 'course.category'])->get();

if ($certifications->count() > 0) {
    echo "✅ {$certifications->count()} certificat(s) trouvé(s)\n\n";
    
    foreach ($certifications as $index => $cert) {
        echo "📜 Certificat #" . ($index + 1) . "\n";
        echo "   • Code: {$cert->verification_code}\n";
        echo "   • Utilisateur: {$cert->user->first_name} {$cert->user->last_name}\n";
        echo "   • Email: {$cert->user->email}\n";
        echo "   • Cours: {$cert->course->title}\n";
        echo "   • Formateur: " . ($cert->course->formateur ? $cert->course->formateur->first_name . ' ' . $cert->course->formateur->last_name : 'N/A') . "\n";
        echo "   • Catégorie: " . ($cert->course->category ? $cert->course->category->name : 'N/A') . "\n";
        echo "   • Date: {$cert->issued_at->format('d/m/Y H:i')}\n";
        echo "   • Identifiant: {$cert->certificate_identifier}\n\n";
    }
} else {
    echo "❌ Aucun certificat trouvé\n";
    exit(1);
}

// Sélectionner le premier certificat pour les tests
$testCert = $certifications->first();
$testCode = $testCert->verification_code;

echo "2. TEST DES ROUTES HTTP\n";
echo "------------------------\n";

// Fonction pour tester une URL
function testUrl($url, $description = '') {
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'method' => 'GET',
            'ignore_errors' => true
        ]
    ]);
    
    $result = @file_get_contents($url, false, $context);
    $headers = $http_response_header ?? [];
    
    $status = 'unknown';
    if (!empty($headers)) {
        $status_line = $headers[0];
        if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $status_line, $matches)) {
            $status = $matches[1];
        }
    }
    
    $success = $result !== false && $status === '200';
    $icon = $success ? "✅" : "❌";
    $statusText = $success ? "Succès (HTTP {$status})" : "Échec (HTTP {$status})";
    
    echo "🔍 Test: {$description}\n";
    echo "   URL: {$url}\n";
    echo "   {$icon} {$statusText}\n";
    
    if ($success && strlen($result) > 0) {
        // Vérifier la présence de contenu spécifique
        if (strpos($result, 'verification_code') !== false) {
            echo "   ✅ Formulaire de vérification présent\n";
        }
        if (strpos($result, 'CSRF') !== false || strpos($result, 'csrf') !== false) {
            echo "   ✅ Protection CSRF présente\n";
        }
    }
    
    echo "\n";
    return $success;
}

// Tests des URLs
$tests = [
    [
        'url' => 'http://localhost:8000/',
        'description' => 'Page d\'accueil'
    ],
    [
        'url' => 'http://localhost:8000/verification-certificat',
        'description' => 'Page de vérification moderne'
    ],
    [
        'url' => 'http://localhost:8000/verifier-certificat',
        'description' => 'Page de vérification classique'
    ],
    [
        'url' => "http://localhost:8000/verifier-certificat/{$testCode}",
        'description' => 'Vérification directe avec code'
    ],
    [
        'url' => "http://localhost:8000/c/{$testCode}",
        'description' => 'Affichage public du certificat'
    ]
];

$totalTests = count($tests);
$successfulTests = 0;

foreach ($tests as $test) {
    if (testUrl($test['url'], $test['description'])) {
        $successfulTests++;
    }
}

echo "3. VÉRIFICATION DES COMPOSANTS\n";
echo "------------------------------\n";

// Vérifier les fichiers critiques
$criticalFiles = [
    'app/Http/Controllers/CertificateVerificationController.php' => 'Contrôleur principal',
    'app/Models/Certification.php' => 'Modèle Certification',
    'app/Models/User.php' => 'Modèle User',
    'resources/views/pages/verification-form.blade.php' => 'Vue formulaire classique',
    'resources/views/pages/certificate-verification.blade.php' => 'Vue moderne',
    'resources/views/pages/public-certification.blade.php' => 'Vue certificat public',
    'resources/views/layouts/navigation.blade.php' => 'Navigation'
];

foreach ($criticalFiles as $file => $description) {
    if (file_exists($file)) {
        echo "✅ {$description}: {$file}\n";
    } else {
        echo "❌ {$description}: {$file} - MANQUANT\n";
    }
}

echo "\n4. VÉRIFICATION DES ROUTES\n";
echo "--------------------------\n";

// Vérifier le fichier routes/web.php
$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $routesContent = file_get_contents($routesFile);
    
    $requiredRoutes = [
        'verification.form' => 'Route de vérification classique',
        'certificate.verification' => 'Route de vérification moderne',
        'public.certificate.show' => 'Route d\'affichage public'
    ];
    
    foreach ($requiredRoutes as $routeName => $description) {
        if (strpos($routesContent, $routeName) !== false) {
            echo "✅ {$description}: {$routeName}\n";
        } else {
            echo "❌ {$description}: {$routeName} - MANQUANTE\n";
        }
    }
} else {
    echo "❌ Fichier routes/web.php non trouvé\n";
}

echo "\n5. TEST DE VÉRIFICATION FONCTIONNELLE\n";
echo "--------------------------------------\n";

// Test avec code valide
echo "🔍 Test avec code valide: {$testCode}\n";
$validCert = Certification::where('verification_code', $testCode)->first();
if ($validCert) {
    echo "✅ Certificat trouvé en base\n";
    echo "   ✅ Utilisateur: {$validCert->user->first_name} {$validCert->user->last_name}\n";
    echo "   ✅ Cours: {$validCert->course->title}\n";
} else {
    echo "❌ Certificat non trouvé\n";
}

// Test avec code invalide
echo "\n🔍 Test avec code invalide: VERIFY-INVALID-TEST-CODE\n";
$invalidCert = Certification::where('verification_code', 'VERIFY-INVALID-TEST-CODE')->first();
if (!$invalidCert) {
    echo "✅ Code invalide correctement rejeté\n";
} else {
    echo "❌ Code invalide accepté (problème de sécurité)\n";
}

echo "\n6. RÉSUMÉ FINAL\n";
echo "===============\n";

$successRate = ($successfulTests / $totalTests) * 100;

echo "📊 STATISTIQUES:\n";
echo "• Certificats en base: {$certifications->count()}\n";
echo "• Routes testées: {$totalTests}\n";
echo "• Routes fonctionnelles: {$successfulTests}\n";
echo "• Taux de réussite: " . round($successRate, 1) . "%\n\n";

if ($successRate >= 80) {
    echo "✅ SYSTÈME FONCTIONNEL\n";
    echo "🎉 Le système de vérification des certificats fonctionne correctement!\n";
} elseif ($successRate >= 60) {
    echo "⚠️  SYSTÈME PARTIELLEMENT FONCTIONNEL\n";
    echo "⚡ Quelques améliorations peuvent être nécessaires.\n";
} else {
    echo "❌ SYSTÈME NON FONCTIONNEL\n";
    echo "🚨 Des corrections importantes sont nécessaires.\n";
}

echo "\n🔗 LIENS DE TEST DISPONIBLES:\n";
echo "• Vérification moderne: http://localhost:8000/verification-certificat\n";
echo "• Vérification classique: http://localhost:8000/verifier-certificat\n";
foreach ($certifications as $cert) {
    echo "• Test avec code {$cert->verification_code}: http://localhost:8000/verifier-certificat/{$cert->verification_code}\n";
    echo "• Certificat public {$cert->verification_code}: http://localhost:8000/c/{$cert->verification_code}\n";
}

echo "\n📋 CODES DE TEST DISPONIBLES:\n";
foreach ($certifications as $cert) {
    echo "• {$cert->verification_code} → {$cert->user->first_name} {$cert->user->last_name} → {$cert->course->title}\n";
}

echo "\n==========================================\n";
echo "           TEST TERMINÉ\n";
echo "==========================================\n";
