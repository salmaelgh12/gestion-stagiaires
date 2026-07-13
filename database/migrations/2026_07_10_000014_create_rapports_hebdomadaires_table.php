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
        Schema::create('rapports_hebdomadaires', function (Blueprint $table) {
            $table->id('id_rapport');
            $table->foreignId('id_stagiaire')->constrained('stagiaires', 'id_stagiaire');
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->text('resume_activites')->nullable();
            $table->integer('score_performance')->nullable();
            $table->boolean('genere_par_ia')->default(true);
            $table->timestamp('date_generation')->useCurrent();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports_hebdomadaires');
    }
};
