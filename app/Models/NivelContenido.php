<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

class NivelContenido extends Model
{
    protected $fillable = [
        'slug',
        'titulo',
        'descripcion',
        'contenido_titulo',
        'contenido_intro',
        'oferta_subtitulo',
        'oferta_descripcion',
        'oferta_edad',
        'oferta_puntos',
        'pop_rutas_visibles',
    ];

    protected $casts = [
        'oferta_puntos' => 'array',
        'pop_rutas_visibles' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => SiteCache::forget('niveles_contenido'));
        static::deleted(fn () => SiteCache::forget('niveles_contenido'));
    }
}
