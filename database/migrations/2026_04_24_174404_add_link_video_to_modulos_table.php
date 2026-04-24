<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('modulos', function (Blueprint $table) {
            // Agregamos la columna link_video
            $table->string('link_video')->nullable()->after('duracion');
        });
    }

    public function down()
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->dropColumn('link_video');
        });
    }
};