<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // Relacionamos el curso con la tabla de usuarios (profesores)
            $table->foreignId('profesor_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropForeign(['profesor_id']);
            $table->dropColumn('profesor_id');
        });
    }
};