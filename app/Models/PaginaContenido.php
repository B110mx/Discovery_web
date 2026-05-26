<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

class PaginaContenido extends Model
{
    protected $fillable = [
        'slug',
        'titulo',
        'subtitulo',
        'descripcion',
        'imagen_principal',
        'imagen_secundaria',
        'direccion',
        'telefono_principal',
        'telefono_secundario',
        'correo',
        'mapa_url',
    ];

    protected static function booted(): void
    {
        static::saved(fn (PaginaContenido $pagina) => $pagina->forgetSiteCache());
        static::deleted(fn (PaginaContenido $pagina) => $pagina->forgetSiteCache());
    }

    public function forgetSiteCache(): void
    {
        if ($this->slug === 'contacto') {
            SiteCache::forget('contacto_pagina_id');
        }
    }

    public function mapaEmbedUrl(): string
    {
        $mapa = trim((string) $this->mapa_url);

        if ($mapa === '') {
            return config('colegio.contacto.mapa_embed_url');
        }

        if (preg_match('/src=["\']([^"\']+)["\']/i', $mapa, $matches)) {
            return $matches[1];
        }

        return $mapa;
    }

}
