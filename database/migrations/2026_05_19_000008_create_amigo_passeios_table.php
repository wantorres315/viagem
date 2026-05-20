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
        Schema::create('amigo_passeios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amigo_id')->constrained('pessoas')->onDelete('cascade');
            $table->foreignId('passeio_id')->constrained('passeios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amigo_passeios');
    }
};
