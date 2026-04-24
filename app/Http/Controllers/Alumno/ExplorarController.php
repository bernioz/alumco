<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExplorarController extends Controller
{
    public function index()
    {
        // Traemos los cursos disponibles
        $cursos = Curso::all(); 
        
        // Obtenemos solo los IDs de los cursos donde ya está el alumno
        $inscritos = auth()->user()->cursosInscritos->pluck('id')->toArray();

        return Inertia::render('Alumno/Explorar', [
            'cursos' => $cursos,
            'inscritos' => $inscritos
        ]);
    }

    public function inscribir(Curso $curso)
    {
        $user = auth()->user();

        // Usamos tu relación existente. 
        // Agregamos el campo 'estado' inicial en la tabla pivote 'inscripciones'
        $user->cursosInscritos()->syncWithoutDetaching([
            $curso->id => ['estado' => 'en_progreso']
        ]);

        return redirect()->route('alumno.dashboard')->with('message', '¡Inscripción exitosa!');
    }
}