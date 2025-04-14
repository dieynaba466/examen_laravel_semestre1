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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom');  // Nom de la catégorie
            $table->timestamps();
        });

        // Ajouter une colonne 'categorie_id' à la table 'livres' pour lier chaque livre à une catégorie
        Schema::table('livres', function (Blueprint $table) {
            $table->foreignId('categorie_id')->nullable()->constrained()->onDelete('set null')->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            $table->dropColumn('categorie_id');
        });

        Schema::dropIfExists('categories');
    }
};
