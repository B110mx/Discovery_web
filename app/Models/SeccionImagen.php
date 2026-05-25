<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeccionImagen extends Model
{
    protected $table = 'seccion_imagenes';

    protected $fillable = [
        'vista',
        'clave',
        'titulo',
        'referencia',
        'imagen',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
