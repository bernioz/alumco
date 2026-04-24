<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $guarded = [];

    // Esta función conecta la pregunta con sus alternativas
    public function opciones()
    {
        return $this->hasMany(PreguntaOpcion::class, 'pregunta_id');
    }
}