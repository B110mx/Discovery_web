<?php

namespace Tests\Feature;

use App\Models\PaginaContenido;
use App\Models\SeccionImagen;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LowCoverageModelTest extends TestCase
{
    public function test_page_map_uses_the_fallback_for_an_empty_value(): void
    {
        config()->set('colegio.contacto.mapa_embed_url', 'https://maps.example/fallback');

        $page = new PaginaContenido(['mapa_url' => '   ']);

        $this->assertSame('https://maps.example/fallback', $page->mapaEmbedUrl());
    }

    public function test_page_map_extracts_the_source_from_an_iframe(): void
    {
        $page = new PaginaContenido([
            'mapa_url' => '<iframe loading="lazy" src="https://maps.example/embed?id=123"></iframe>',
        ]);

        $this->assertSame('https://maps.example/embed?id=123', $page->mapaEmbedUrl());
    }

    public function test_page_map_accepts_a_direct_url(): void
    {
        $page = new PaginaContenido(['mapa_url' => 'https://maps.example/direct']);

        $this->assertSame('https://maps.example/direct', $page->mapaEmbedUrl());
    }

    public function test_section_images_invalidate_each_supported_public_cache(): void
    {
        $cases = [
            ['preescolar', 'portada', 'galeria.preescolar'],
            ['contacto', 'portada', 'contacto_pagina_id'],
            ['academias-vespertinas', 'futbol', 'academias_media'],
            ['nosotros', 'historia_1985', 'nosotros_historia'],
        ];

        foreach ($cases as [$view, $slot, $cacheName]) {
            $key = SiteCache::key($cacheName);
            Cache::put($key, 'cached');

            (new SeccionImagen([
                'vista' => $view,
                'clave' => $slot,
            ]))->forgetSiteCache();

            $this->assertFalse(Cache::has($key), "The {$cacheName} cache was not forgotten.");
        }
    }

    public function test_unrelated_section_image_does_not_invalidate_history(): void
    {
        $key = SiteCache::key('nosotros_historia');
        Cache::put($key, 'keep');

        (new SeccionImagen([
            'vista' => 'nosotros',
            'clave' => 'equipo_directivo',
        ]))->forgetSiteCache();

        $this->assertSame('keep', Cache::get($key));
    }
}
