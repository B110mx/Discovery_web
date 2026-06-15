<?php

namespace Tests\Feature;

use App\Filament\Resources\BannersInicio\BannerInicioResource;
use App\Models\BannerInicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomeBannerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_uses_the_banner_folder_until_admin_records_exist(): void
    {
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        $disk = Storage::disk(config('colegio.media.disk', 'videosyfotos'));
        $disk->put('Banner de inicio/Otro banner.png', 'imagen');
        $disk->put('Banner de inicio/Banner de bienvenida.png', 'imagen');

        $response = $this->get(route('inicio'));

        $response->assertOk();
        $response->assertSeeInOrder([
            '/media/Banner%20de%20inicio/Banner%20de%20bienvenida.png',
            '/media/Banner%20de%20inicio/Otro%20banner.png',
        ], false);
    }

    public function test_admin_banners_replace_the_folder_and_respect_order_alt_text_and_link(): void
    {
        Storage::fake('public');
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))
            ->put('Banner de inicio/respaldo.png', 'imagen');
        Storage::disk('public')->put('banners-inicio/primero.png', 'imagen');
        Storage::disk('public')->put('banners-inicio/segundo.png', 'imagen');

        BannerInicio::query()->create([
            'titulo' => 'Segundo',
            'texto_alternativo' => 'Segundo mensaje familiar',
            'imagen' => 'banners-inicio/segundo.png',
            'orden' => 20,
            'activo' => true,
        ]);
        BannerInicio::query()->create([
            'titulo' => 'Primero',
            'texto_alternativo' => 'Primer mensaje familiar',
            'enlace' => '/contacto',
            'imagen' => 'banners-inicio/primero.png',
            'orden' => 10,
            'activo' => true,
        ]);

        $response = $this->get(route('inicio'));

        $response->assertOk();
        $response->assertSeeInOrder([
            '/storage/banners-inicio/primero.png',
            '/storage/banners-inicio/segundo.png',
        ], false);
        $response->assertSee('alt="Primer mensaje familiar"', false);
        $response->assertSee('href="/contacto"', false);
        $response->assertDontSee('respaldo.png');
    }

    public function test_inactive_admin_banners_hide_the_carousel_without_using_the_folder(): void
    {
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        Storage::disk(config('colegio.media.disk', 'videosyfotos'))
            ->put('Banner de inicio/respaldo.png', 'imagen');

        BannerInicio::query()->create([
            'titulo' => 'Banner oculto',
            'imagen_media_path' => 'Banner de inicio/respaldo.png',
            'orden' => 10,
            'activo' => false,
        ]);

        $response = $this->get(route('inicio'));

        $response->assertOk();
        $response->assertViewHas('bannerInicioSlides', []);
        $response->assertDontSee('<section class="relative mt-8 overflow-hidden rounded-xl bg-white shadow-lg" data-home-hero-carousel>', false);
        $response->assertDontSee('respaldo.png');
    }

    public function test_admin_can_open_the_banner_module_and_batch_upload_action(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->get(BannerInicioResource::getUrl('index'));

        $response->assertOk();
        $response->assertSee('Subir varios banners');
        $response->assertSee('Agregar un banner');
        $response->assertSee('Banners de Inicio');
    }
}
