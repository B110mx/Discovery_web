<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

class HitoHistoria extends Model
{
    protected $fillable = [
        'anio',
        'titulo',
        'texto',
        'imagen_url',
        'imagen_media_path',
        'imagen_secundaria_url',
        'imagen_secundaria_media_path',
        'orden',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => SiteCache::forget('nosotros_historia'));
        static::deleted(fn () => SiteCache::forget('nosotros_historia'));
    }
}
