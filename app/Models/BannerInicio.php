<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

class BannerInicio extends Model
{
    protected $table = 'banners_inicio';

    protected $fillable = [
        'titulo',
        'texto_alternativo',
        'enlace',
        'imagen',
        'imagen_media_path',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => SiteCache::forget('inicio_banners'));
        static::deleted(fn () => SiteCache::forget('inicio_banners'));
    }
}
