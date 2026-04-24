<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    protected $fillable = [
        'curso_id',
        'user_id',
        'estado',
        'authorized_at',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}