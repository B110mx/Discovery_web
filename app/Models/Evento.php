<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen_url',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
