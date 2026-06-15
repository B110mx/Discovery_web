<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

class GaleriaImagen extends Model
{
    protected $table = 'galeria_imagenes';

    protected $fillable = [
        'nivel',
        'titulo',
        'texto_alternativo',
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
        static::saved(function (GaleriaImagen $image): void {
            SiteCache::forget("galeria.{$image->nivel}");

            $previousLevel = $image->getOriginal('nivel');

            if ($previousLevel && $previousLevel !== $image->nivel) {
                SiteCache::forget("galeria.{$previousLevel}");
            }
        });
        static::deleted(fn (GaleriaImagen $image) => SiteCache::forget("galeria.{$image->nivel}"));
    }
}
