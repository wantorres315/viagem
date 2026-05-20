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
        Schema::table('amigo_passeios', function (Blueprint $table) {
            // Remove a foreign key antiga se existir
            $table->dropForeign(['amigo_id']);
            // Corrige para referenciar a tabela amigos
            $table->foreign('amigo_id')->references('id')->on('amigos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amigo_passeios', function (Blueprint $table) {
            $table->dropForeign(['amigo_id']);
            $table->foreign('amigo_id')->references('id')->on('pessoas')->onDelete('cascade');
        });
    }
};
