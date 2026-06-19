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

    public function test_level_displays_active_promotional_videos_as_lazy_modal_cards(): void
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
        $response->assertSee('/media/promocionales/kinder.mp4', false);
        $response->assertSee('/media/promocionales/kinder.webp', false);
        $response->assertSee('preload="none"', false);
        $response->assertDontSee('<video src=', false);
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
