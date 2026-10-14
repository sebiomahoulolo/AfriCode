<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'formateur_status')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            // Statut du formateur
            $table->enum('formateur_status', ['candidat', 'actif', 'suspendu', 'inactif', 'banni'])->nullable()->after('role');
            
            // Badge actuel
            $table->enum('badge_level', ['bronze', 'argent', 'or', 'platine'])->nullable()->after('formateur_status');
            
            // Statistiques formateur
            $table->integer('total_courses_created')->default(0)->after('badge_level');
            $table->integer('total_students')->default(0)->after('total_courses_created');
            $table->decimal('total_earnings', 12, 2)->default(0)->after('total_students');
            $table->decimal('average_rating', 3, 2)->default(0)->after('total_earnings');
            
            // Informations de paiement
            $table->string('payment_method')->nullable()->after('average_rating'); // mobile_money, card
            $table->string('payment_account')->nullable()->after('payment_method'); // Numéro de compte
            $table->decimal('pending_earnings', 12, 2)->default(0)->after('payment_account');
            $table->decimal('withdrawal_threshold', 10, 2)->default(25000)->after('pending_earnings'); // 25000 FCFA
            
            // Onboarding
            $table->boolean('onboarding_completed')->default(false)->after('withdrawal_threshold');
            $table->timestamp('onboarding_completed_at')->nullable()->after('onboarding_completed');
            
            // Contrat
            $table->timestamp('contract_signed_at')->nullable()->after('onboarding_completed_at');
            $table->string('contract_file_path')->nullable()->after('contract_signed_at');
            
            // Dernière activité
            $table->timestamp('last_course_created_at')->nullable()->after('contract_file_path');
            $table->timestamp('last_login_at')->nullable()->after('last_course_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'formateur_status',
                'badge_level',
                'total_courses_created',
                'total_students',
                'total_earnings',
                'average_rating',
                'payment_method',
                'payment_account',
                'pending_earnings',
                'withdrawal_threshold',
                'onboarding_completed',
                'onboarding_completed_at',
                'contract_signed_at',
                'contract_file_path',
                'last_course_created_at',
                'last_login_at',
            ]);
        });
    }
};
