<?php

use App\Models\User;
use App\Notifications\InactiveUserReminder;
use App\Notifications\LearningHelpNotification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Notifie les apprenants en difficulté chaque nuit à 2h
Schedule::call(function () {
    User::all()->each(function ($user) {
        if ($user->hasLearningDifficulties()) {
            $user->notify(new LearningHelpNotification());
        }
    });
})->dailyAt('02:00');

// Rappel aux utilisateurs inactifs (7 jours) via notifications in-app, chaque jour à 9h
Schedule::call(function () {
    User::all()->each(function ($user) {
        if ($user->isInactive(7)) {
            $user->notify(new InactiveUserReminder());
        }
    });
})->dailyAt('09:00');

// Rappel push d'inactivité (7 jours) → tous les jours à 10h
Schedule::command('push:inactivity-reminder 7')->dailyAt('10:00');

// Rappel push hebdomadaire (3 jours d'inactivité) → chaque lundi à 08h30
Schedule::command('push:inactivity-reminder 3')->weeklyOn(1, '08:30');
