<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

class TestimonioVideo extends Model
{
    protected $fillable = [
        'titulo',
        'video',
        'video_media_path',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => SiteCache::forgetMany(['inicio_testimonios', 'protagonistas_testimonios']));
        static::deleted(fn () => SiteCache::forgetMany(['inicio_testimonios', 'protagonistas_testimonios']));
    }
}
