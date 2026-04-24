<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    // Esto permite guardar datos masivamente sin que Laravel nos bloquee
    protected $guarded = [];

    // Obtener al profesor creador del curso
    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    // Obtener a todos los alumnos inscritos
    public function alumnos()
    {
        return $this->belongsToMany(User::class, 'inscripciones', 'curso_id', 'alumno_id')
                    ->withPivot('estado')
                    ->withTimestamps();
    }

    // Obtener los módulos de este curso
    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'curso_id');
    }

    // Obtener las preguntas del examen final
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'curso_id');
    }
}