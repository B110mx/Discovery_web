<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen_url',
        'imagen_media_path',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => SiteCache::forget('inicio_eventos'));
        static::deleted(fn () => SiteCache::forget('inicio_eventos'));
    }
}
