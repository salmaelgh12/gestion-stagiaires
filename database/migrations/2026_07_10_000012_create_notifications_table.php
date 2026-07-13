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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id_notification');
            $table->foreignId('id_user')->constrained('utilisateurs', 'id_user');
            $table->enum('type_notification', ['Absence répétée', 'Retard tâche', 'Nouvelle demande', 'Attestation validée']);
            $table->string('titre');
            $table->text('message')->nullable();
            $table->boolean('lue')->default(false);
            $table->timestamp('date_notification')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
