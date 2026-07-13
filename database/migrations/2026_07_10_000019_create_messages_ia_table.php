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
        Schema::create('messages_ia', function (Blueprint $table) {
            $table->id('id_message_ia');
            $table->foreignId('id_conversation')->constrained('conversations_ia', 'id_conversation');
            $table->enum('role', ['user', 'assistant']);
            $table->text('contenu');
            $table->foreignId('modele_utilise')->nullable()->constrained('modeles_ia', 'id_modele');
            $table->timestamp('date_envoi')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages_ia');
    }
};
