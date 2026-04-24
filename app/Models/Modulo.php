<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    // Esto desactiva el bloqueo de seguridad para poder guardar rápido
    protected $guarded = [];

    public function archivos()
    {
        return $this->hasMany(ModuloArchivo::class);
    }
}