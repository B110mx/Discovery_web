<?php

namespace Tests\Feature;

use App\Models\SeccionImagen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PopIbPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_pop_ib_page_and_image_slots_are_available(): void
    {
        $this->assertSame(
            6,
            SeccionImagen::query()->where('vista', 'pop-del-ib')->count(),
        );

        $this->assertTrue(
            SeccionImagen::query()
                ->where('vista', 'oferta-academica')
                ->where('clave', 'pop_ib')
                ->exists(),
        );

        $response = $this->get('/oferta-academica/pop-del-ib');

        $response->assertOk();
        $response->assertSee('Preparando Explorers para construir su futuro');
        $response->assertSee('Ruta de Data Science');
        $response->assertSee('Ruta de Diseño e Impresión 3D');
        $response->assertSee('Áreas de interés profesional en 11° y 12°');
        $response->assertSee('Fotografía horizontal de Explorers trabajando con computadoras');
    }
}
