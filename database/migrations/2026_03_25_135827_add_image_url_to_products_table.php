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
        Schema::table('products', function (Blueprint $table) {
            // On ajoute image_url, et on précise que c'est optionnel (nullable)
            // Comme ça, si l'image n'est pas trouvée, ça ne fait pas planter le site.
            $table->string('image_url')->nullable()->after('nutriscore');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Pour pouvoir annuler la migration si besoin
            $table->dropColumn('image_url');
        });
    }
};
