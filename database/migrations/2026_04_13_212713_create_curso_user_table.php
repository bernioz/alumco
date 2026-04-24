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
    Schema::create('curso_user', function (Blueprint $table) {
        $table->id();
        // Conecta con el alumno (User)
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        // Conecta con el curso
        $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete(); 
        // Opcional: Podrías agregar columnas aquí como 'progreso' o 'estado' más adelante
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_user');
    }
};
