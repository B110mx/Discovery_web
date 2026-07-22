<?php

namespace App\Services;

use App\Models\PaginaContenido;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;

class EditablePageContentService
{
    public function get(string $slug): ?PaginaContenido
    {
        $paginaId = Cache::remember(SiteCache::key("pagina_contenido.{$slug}"), SiteCache::ttl(), function () use ($slug) {
            return PaginaContenido::where('slug', $slug)->value('id');
        });

        $pagina = $paginaId ? PaginaContenido::find($paginaId) : null;

        return $this->localize($slug, $pagina);
    }

    private function localize(string $slug, ?PaginaContenido $pagina): ?PaginaContenido
    {
        if (! $pagina || app()->getLocale() === 'es') {
            return $pagina;
        }

        $translationMap = [
            'inicio' => [
                'subtitulo' => 'site.pages.home.about_subtitle',
                'titulo' => 'site.pages.home.about_title',
                'descripcion' => 'site.pages.home.about_text',
            ],
            'nosotros' => [
                'subtitulo' => 'site.pages.about.hero_subtitle',
                'titulo' => 'site.pages.about.hero_title',
                'descripcion' => 'site.pages.about.hero_text',
            ],
            'oferta-academica' => [
                'subtitulo' => 'site.pages.offer.default_subtitle',
                'titulo' => 'site.pages.offer.default_title',
                'descripcion' => 'site.pages.offer.default_text',
            ],
            'protagonistas' => [
                'subtitulo' => 'site.pages.community.hero_subtitle',
                'titulo' => 'site.pages.community.hero_title',
                'descripcion' => 'site.pages.community.hero_text',
            ],
            'contacto' => [
                'subtitulo' => 'site.pages.contact.hero_subtitle',
                'titulo' => 'site.pages.contact.hero_title',
                'descripcion' => 'site.pages.contact.hero_text',
            ],
        ];

        if (! isset($translationMap[$slug])) {
            return $pagina;
        }

        $localized = clone $pagina;

        foreach ($translationMap[$slug] as $attribute => $translationKey) {
            $localized->setAttribute($attribute, __($translationKey));
        }

        return $localized;
    }
}
