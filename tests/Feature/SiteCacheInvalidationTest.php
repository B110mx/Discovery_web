<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\BannerInicio;
use App\Models\GaleriaImagen;
use App\Models\HitoHistoria;
use App\Models\ListaUtil;
use App\Models\NivelContenido;
use App\Models\PaginaContenido;
use App\Models\SeccionImagen;
use App\Models\TestimonioVideo;
use App\Support\SiteCache;
use Database\Seeders\NivelContenidoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SiteCacheInvalidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_editable_page_content_invalidates_only_its_page_cache(): void
    {
        $page = PaginaContenido::query()->where('slug', 'inicio')->firstOrFail();
        $pageKey = SiteCache::key('pagina_contenido.inicio');
        $otherKey = SiteCache::key('pagina_contenido.contacto');

        Cache::put($pageKey, $page->getKey());
        Cache::put($otherKey, 'keep');

        $page->update(['titulo' => 'Inicio actualizado']);

        $this->assertFalse(Cache::has($pageKey));
        $this->assertSame('keep', Cache::get($otherKey));
    }

    public function test_history_changes_invalidate_the_timeline_cache(): void
    {
        $historyKey = SiteCache::key('nosotros_historia');
        Cache::put($historyKey, ['cached']);

        HitoHistoria::query()->firstOrFail()->update(['titulo' => 'Hito actualizado']);

        $this->assertFalse(Cache::has($historyKey));
    }

    public function test_home_events_invalidate_their_public_cache(): void
    {
        $eventsKey = SiteCache::key('inicio_eventos');
        Cache::put($eventsKey, ['cached']);

        Evento::query()->create([
            'titulo' => 'Evento de prueba',
            'descripcion' => 'Contrato de invalidación',
            'imagen_url' => 'eventos/prueba.jpg',
            'orden' => 10,
            'activo' => true,
        ]);

        $this->assertFalse(Cache::has($eventsKey));
    }

    public function test_home_banners_invalidate_their_public_cache(): void
    {
        $bannersKey = SiteCache::key('inicio_banners');
        Cache::put($bannersKey, ['cached']);

        BannerInicio::query()->create([
            'titulo' => 'Banner de prueba',
            'imagen_media_path' => 'Banner de inicio/Banner de bienvenida.png',
            'orden' => 10,
            'activo' => true,
        ]);

        $this->assertFalse(Cache::has($bannersKey));
    }

    public function test_school_supply_lists_invalidate_their_public_cache(): void
    {
        $listsKey = SiteCache::key('recursos_listas_utiles');
        Cache::put($listsKey, ['cached']);

        ListaUtil::query()->create([
            'ciclo_escolar' => '2026-2027',
            'nivel' => 'Middle',
            'grado' => '1°',
            'titulo' => 'Lista de prueba',
            'archivo_pdf' => 'listas-utiles/middle-primero.pdf',
            'orden' => 10,
            'activo' => true,
        ]);

        $this->assertFalse(Cache::has($listsKey));
    }

    public function test_testimonials_invalidate_home_and_community_caches(): void
    {
        $homeKey = SiteCache::key('inicio_testimonios');
        $communityKey = SiteCache::key('protagonistas_testimonios');
        Cache::put($homeKey, ['cached']);
        Cache::put($communityKey, ['cached']);

        TestimonioVideo::query()->create([
            'titulo' => 'Testimonio de prueba',
            'video' => 'testimonios/prueba.mp4',
            'orden' => 10,
            'activo' => true,
        ]);

        $this->assertFalse(Cache::has($homeKey));
        $this->assertFalse(Cache::has($communityKey));
    }

    public function test_academy_site_images_invalidate_their_public_media_cache(): void
    {
        $academyKey = SiteCache::key('academias_media');
        Cache::put($academyKey, ['cached']);

        SeccionImagen::query()->updateOrCreate([
            'vista' => 'academias-vespertinas',
            'clave' => 'academia_cache_test',
        ], [
            'vista' => 'academias-vespertinas',
            'clave' => 'academia_cache_test',
            'titulo' => 'Academia actualizada',
            'respaldo_media_path' => 'Academias vespertinas/soccer.jpg',
            'orden' => 10,
            'activo' => true,
        ]);

        $this->assertFalse(Cache::has($academyKey));
    }

    public function test_level_content_invalidates_its_shared_cache(): void
    {
        $this->seed(NivelContenidoSeeder::class);

        $levelsKey = SiteCache::key('niveles_contenido');
        Cache::put($levelsKey, ['cached']);

        NivelContenido::query()
            ->where('slug', 'preescolar')
            ->firstOrFail()
            ->update(['titulo' => 'Kinder actualizado']);

        $this->assertFalse(Cache::has($levelsKey));
    }

    public function test_gallery_images_invalidate_only_their_level_gallery_cache(): void
    {
        $kinderKey = SiteCache::key('galeria.preescolar');
        $elementaryKey = SiteCache::key('galeria.primaria');
        Cache::put($kinderKey, ['cached']);
        Cache::put($elementaryKey, ['keep']);

        GaleriaImagen::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Galería actualizada',
            'imagen_media_path' => 'Kinder fotos actuales/IMG_5775.JPG',
            'orden' => 10,
            'activo' => true,
        ]);

        $this->assertFalse(Cache::has($kinderKey));
        $this->assertSame(['keep'], Cache::get($elementaryKey));
    }

    public function test_moving_a_gallery_image_invalidates_the_old_and_new_level_caches(): void
    {
        $image = GaleriaImagen::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Imagen que cambia de nivel',
            'imagen_media_path' => 'Kinder fotos actuales/IMG_5775.JPG',
            'orden' => 10,
            'activo' => true,
        ]);

        $kinderKey = SiteCache::key('galeria.preescolar');
        $elementaryKey = SiteCache::key('galeria.primaria');
        Cache::put($kinderKey, ['cached']);
        Cache::put($elementaryKey, ['cached']);

        $image->update(['nivel' => 'primaria']);

        $this->assertFalse(Cache::has($kinderKey));
        $this->assertFalse(Cache::has($elementaryKey));
    }
}
