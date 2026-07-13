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
        Schema::create('attestations', function (Blueprint $table) {
            $table->id('id_attestation');
            $table->foreignId('id_stagiaire')->constrained('stagiaires', 'id_stagiaire');
            $table->foreignId('id_demande')->constrained('demandes', 'id_demande');
            $table->string('numero_attestation')->unique();
            $table->string('fichier_pdf')->nullable();
            $table->date('date_generation')->nullable();
            $table->date('date_validation_rh')->nullable();
            $table->enum('statut', ['En attente', 'Générée', 'Archivée'])->default('En attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attestations');
    }
};
