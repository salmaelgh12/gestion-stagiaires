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
        Schema::create('conversations_ia', function (Blueprint $table) {
            $table->id('id_conversation');
            $table->foreignId('id_user')->constrained('utilisateurs', 'id_user');
            $table->string('titre')->nullable();
            $table->timestamp('date_debut')->useCurrent();
            $table->timestamp('date_derniere_activite')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations_ia');
    }
};
