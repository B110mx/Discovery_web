<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

/**
 * Textos editables y datos generales de una página pública.
 *
 * Las imágenes históricas de este modelo se conservan como respaldo; las
 * posiciones visuales nuevas deben administrarse mediante SeccionImagen.
 */
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
        SiteCache::forget("pagina_contenido.{$this->slug}");
    }

    public function mapaEmbedUrl(): string
    {
        $mapa = trim((string) $this->mapa_url);

        if ($mapa === '') {
            return config('colegio.contacto.mapa_embed_url');
        }

        // Filament acepta tanto la URL como el iframe completo copiado desde
        // Google Maps; la vista solo necesita el valor de src.
        if (preg_match('/src=["\']([^"\']+)["\']/i', $mapa, $matches)) {
            return $matches[1];
        }

        return $mapa;
    }

}
