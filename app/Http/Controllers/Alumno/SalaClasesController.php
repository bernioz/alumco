<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalaClasesController extends Controller
{
    public function show(Curso $curso)
    {
        // Traemos el curso con sus módulos (y archivos si los tienes conectados)
        $curso->load('modulos'); 

        // Traemos un arreglo simple con los IDs de los módulos que el alumno ya terminó
        $completados = auth()->user()->modulosCompletados->pluck('id')->toArray();

        return Inertia::render('Alumno/SalaClases', [
            'curso' => $curso,
            'completados' => $completados
        ]);
    }

    public function completarModulo(Modulo $modulo)
    {
        // Guardamos el módulo en la tabla pivote de completados
        auth()->user()->modulosCompletados()->syncWithoutDetaching([$modulo->id]);
        
        return back(); // Recarga la página suavemente
    }
}