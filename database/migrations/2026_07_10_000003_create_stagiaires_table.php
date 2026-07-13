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
        Schema::create('stagiaires', function (Blueprint $table) {
            $table->id('id_stagiaire');
            $table->foreignId('id_user')->constrained('utilisateurs', 'id_user');
            $table->string('ecole')->nullable();
            $table->string('filiere')->nullable();
            $table->string('niveau_etude')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['En attente', 'En cours', 'Terminé', 'Prolongé', 'Archivé'])->default('En attente');
            $table->integer('score_global')->default(0);
            $table->boolean('archive')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stagiaires');
    }
};
