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
        Schema::create('checklist_viagems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viagem_id')->constrained('viagens')->onDelete('cascade');
            $table->string('tarefa');
            $table->boolean('concluido')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_viagems');
    }
};
