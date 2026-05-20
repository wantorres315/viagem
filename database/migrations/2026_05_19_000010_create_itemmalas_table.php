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
        Schema::create('itemmalas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mala_id')->constrained('malas')->onDelete('cascade');
            $table->foreignId('pessoa_id')->nullable()->constrained('pessoas')->onDelete('cascade');
            $table->string('item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemmalas');
    }
};
