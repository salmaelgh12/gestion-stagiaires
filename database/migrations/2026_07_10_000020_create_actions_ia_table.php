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
        Schema::create('actions_ia', function (Blueprint $table) {
            $table->id('id_action');
            $table->foreignId('id_conversation')->constrained('conversations_ia', 'id_conversation');
            $table->foreignId('id_user')->constrained('utilisateurs', 'id_user');
            $table->enum('type_action', ['envoi_message']);
            $table->foreignId('id_message')->nullable()->constrained('messages', 'id_message');
            $table->timestamp('date_action')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions_ia');
    }
};
