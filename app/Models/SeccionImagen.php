<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

/**
 * Imagen administrable identificada por el contrato vista + clave.
 *
 * PageController usa esa pareja para reemplazar una posición visual sin que el
 * administrador tenga que conocer rutas de archivos o modificar Blade.
 */
class SeccionImagen extends Model
{
    protected $table = 'seccion_imagenes';

    protected $fillable = [
        'vista',
        'clave',
        'titulo',
        'referencia',
        'imagen',
        'respaldo_media_path',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Toda edición visual invalida únicamente las páginas que pueden haber
        // reutilizado el registro modificado.
        static::saved(fn (SeccionImagen $imagen) => $imagen->forgetSiteCache());
        static::deleted(fn (SeccionImagen $imagen) => $imagen->forgetSiteCache());
    }

    public function forgetSiteCache(): void
    {
        if (in_array($this->vista, ['preescolar', 'primaria', 'secundaria', 'bachillerato'], true)) {
            SiteCache::forget("galeria.{$this->vista}");
        }

        if ($this->vista === 'contacto') {
            SiteCache::forget('contacto_pagina_id');
        }

        if ($this->vista === 'academias-vespertinas') {
            SiteCache::forget('academias_media');
        }

        if ($this->vista === 'nosotros' && str($this->clave)->startsWith('historia_')) {
            SiteCache::forget('nosotros_historia');
        }

    }
}
