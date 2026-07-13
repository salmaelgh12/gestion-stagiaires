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
        Schema::create('pieces_jointes', function (Blueprint $table) {
            $table->id('id_piece');
            $table->foreignId('id_stagiaire')->constrained('stagiaires', 'id_stagiaire');
            $table->string('nom_fichier');
            $table->string('chemin_fichier');
            $table->string('type_document')->nullable();
            $table->timestamp('date_upload')->useCurrent();
            $table->foreignId('uploaded_by')->constrained('utilisateurs', 'id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pieces_jointes');
    }
};
