<?php

namespace Tests\Feature;

use App\Filament\Resources\Eventos\EventoResource;
use App\Filament\Resources\Eventos\Pages\CreateEvento;
use App\Filament\Resources\Eventos\Pages\EditEvento;
use App\Filament\Resources\SeccionImagenes\Pages\ListSeccionImagenes;
use App\Filament\Resources\Usuarios\Schemas\UsuarioForm;
use App\Models\Evento;
use App\Models\HitoHistoria;
use App\Models\PaginaContenido;
use App\Models\SeccionImagen;
use App\Models\User;
use App\Models\VideoPromocional;
use App\Services\EditablePageContentService;
use App\Services\HistoryTimelineService;
use App\Services\LevelContentService;
use App\Services\MediaResolver;
use App\Services\PromotionalVideoService;
use App\Services\SchoolCalendarService;
use App\Support\SiteCache;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Tests\TestCase;

class FinalCoverageGapsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_and_edit_pages_redirect_to_the_resource_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        Storage::fake('public');

        Livewire::test(CreateEvento::class)
            ->fillForm([
                'titulo' => 'Evento creado',
                'descripcion' => 'Prueba del flujo de creación.',
                'fecha_evento' => '2026-08-20',
                'imagen_url' => [UploadedFile::fake()->image('creado.jpg')],
                'nivel' => 'general',
                'orden' => 1,
                'activo' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect(EventoResource::getUrl('index'));

        $event = Evento::query()->where('titulo', 'Evento creado')->firstOrFail();

        Livewire::test(EditEvento::class, ['record' => $event->getRouteKey()])
            ->fillForm(['titulo' => 'Evento actualizado'])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect(EventoResource::getUrl('index'));

        $this->assertSame('Evento actualizado', $event->fresh()->titulo);
    }

    public function test_moving_a_promotional_video_invalidates_old_new_and_shared_caches(): void
    {
        $video = VideoPromocional::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Video que cambia de nivel',
            'video_media_path' => 'promocionales/video.mp4',
            'orden' => 1,
            'activo' => true,
        ]);

        $oldKey = SiteCache::key('videos_promocionales.preescolar');
        $newKey = SiteCache::key('videos_promocionales.primaria');
        $allKey = SiteCache::key('videos_promocionales.all');
        Cache::put($oldKey, 'old');
        Cache::put($newKey, 'new');
        Cache::put($allKey, 'all');

        $video->update(['nivel' => 'primaria']);

        $this->assertFalse(Cache::has($oldKey));
        $this->assertFalse(Cache::has($newKey));
        $this->assertFalse(Cache::has($allKey));
    }

    public function test_login_response_redirects_to_the_filament_panel(): void
    {
        $response = app(LoginResponse::class)->toResponse(
            Request::create('/admin/login', 'POST'),
        );

        $this->assertSame(Filament::getUrl(), $response->getTargetUrl());
    }

    public function test_layout_uses_default_seo_for_a_route_without_translations(): void
    {
        config()->set('seo.default', [
            'title' => 'SEO de respaldo',
            'description' => 'Descripción de respaldo.',
        ]);

        $request = Request::create('/_coverage/seo-fallback');
        $route = new Route(['GET'], '/_coverage/seo-fallback', fn () => null);
        $route->name('coverage.seo-fallback');
        $request->setRouteResolver(fn () => $route);
        $this->app->instance('request', $request);

        $html = View::make('layouts.app')->render();

        $this->assertStringContainsString('<title>SEO de respaldo</title>', $html);
        $this->assertStringContainsString(
            '<meta name="description" content="Descripción de respaldo.">',
            $html,
        );
    }

    public function test_invalid_session_locale_is_replaced_with_the_default(): void
    {
        $this->withSession(['locale' => 'xx'])
            ->get(route('inicio'))
            ->assertOk()
            ->assertSee('<html lang="es">', false)
            ->assertSessionHas('locale', 'xx');

        $this->assertSame('es', app()->getLocale());
    }

    public function test_regular_admin_cannot_change_password_through_the_user_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $method = new \ReflectionMethod(UsuarioForm::class, 'canChangePassword');

        $this->assertFalse($method->invoke(null, $target));
    }

    public function test_unknown_editable_page_slug_keeps_its_original_english_content(): void
    {
        app()->setLocale('en');
        PaginaContenido::query()->create([
            'slug' => 'pagina-personalizada',
            'titulo' => 'Custom title',
            'descripcion' => 'Custom description',
        ]);

        $page = app(EditablePageContentService::class)->get('pagina-personalizada');

        $this->assertSame('Custom title', $page?->titulo);
        $this->assertSame('Custom description', $page?->descripcion);
    }

    public function test_history_service_returns_defaults_without_database_records(): void
    {
        HitoHistoria::query()->delete();

        $timeline = app(HistoryTimelineService::class)->get();

        $this->assertNotEmpty($timeline);
        $this->assertArrayHasKey('anio', $timeline[0]);
    }

    public function test_level_content_handles_missing_translation_and_all_pop_routes(): void
    {
        app()->setLocale('en');
        $service = app(LevelContentService::class);
        $localized = new \ReflectionMethod($service, 'localizedDefinition');
        $filterRoutes = new \ReflectionMethod($service, 'filterPopRoutes');
        $definition = ['layout' => 'standard', 'informacion' => ['original' => true]];

        $this->assertSame(
            $definition,
            $localized->invoke($service, 'slug-sin-traduccion', $definition),
        );

        $information = $filterRoutes->invoke($service, [
            'rutas_visibles' => 'todas',
            'rutas' => [
                ['clave' => 'data_science'],
                ['clave' => 'diseno_3d'],
                ['clave' => 'otra'],
            ],
        ]);

        $this->assertSame(
            ['data_science', 'diseno_3d'],
            collect($information['rutas'])->pluck('clave')->all(),
        );
    }

    public function test_media_resolver_reuses_public_url_and_rejects_an_empty_file_path(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('paginas/imagen.jpg', 'image');
        $resolver = app(MediaResolver::class);

        $this->assertSame(
            '/storage/paginas/imagen.jpg',
            $resolver->publicUploadUrl('paginas/imagen.jpg'),
        );
        $this->assertSame(
            '/storage/paginas/imagen.jpg',
            $resolver->publicUploadUrl('paginas/imagen.jpg'),
        );
        $this->assertNull($resolver->filePath('/'));
    }

    public function test_promotional_video_without_a_valid_file_is_omitted(): void
    {
        Storage::fake('public');
        Storage::fake('videosyfotos');
        config()->set('colegio.media.disk', 'videosyfotos');

        VideoPromocional::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Video inexistente',
            'video_media_path' => 'promocionales/inexistente.mp4',
            'orden' => 1,
            'activo' => true,
        ]);

        $this->assertSame([], app(PromotionalVideoService::class)->get('preescolar'));
    }

    public function test_invalid_numeric_calendar_month_uses_the_current_month(): void
    {
        $calendar = app(SchoolCalendarService::class)->month('2026-99');

        $this->assertSame(today()->format('Y-m'), $calendar['month']);
    }

    public function test_site_image_table_formats_the_high_school_view(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        $image = SeccionImagen::query()->create([
            'vista' => 'bachillerato',
            'clave' => 'portada',
            'titulo' => 'High School',
            'orden' => 1,
            'activo' => true,
        ]);

        Livewire::test(ListSeccionImagenes::class)
            ->assertTableColumnFormattedStateSet('vista', 'Nivel - High School', $image);
    }
}
