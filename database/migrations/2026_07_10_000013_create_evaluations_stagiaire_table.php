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
        Schema::create('evaluations_stagiaire', function (Blueprint $table) {
            $table->id('id_evaluation');
            $table->foreignId('id_stagiaire')->constrained('stagiaires', 'id_stagiaire');
            $table->foreignId('id_encadrant')->constrained('utilisateurs', 'id_user');
            $table->unsignedTinyInteger('note_technique')->nullable();
            $table->unsignedTinyInteger('note_comportement')->nullable();
            $table->unsignedTinyInteger('note_assiduite')->nullable();
            $table->text('commentaire')->nullable();
            $table->date('date_evaluation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations_stagiaire');
    }
};
