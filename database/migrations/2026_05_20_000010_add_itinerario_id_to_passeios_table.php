<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('passeios', function (Blueprint $table) {
            $table->unsignedBigInteger('itinerario_id')->nullable()->after('id');
            $table->foreign('itinerario_id')->references('id')->on('itinerarios')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('passeios', function (Blueprint $table) {
            $table->dropForeign(['itinerario_id']);
            $table->dropColumn('itinerario_id');
        });
    }
};
