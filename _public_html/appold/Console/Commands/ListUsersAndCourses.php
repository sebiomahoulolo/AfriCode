<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;

class ListUsersAndCourses extends Command
{
    protected $signature = 'list:users-courses';
    protected $description = 'Liste les utilisateurs et cours disponibles';

    public function handle()
    {
        $this->info('👥 Utilisateurs:');
        $users = User::all(['id', 'first_name', 'last_name', 'email']);
        foreach ($users as $user) {
            $this->line("  {$user->id}: {$user->first_name} {$user->last_name} ({$user->email})");
        }
        
        $this->newLine();
        $this->info('📚 Cours:');
        $courses = Course::all(['id', 'title', 'slug']);
        foreach ($courses as $course) {
            $this->line("  {$course->id}: {$course->title} ({$course->slug})");
        }
        
        return 0;
    }
}
