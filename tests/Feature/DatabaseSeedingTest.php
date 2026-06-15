<?php

namespace Tests\Feature;

use App\Models\BannerInicio;
use App\Models\GaleriaImagen;
use App\Models\NivelContenido;
use App\Models\User;
use Database\Seeders\BannerInicioSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\GaleriaImagenSeeder;
use Database\Seeders\NivelContenidoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseSeedingTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_create_structure_without_optional_content(): void
    {
        $this->assertSame(0, NivelContenido::query()->count());
        $this->assertSame(0, User::query()->count());
    }

    public function test_required_content_seeder_is_idempotent(): void
    {
        $this->seed(NivelContenidoSeeder::class);
        NivelContenido::query()
            ->where('slug', 'preescolar')
            ->update(['titulo' => 'Kinder editado']);
        $this->seed(NivelContenidoSeeder::class);

        $this->assertSame(7, NivelContenido::query()->count());
        $this->assertSame(
            7,
            NivelContenido::query()->distinct()->count('slug'),
        );
        $this->assertSame(
            'Kinder editado',
            NivelContenido::query()->where('slug', 'preescolar')->value('titulo'),
        );
    }

    public function test_default_database_seeder_does_not_create_demo_users(): void
    {
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));

        $this->seed(DatabaseSeeder::class);

        $this->assertSame(7, NivelContenido::query()->count());
        $this->assertSame(0, User::query()->count());
    }

    public function test_banner_and_gallery_seeders_import_current_media_without_duplicates(): void
    {
        Storage::fake(config('colegio.media.disk', 'videosyfotos'));
        $disk = Storage::disk(config('colegio.media.disk', 'videosyfotos'));
        $disk->put('Banner de inicio/Banner de bienvenida.png', 'imagen');
        $disk->put('Banner de inicio/Audacia.png', 'imagen');
        $disk->put('Kinder fotos actuales/IMG_0001.JPG', 'imagen');
        $disk->put('Kinder fotos actuales/IMG_0002.JPG', 'imagen');

        $this->seed(BannerInicioSeeder::class);
        $this->seed(GaleriaImagenSeeder::class);

        BannerInicio::query()
            ->where('imagen_media_path', 'Banner de inicio/Audacia.png')
            ->update(['titulo' => 'Banner editado']);
        GaleriaImagen::query()
            ->where('imagen_media_path', 'Kinder fotos actuales/IMG_0001.JPG')
            ->update(['titulo' => 'Galería editada']);

        $this->seed(BannerInicioSeeder::class);
        $this->seed(GaleriaImagenSeeder::class);

        $this->assertSame(2, BannerInicio::query()->count());
        $this->assertSame(2, GaleriaImagen::query()->count());
        $this->assertSame(
            'Banner editado',
            BannerInicio::query()
                ->where('imagen_media_path', 'Banner de inicio/Audacia.png')
                ->value('titulo'),
        );
        $this->assertSame(
            'Galería editada',
            GaleriaImagen::query()
                ->where('imagen_media_path', 'Kinder fotos actuales/IMG_0001.JPG')
                ->value('titulo'),
        );
    }
}
