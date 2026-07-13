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
        Schema::create('validations_demande', function (Blueprint $table) {
            $table->id('id_validation');
            $table->foreignId('id_demande')->constrained('demandes', 'id_demande');
            $table->foreignId('id_validateur')->constrained('utilisateurs', 'id_user');
            $table->string('role_validateur');
            $table->enum('decision', ['Validée', 'Rejetée', 'En attente'])->default('En attente');
            $table->text('commentaire')->nullable();
            $table->timestamp('date_validation')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validations_demande');
    }
};
