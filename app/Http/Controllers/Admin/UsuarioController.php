<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Curso;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class UsuarioController extends Controller
{
    public function alumnos(Request $request)
    {
        // Buscador
        $query = User::where('rol', 'alumno');

        if ($request->buscar) {
            $query->where('name', 'like', "%{$request->buscar}%");
        }

        $alumnos = $query->latest()->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'sexo' => $user->sexo ?? 'No definido',
                // Calculamos la edad con Carbon
                'edad' => $user->fecha_nacimiento ? Carbon::parse($user->fecha_nacimiento)->age : 'N/A',
                'email' => $user->email
            ];
        });

        return Inertia::render('Admin/Usuarios/Alumnos', [
            'usuarios' => $alumnos,
            'cursos' => Curso::where('estado', 'publicado')->get(['id', 'titulo']),
            'filtros' => $request->only(['buscar'])
        ]);
    }

    public function inscripcionMasiva(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'curso_id' => 'required|exists:cursos,id',
            'accion' => 'required|in:inscribir,desinscribir'
        ]);

        if ($request->accion === 'inscribir') {
            foreach ($request->user_ids as $userId) {
                // firstOrCreate evita duplicados
                Inscripcion::firstOrCreate([
                    'user_id' => $userId,
                    'curso_id' => $request->curso_id
                ]);
            }
        } else {
            Inscripcion::whereIn('user_id', $request->user_ids)
                       ->where('curso_id', $request->curso_id)
                       ->delete();
        }

        return back()->with('success', 'Operación masiva completada con éxito.');
    }

    public function profesores(Request $request)
    {
        $query = User::whereIn('rol', ['profesor', 'admin']);

        if ($request->buscar) {
            $query->where('name', 'like', "%{$request->buscar}%");
        }

        $profesores = $query->latest()->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'sexo' => $user->sexo ?? 'No definido',
                'edad' => $user->fecha_nacimiento ? \Carbon\Carbon::parse($user->fecha_nacimiento)->age : 'N/A',
            ];
        });

        return Inertia::render('Admin/Usuarios/Profesores', [
            'usuarios' => $profesores,
            'filtros' => $request->only(['buscar'])
        ]);
    }

    // Función para ver el perfil individual de un alumno
    public function verAlumno($id)
    {
        $alumno = User::where('rol', 'alumno')
                      ->with('cursosInscritos') // Traemos los cursos en los que está inscrito
                      ->findOrFail($id);
        
        $alumno->edad = $alumno->fecha_nacimiento ? \Carbon\Carbon::parse($alumno->fecha_nacimiento)->age : 'N/A';

        return Inertia::render('Admin/Usuarios/AlumnoPerfil', [
            'alumno' => $alumno
        ]);
    }

    // Función para ver el perfil individual de un profesor
    public function verProfesor($id)
    {
        // Buscamos al usuario y nos traemos sus cursos adjuntos
        $profesor = User::with('cursos')->findOrFail($id);
        
        // Calculamos la edad para mandarla a la vista
        $profesor->edad = $profesor->fecha_nacimiento ? \Carbon\Carbon::parse($profesor->fecha_nacimiento)->age : 'N/A';

        return Inertia::render('Admin/Usuarios/ProfesorPerfil', [
            'profesor' => $profesor
        ]);
    }
}