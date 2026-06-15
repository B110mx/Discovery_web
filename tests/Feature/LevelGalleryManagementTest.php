<?php

namespace Tests\Feature;

use App\Filament\Resources\GaleriaImagenes\GaleriaImagenResource;
use App\Models\GaleriaImagen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LevelGalleryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_level_gallery_uses_the_configured_folder_until_admin_records_exist(): void
    {
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))
            ->put('Kinder fotos actuales/respaldo.jpg', 'imagen');

        $response = $this->get(route('nivel', 'preescolar'));

        $response->assertOk();
        $response->assertSee('/media/Kinder%20fotos%20actuales/respaldo.jpg', false);
    }

    public function test_admin_gallery_replaces_the_folder_and_respects_order_and_alt_text(): void
    {
        Storage::fake('public');
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))
            ->put('Kinder fotos actuales/respaldo.jpg', 'imagen');
        Storage::disk('public')->put('galerias/primera.jpg', 'imagen');
        Storage::disk('public')->put('galerias/segunda.jpg', 'imagen');

        GaleriaImagen::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Segunda imagen',
            'texto_alternativo' => 'Explorers jugando',
            'imagen' => 'galerias/segunda.jpg',
            'orden' => 20,
            'activo' => true,
        ]);
        GaleriaImagen::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Primera imagen',
            'texto_alternativo' => 'Explorers aprendiendo',
            'imagen' => 'galerias/primera.jpg',
            'orden' => 10,
            'activo' => true,
        ]);

        $response = $this->get(route('nivel', 'preescolar'));

        $response->assertOk();
        $response->assertSeeInOrder([
            '/storage/galerias/primera.jpg',
            '/storage/galerias/segunda.jpg',
        ], false);
        $response->assertSee('Explorers aprendiendo');
        $response->assertDontSee('respaldo.jpg');
    }

    public function test_inactive_admin_gallery_does_not_fall_back_to_the_folder(): void
    {
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))
            ->put('Kinder fotos actuales/respaldo.jpg', 'imagen');

        GaleriaImagen::query()->create([
            'nivel' => 'preescolar',
            'titulo' => 'Imagen oculta',
            'imagen_media_path' => 'Kinder fotos actuales/respaldo.jpg',
            'orden' => 10,
            'activo' => false,
        ]);

        $response = $this->get(route('nivel', 'preescolar'));

        $response->assertOk();
        $response->assertSee('Galería en preparación');
        $response->assertDontSee('/media/Kinder%20fotos%20actuales/respaldo.jpg', false);
    }

    public function test_admin_can_open_the_gallery_module_and_batch_upload_action(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->get(GaleriaImagenResource::getUrl('index'));

        $response->assertOk();
        $response->assertSee('Subir varias imágenes');
        $response->assertSee('Agregar una imagen');
        $response->assertSee('Galería');
    }
}
