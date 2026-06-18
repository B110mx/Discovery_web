<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

/** Evento administrable que alimenta el carrusel de la página de Inicio. */
class Evento extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_evento',
        'nivel',
        'imagen_url',
        'imagen_media_path',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_evento' => 'date',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => SiteCache::forget('inicio_eventos'));
        static::deleted(fn () => SiteCache::forget('inicio_eventos'));
    }

    public static function levelOptions(): array
    {
        return [
            'general' => 'Toda la comunidad',
            'preescolar' => 'Kindergarten',
            'primaria' => 'Elementary',
            'secundaria' => 'Middle School',
            'bachillerato' => 'High School',
        ];
    }
}
