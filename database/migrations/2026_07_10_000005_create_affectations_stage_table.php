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
        Schema::create('affectations_stage', function (Blueprint $table) {
            $table->id('id_affectation');
            $table->foreignId('id_stagiaire')->constrained('stagiaires', 'id_stagiaire');
            $table->foreignId('id_stage')->constrained('stages', 'id_stage');
            $table->foreignId('id_encadrant')->constrained('utilisateurs', 'id_user');
            $table->foreignId('id_responsable_competence')->constrained('utilisateurs', 'id_user');
            $table->date('date_affectation');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations_stage');
    }
};
