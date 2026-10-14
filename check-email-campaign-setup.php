<?php

/**
 * Script de vérification de la configuration de la campagne email
 * Exécutez : php check-email-campaign-setup.php
 */

echo "🔍 Vérification de la configuration de la campagne email...\n\n";

$errors = [];
$warnings = [];
$success = [];

// 1. Vérifier les fichiers créés
$files = [
    'app/Mail/NewCourseNotification.php' => 'Classe Mailable',
    'app/Jobs/SendNewCourseEmails.php' => 'Job d\'envoi d\'emails',
    'app/Events/CoursePublished.php' => 'Événement CoursePublished',
    'app/Listeners/SendNewCourseEmailCampaign.php' => 'Listener de campagne',
    'app/Console/Commands/SendCourseEmailCampaign.php' => 'Commande Artisan',
    'resources/views/emails/courses/new-course.blade.php' => 'Template email',
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        $success[] = "✅ {$description} : {$file}";
    } else {
        $errors[] = "❌ {$description} manquant : {$file}";
    }
}

// 2. Vérifier les routes
$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    if (strpos($content, 'email-campaigns') !== false) {
        $success[] = "✅ Routes admin pour les campagnes email ajoutées";
    } else {
        $errors[] = "❌ Routes admin pour les campagnes email manquantes";
    }
}

// 3. Vérifier EventServiceProvider
$eventProvider = 'app/Providers/EventServiceProvider.php';
if (file_exists($eventProvider)) {
    $content = file_get_contents($eventProvider);
    if (strpos($content, 'CoursePublished') !== false && strpos($content, 'SendNewCourseEmailCampaign') !== false) {
        $success[] = "✅ EventServiceProvider configuré";
    } else {
        $errors[] = "❌ EventServiceProvider non configuré correctement";
    }
}

// 4. Vérifier la configuration de la queue
if (file_exists('.env')) {
    $env = file_get_contents('.env');
    if (strpos($env, 'QUEUE_CONNECTION') !== false) {
        preg_match('/QUEUE_CONNECTION=(.+)/', $env, $matches);
        if (!empty($matches[1])) {
            $queueConnection = trim($matches[1]);
            if ($queueConnection === 'database' || $queueConnection === 'redis') {
                $success[] = "✅ Queue configurée : {$queueConnection}";
            } else {
                $warnings[] = "⚠️  Queue configurée en mode : {$queueConnection} (recommandé: database ou redis)";
            }
        }
    } else {
        $warnings[] = "⚠️  QUEUE_CONNECTION non défini dans .env";
    }
} else {
    $warnings[] = "⚠️  Fichier .env non trouvé";
}

// 5. Vérifier la configuration email
if (file_exists('.env')) {
    $env = file_get_contents('.env');
    $mailConfigs = ['MAIL_MAILER', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD'];
    $mailConfigured = true;
    foreach ($mailConfigs as $config) {
        if (strpos($env, $config) === false) {
            $mailConfigured = false;
            break;
        }
    }
    if ($mailConfigured) {
        $success[] = "✅ Configuration email présente dans .env";
    } else {
        $warnings[] = "⚠️  Configuration email incomplète dans .env";
    }
}

// 6. Vérifier les tables de queue
try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    $tables = ['jobs', 'failed_jobs', 'job_batches'];
    foreach ($tables as $table) {
        try {
            \Illuminate\Support\Facades\Schema::hasTable($table);
            $success[] = "✅ Table {$table} existe";
        } catch (\Exception $e) {
            $errors[] = "❌ Table {$table} manquante : " . $e->getMessage();
        }
    }
} catch (\Exception $e) {
    $warnings[] = "⚠️  Impossible de vérifier les tables (Laravel non initialisé)";
}

// Afficher les résultats
echo "📋 RÉSULTATS DE LA VÉRIFICATION\n";
echo str_repeat("=", 50) . "\n\n";

if (!empty($success)) {
    echo "✅ SUCCÈS :\n";
    foreach ($success as $msg) {
        echo "   {$msg}\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  AVERTISSEMENTS :\n";
    foreach ($warnings as $msg) {
        echo "   {$msg}\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ ERREURS :\n";
    foreach ($errors as $msg) {
        echo "   {$msg}\n";
    }
    echo "\n";
}

// Résumé
$total = count($success) + count($warnings) + count($errors);
$successCount = count($success);

echo str_repeat("=", 50) . "\n";
echo "📊 RÉSUMÉ : {$successCount}/{$total} vérifications réussies\n\n";

if (empty($errors)) {
    echo "🎉 Configuration complète ! Vous pouvez maintenant :\n";
    echo "   1. Lancer le worker : php artisan queue:work\n";
    echo "   2. Tester une campagne : php artisan course:send-campaign {course_id}\n";
    echo "   3. Publier un cours pour déclencher automatiquement la campagne\n";
} else {
    echo "⚠️  Veuillez corriger les erreurs ci-dessus avant d'utiliser le système.\n";
}

