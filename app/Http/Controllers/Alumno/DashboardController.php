<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtenemos al alumno logueado con sus cursos inscritos
        $alumno = Auth::user();
        
        // Cargamos la relación que ya tienes definida
        $misCursos = $alumno->cursosInscritos()
            ->select('cursos.id', 'titulo', 'descripcion', 'imagen_portada')
            ->withPivot('estado') // Traemos el estado (en curso, completado, etc)
            ->get();

        return Inertia::render('Alumno/Dashboard', [
            'misCursos' => $misCursos
        ]);
    }
}