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
            // Ajouter les champs manquants seulement s'ils n'existent pas
            if (!Schema::hasColumn('courses', 'content')) {
                $table->longText('content')->nullable()->after('full_description');
            }
            if (!Schema::hasColumn('courses', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('content');
            }
            if (!Schema::hasColumn('courses', 'tags')) {
                $table->text('tags')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('courses', 'price_fcfa')) {
                $table->decimal('price_fcfa', 12, 2)->nullable()->after('currency');
            }
            if (!Schema::hasColumn('courses', 'discounted_price')) {
                $table->decimal('discounted_price', 12, 2)->nullable()->after('price_fcfa');
            }
            if (!Schema::hasColumn('courses', 'discount_starts_at')) {
                $table->timestamp('discount_starts_at')->nullable()->after('discounted_price');
            }
            if (!Schema::hasColumn('courses', 'discount_ends_at')) {
                $table->timestamp('discount_ends_at')->nullable()->after('discount_starts_at');
            }
            if (!Schema::hasColumn('courses', 'duration')) {
                $table->decimal('duration', 8, 2)->nullable()->after('discount_ends_at');
            }
            if (!Schema::hasColumn('courses', 'language')) {
                $table->enum('language', ['fr', 'en', 'ar', 'wo'])->default('fr')->after('duration');
            }
            if (!Schema::hasColumn('courses', 'preview_video')) {
                $table->string('preview_video')->nullable()->after('cover_image_path');
            }
            if (!Schema::hasColumn('courses', 'is_premium')) {
                $table->boolean('is_premium')->default(false)->after('is_certifying');
            }
            if (!Schema::hasColumn('courses', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_premium');
            }
            
            // Modifier la colonne level pour correspondre aux nouvelles valeurs
            if (Schema::hasColumn('courses', 'level')) {
                $table->dropColumn('level');
            }
        });
        
        // Ajouter la nouvelle colonne level avec les bonnes valeurs
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'level')) {
                $table->enum('level', ['débutant', 'intermédiaire', 'avancé', 'expert'])->default('débutant')->after('language');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Supprimer les champs ajoutés seulement s'ils existent
            $columnsToRemove = [];
            
            if (Schema::hasColumn('courses', 'content')) {
                $columnsToRemove[] = 'content';
            }
            if (Schema::hasColumn('courses', 'meta_title')) {
                $columnsToRemove[] = 'meta_title';
            }
            if (Schema::hasColumn('courses', 'tags')) {
                $columnsToRemove[] = 'tags';
            }
            if (Schema::hasColumn('courses', 'price_fcfa')) {
                $columnsToRemove[] = 'price_fcfa';
            }
            if (Schema::hasColumn('courses', 'discounted_price')) {
                $columnsToRemove[] = 'discounted_price';
            }
            if (Schema::hasColumn('courses', 'discount_starts_at')) {
                $columnsToRemove[] = 'discount_starts_at';
            }
            if (Schema::hasColumn('courses', 'discount_ends_at')) {
                $columnsToRemove[] = 'discount_ends_at';
            }
            if (Schema::hasColumn('courses', 'duration')) {
                $columnsToRemove[] = 'duration';
            }
            if (Schema::hasColumn('courses', 'language')) {
                $columnsToRemove[] = 'language';
            }
            if (Schema::hasColumn('courses', 'preview_video')) {
                $columnsToRemove[] = 'preview_video';
            }
            if (Schema::hasColumn('courses', 'is_premium')) {
                $columnsToRemove[] = 'is_premium';
            }
            if (Schema::hasColumn('courses', 'is_featured')) {
                $columnsToRemove[] = 'is_featured';
            }
            if (Schema::hasColumn('courses', 'level')) {
                $columnsToRemove[] = 'level';
            }
            
            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
        
        // Restaurer l'ancienne colonne level
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'level')) {
                $table->enum('level', ['debutant', 'intermediaire', 'avance', 'tous_niveaux'])->default('debutant');
            }
        });
    }
};
