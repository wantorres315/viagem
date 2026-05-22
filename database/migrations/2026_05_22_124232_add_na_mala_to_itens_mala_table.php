<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('itemmalas', function (Blueprint $table) {

            $table->boolean('na_mala')
                ->default(false)
                ->after('pessoa_id');

        });
    }

    public function down(): void
    {
        Schema::table('itemmalas', function (Blueprint $table) {

            $table->dropColumn('na_mala');

        });
    }
};