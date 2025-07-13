<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DES BADGES ===\n\n";

// Vérifier les badges attribués
echo "Badges attribués aux utilisateurs:\n";
$badgeUsers = DB::table('badge_user')
    ->join('badges', 'badge_user.badge_id', '=', 'badges.id')
    ->join('users', 'badge_user.user_id', '=', 'users.id')
    ->select('users.name', 'badges.name as badge_name', 'badges.icon', 'badges.color')
    ->orderBy('users.name')
    ->get();

foreach($badgeUsers as $badgeUser) {
    echo "- {$badgeUser->name}: {$badgeUser->badge_name} ({$badgeUser->icon}, {$badgeUser->color})\n";
}

echo "\nUtilisateurs avec badges:\n";
$usersWithBadges = DB::table('users')
    ->join('badge_user', 'users.id', '=', 'badge_user.user_id')
    ->select('users.name', DB::raw('COUNT(badge_user.badge_id) as badge_count'))
    ->groupBy('users.id', 'users.name')
    ->orderBy('badge_count', 'desc')
    ->get();

foreach($usersWithBadges as $user) {
    echo "- {$user->name}: {$user->badge_count} badge(s)\n";
}

echo "\n=== FIN DU TEST ===\n"; 