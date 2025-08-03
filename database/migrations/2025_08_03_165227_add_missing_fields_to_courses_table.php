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
        Schema::table('courses', function (Blueprint $table) {
            // Ajouter les champs manquants
            $table->longText('content')->nullable()->after('full_description');
            $table->string('meta_title')->nullable()->after('content');
            $table->text('tags')->nullable()->after('meta_title');
            $table->decimal('price_fcfa', 12, 2)->nullable()->after('currency');
            $table->decimal('discounted_price', 12, 2)->nullable()->after('price_fcfa');
            $table->timestamp('discount_starts_at')->nullable()->after('discounted_price');
            $table->timestamp('discount_ends_at')->nullable()->after('discount_starts_at');
            $table->decimal('duration', 8, 2)->nullable()->after('discount_ends_at');
            $table->enum('language', ['fr', 'en', 'ar', 'wo'])->default('fr')->after('duration');
            $table->string('preview_video')->nullable()->after('cover_image_path');
            $table->boolean('is_premium')->default(false)->after('is_certifying');
            $table->boolean('is_featured')->default(false)->after('is_premium');
            
            // Modifier la colonne level pour correspondre aux nouvelles valeurs
            $table->dropColumn('level');
        });
        
        // Ajouter la nouvelle colonne level avec les bonnes valeurs
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('level', ['débutant', 'intermédiaire', 'avancé', 'expert'])->default('débutant')->after('language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Supprimer les champs ajoutés
            $table->dropColumn([
                'content',
                'meta_title', 
                'tags',
                'price_fcfa',
                'discounted_price',
                'discount_starts_at',
                'discount_ends_at',
                'duration',
                'language',
                'preview_video',
                'is_premium',
                'is_featured',
                'level'
            ]);
        });
        
        // Restaurer l'ancienne colonne level
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('level', ['debutant', 'intermediaire', 'avance', 'tous_niveaux'])->default('debutant');
        });
    }
};
