<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

class SeccionImagen extends Model
{
    protected $table = 'seccion_imagenes';

    protected $fillable = [
        'vista',
        'clave',
        'titulo',
        'referencia',
        'imagen',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function booted(): void
    {
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
    }
}
