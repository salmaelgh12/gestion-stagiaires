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
        Schema::create('taches', function (Blueprint $table) {
            $table->id('id_tache');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->foreignId('id_stagiaire')->constrained('stagiaires', 'id_stagiaire');
            $table->foreignId('id_encadrant')->constrained('utilisateurs', 'id_user');
            $table->date('date_creation');
            $table->date('date_echeance');
            $table->date('date_realisation')->nullable();
            $table->enum('priorite', ['Faible', 'Moyenne', 'Haute'])->default('Moyenne');
            $table->enum('statut', ['A faire', 'En cours', 'Terminée', 'Annulée'])->default('A faire');
            $table->unsignedTinyInteger('pourcentage_avancement')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taches');
    }
};
