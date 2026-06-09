<?php

namespace Tests\Feature;

use App\Models\HitoHistoria;
use App\Models\SeccionImagen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HitoHistoriaImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_timeline_images_are_managed_only_by_hito_historia(): void
    {
        $hito = HitoHistoria::query()->where('anio', '2003')->firstOrFail();

        $this->assertSame('Linea del tiempo/2003-1.jpg', $hito->imagen_media_path);
        $this->assertSame('Linea del tiempo/2003-2.jpg', $hito->imagen_secundaria_media_path);
        $this->assertFalse(
            SeccionImagen::query()
                ->where('vista', 'nosotros')
                ->where('clave', 'like', 'historia_%')
                ->exists(),
        );

        $response = $this->get('/nosotros');

        $response->assertOk();
        $response->assertSee('/media/Linea%20del%20tiempo/2003-1.jpg', false);
        $response->assertSee('/media/Linea%20del%20tiempo/2003-2.jpg', false);
    }
}
