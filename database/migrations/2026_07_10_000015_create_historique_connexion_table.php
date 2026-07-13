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
        Schema::create('historique_connexion', function (Blueprint $table) {
            $table->id('id_historique');
            $table->foreignId('id_user')->constrained('utilisateurs', 'id_user');
            $table->string('adresse_ip')->nullable();
            $table->string('navigateur')->nullable();
            $table->timestamp('date_connexion')->useCurrent();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_connexion');
    }
};
