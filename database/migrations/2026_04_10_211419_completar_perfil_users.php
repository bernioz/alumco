<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregamos sexo (usamos enum para estandarizar) y fecha de nacimiento
            $table->enum('sexo', ['Masculino', 'Femenino', 'Otro'])->nullable()->after('rol');
            $table->date('fecha_nacimiento')->nullable()->after('sexo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sexo', 'fecha_nacimiento']);
        });
    }
};