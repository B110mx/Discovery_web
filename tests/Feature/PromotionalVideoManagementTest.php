<?php

namespace Tests\Feature;

use App\Filament\Resources\VideosPromocionales\VideoPromocionalResource;
use App\Models\User;
use App\Models\VideoPromocional;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PromotionalVideoManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_level_displays_active_promotional_videos_in_a_lazy_sliding_widget(): void
    {
        Storage::fake('public');
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))->put('promocionales/kinder.mp4', 'video');
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))->put('promocionales/kinder.webp', 'portada');

        VideoPromocional::query()->delete();
        VideoPromocional::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Desarrollo de habilidades',
            'video_media_path' => 'promocionales/kinder.mp4',
            'portada_media_path' => 'promocionales/kinder.webp',
            'orden' => 10,
            'activo' => true,
        ]);

        $response = $this->get(route('nivel', 'preescolar'));

        $response->assertOk();
        $response->assertSee('Vive la experiencia Discovery®');
        $response->assertSee('Desarrollo de habilidades');
        $response->assertSee('data-promotional-widget-toggle', false);
        $response->assertSee('translate-x-full', false);
        $response->assertSee('aria-expanded="false"', false);
        $response->assertSee('/media/promocionales/kinder.mp4', false);
        $response->assertSee('/media/promocionales/kinder.webp', false);
        $response->assertSee('preload="none"', false);
        $response->assertDontSee('<video src=', false);
    }

    public function test_home_uses_development_skills_as_its_single_featured_video(): void
    {
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))->put('promocionales/desarrollo.mp4', 'video');
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))->put('promocionales/ingles.mp4', 'video');

        VideoPromocional::query()->delete();
        VideoPromocional::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Desarrollo de habilidades',
            'video_media_path' => 'promocionales/desarrollo.mp4',
            'orden' => 10,
            'activo' => true,
        ]);
        VideoPromocional::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Inglés en Preescolar',
            'video_media_path' => 'promocionales/ingles.mp4',
            'orden' => 20,
            'activo' => true,
        ]);

        $this->get(route('inicio'))
            ->assertOk()
            ->assertSee('data-promotional-widget-toggle', false)
            ->assertSee('Desarrollo de habilidades')
            ->assertDontSee('Inglés en Preescolar');
    }

    public function test_academic_offer_displays_all_unique_promotional_videos(): void
    {
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))->put('promocionales/compartido.mp4', 'video');
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))->put('promocionales/elementary.mp4', 'video');

        VideoPromocional::query()->delete();

        foreach (['secundaria', 'bachillerato'] as $nivel) {
            VideoPromocional::query()->create([
                'nivel' => $nivel,
                'titulo' => 'Deporte diario',
                'video_media_path' => 'promocionales/compartido.mp4',
                'orden' => 10,
                'activo' => true,
            ]);
        }

        VideoPromocional::query()->create([
            'nivel' => 'primaria',
            'titulo' => 'Inglés en Elementary',
            'video_media_path' => 'promocionales/elementary.mp4',
            'orden' => 20,
            'activo' => true,
        ]);

        $response = $this->get(route('oferta-academica'));

        $response->assertOk();
        $response->assertSee('Deporte diario');
        $response->assertSee('Inglés en Elementary');
        $this->assertSame(1, substr_count($response->getContent(), 'data-video-title="Deporte diario"'));
    }

    public function test_inactive_or_other_level_videos_are_not_displayed(): void
    {
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))->put('promocionales/oculto.mp4', 'video');

        VideoPromocional::query()->delete();
        VideoPromocional::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Video oculto',
            'video_media_path' => 'promocionales/oculto.mp4',
            'activo' => false,
        ]);

        $this->get(route('nivel', 'primaria'))
            ->assertOk()
            ->assertDontSee('Video oculto');
    }

    public function test_admin_can_open_promotional_video_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(VideoPromocionalResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Videos promocionales')
            ->assertSee('Agregar video');
    }
}
