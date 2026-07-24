<?php

namespace Tests\Feature;

use App\Filament\Widgets\DashboardStatsOverview;
use App\Models\Contacto;
use App\Models\Evento;
use App\Support\SiteCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SupportAndDashboardCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_cache_ttl_keys_and_bulk_invalidation_are_locale_aware(): void
    {
        Carbon::setTestNow('2026-07-24 12:00:00');
        config([
            'colegio.cache.hours' => 6,
            'colegio.cache.keys.demo' => 'site.demo',
            'idiomas.supported' => ['es' => 'Español', 'en' => 'English'],
        ]);

        $this->assertSame('2026-07-24 18:00:00', SiteCache::ttl()->toDateTimeString());
        $this->assertSame('site.demo.es', SiteCache::key('demo'));

        foreach (['site.demo', 'site.demo.es', 'site.demo.en'] as $key) {
            Cache::put($key, 'cached');
        }

        SiteCache::forgetMany(['demo']);

        $this->assertFalse(Cache::has('site.demo'));
        $this->assertFalse(Cache::has('site.demo.es'));
        $this->assertFalse(Cache::has('site.demo.en'));

        Carbon::setTestNow();
    }

    public function test_site_cache_pattern_only_forgets_matching_declared_keys(): void
    {
        config([
            'colegio.cache.keys' => [
                'galeria.preescolar' => 'gallery.kinder',
                'galeria.primaria' => 'gallery.elementary',
                'inicio_eventos' => 'home.events',
            ],
            'idiomas.supported' => ['es' => 'Español'],
        ]);

        Cache::put('gallery.kinder.es', 'remove');
        Cache::put('gallery.elementary.es', 'remove');
        Cache::put('home.events.es', 'keep');

        SiteCache::forgetPattern('galeria.*');

        $this->assertFalse(Cache::has('gallery.kinder.es'));
        $this->assertFalse(Cache::has('gallery.elementary.es'));
        $this->assertSame('keep', Cache::get('home.events.es'));
    }

    public function test_dashboard_stats_count_contacts_and_only_active_events(): void
    {
        Contacto::query()->create([
            'nombre' => 'Tutor uno',
            'email' => 'uno@example.com',
            'mensaje' => 'Mensaje uno',
        ]);
        Contacto::query()->create([
            'nombre' => 'Tutor dos',
            'email' => 'dos@example.com',
            'mensaje' => 'Mensaje dos',
        ]);
        Evento::query()->create([
            'titulo' => 'Evento activo',
            'imagen_url' => '',
            'activo' => true,
        ]);
        Evento::query()->create([
            'titulo' => 'Evento oculto',
            'imagen_url' => '',
            'activo' => false,
        ]);

        $widget = new class extends DashboardStatsOverview
        {
            public function statsForTest(): array
            {
                return $this->getStats();
            }
        };

        $stats = $widget->statsForTest();

        $this->assertSame('Mensajes Recibidos', $stats[0]->getLabel());
        $this->assertSame(2, $stats[0]->getValue());
        $this->assertSame('Total de contactos web', $stats[0]->getDescription());
        $this->assertSame('Eventos Activos', $stats[1]->getLabel());
        $this->assertSame(1, $stats[1]->getValue());
        $this->assertSame('Eventos publicados', $stats[1]->getDescription());
    }
}
