<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HitoHistoria extends Model
{
    protected $fillable = [
        'anio',
        'titulo',
        'texto',
        'imagen_url',
        'orden',
    ];
}
