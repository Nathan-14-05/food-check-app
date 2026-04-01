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
        Schema::create('food_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Le nom de la liste (ex: "Mes courses", "Frigo")
            // On crée le lien avec l'utilisateur (Clé étrangère)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_lists');
    }
};
