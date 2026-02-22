<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    // tu tabla solo indica createdAt? no lo listaste
    // como no lo incluiste, desactivamos timestamps
    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'usuarioId',
        'cursoId',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuarioId');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'cursoId');
    }
}