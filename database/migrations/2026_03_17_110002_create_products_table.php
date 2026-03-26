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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Nom de l'aliment (ex: Yaourt)
            $table->string('brand');         // Marque (ex: Danone)
            $table->integer('calories');     // Énergie pour 100g
            $table->char('nutriscore', 1);   // Une seule lettre : A, B, C, D ou E
            $table->timestamps();            // Date de création/modification automatique
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
