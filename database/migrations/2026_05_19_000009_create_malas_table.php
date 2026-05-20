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
        Schema::create('malas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->foreignId('viagem_id')->constrained('viagens')->onDelete('cascade');
            $table->string('track')->nullable();
            $table->decimal('peso', 9,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('malas');
    }
};
