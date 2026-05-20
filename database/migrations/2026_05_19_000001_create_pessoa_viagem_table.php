<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pessoa_viagem', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas')->onDelete('cascade');
            $table->foreignId('viagem_id')->constrained('viagens')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['pessoa_id', 'viagem_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pessoa_viagem');
    }
};
