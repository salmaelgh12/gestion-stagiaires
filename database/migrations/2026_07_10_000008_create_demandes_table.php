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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id('id_demande');
            $table->foreignId('id_stagiaire')->constrained('stagiaires', 'id_stagiaire');
            $table->enum('type_demande', ['Attestation', 'Prolongation', 'Absence', 'Autre']);
            $table->string('objet');
            $table->text('description')->nullable();
            $table->enum('statut', ['En attente', 'Validée', 'Rejetée', 'Traitée'])->default('En attente');
            $table->date('date_demande');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
