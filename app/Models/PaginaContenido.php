<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaginaContenido extends Model
{
    protected $fillable = [
        'slug',
        'titulo',
        'subtitulo',
        'descripcion',
        'imagen_principal',
        'imagen_secundaria',
        'direccion',
        'telefono_principal',
        'telefono_secundario',
        'correo',
        'mapa_url',
    ];
}
