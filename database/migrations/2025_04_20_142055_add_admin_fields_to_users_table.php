<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->after('name')->nullable(); // Ajouter prénom
            $table->boolean('is_admin')->after('password')->default(false); // Rôle admin simple
            // Ou utilisez une chaîne pour des rôles plus complexes:
            // $table->string('role')->after('password')->default('user'); // user, admin, instructor
            $table->string('avatar')->nullable()->after('is_admin'); // Chemin avatar
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['surname', 'is_admin', 'avatar']);
             // $table->dropColumn(['surname', 'role', 'avatar']); // Si vous utilisez 'role'
        });
    }
};