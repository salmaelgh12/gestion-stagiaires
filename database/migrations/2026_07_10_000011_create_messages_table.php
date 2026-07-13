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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('id_message');
            $table->foreignId('expediteur_id')->constrained('utilisateurs', 'id_user');
            $table->foreignId('destinataire_id')->constrained('utilisateurs', 'id_user');
            $table->string('objet')->nullable();
            $table->text('contenu');
            $table->boolean('lu')->default(false);
            $table->timestamp('date_envoi')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
