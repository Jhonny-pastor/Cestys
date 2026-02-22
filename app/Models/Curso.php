<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    protected $table = 'curso';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'codigo',
        'imagenPortada',
        'nombre',
        'descripcion',
        'precio',
        'horas',
        'valoracion',
        'estado',
        'categoriaId',
    ];

    protected $casts = [
        'precio' => 'float',
        'horas' => 'int',
        'valoracion' => 'float',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoriaId');
    }

    public function modulos(): HasMany
    {
        return $this->hasMany(Modulo::class, 'cursoId');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'cursoId');
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class, 'cursoId');
    }

    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class, 'cursoId');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'cursoId');
    }
}