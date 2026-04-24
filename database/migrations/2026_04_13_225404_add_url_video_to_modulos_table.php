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
    Schema::table('modulos', function (Blueprint $table) {
        // Agregamos la columna url_video que permite nulos (por si un módulo solo es de lectura)
        $table->string('url_video')->nullable()->after('descripcion_contenido');
    });
}

public function down(): void
{
    Schema::table('modulos', function (Blueprint $table) {
        $table->dropColumn('url_video');
    });
}
};
