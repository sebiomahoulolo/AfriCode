<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail; // Si email verification est activée
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname', // Ajouté
        'email',
        'password',
        'is_admin', // Ajouté (ou 'role')
        'avatar',   // Ajouté
        'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'is_admin' => 'boolean', 'password' => 'hashed']; // Cast is_admin et hash password

    // --- Relations ---
    public function coursesInstructed() { // Si un user peut être instructeur
        return $this->hasMany(Course::class, 'instructor_id');
    }
    public function payments() {
        return $this->hasMany(Payment::class);
    }
    public function certifications() {
        return $this->hasMany(Certification::class);
    }
    public function sentMessages() {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function receivedMessages() {
        return $this->hasMany(Message::class, 'recipient_id');
    }
    public function tasksAssigned() { // Tâches assignées à cet admin
        return $this->hasMany(Task::class, 'assigned_to_id');
    }
    // Ajoutez ici la relation pour les cours auxquels l'utilisateur est inscrit (ManyToMany) si nécessaire
}