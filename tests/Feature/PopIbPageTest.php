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
            7,
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
        $response->assertSee('Fotografía horizontal de Explorers trabajando con computadoras');
    }

    public function test_pop_ib_can_show_only_the_data_science_route(): void
    {
        config(['colegio.niveles.definiciones.pop-del-ib.informacion.rutas_visibles' => ['data_science']]);

        $response = $this->get('/oferta-academica/pop-del-ib');

        $response->assertOk();
        $response->assertSee('Ruta de Data Science');
        $response->assertDontSee('Ruta de Diseño e Impresión 3D');
        $response->assertSee('POP del IB - Ruta de Data Science');
        $response->assertDontSee('POP del IB - Ruta de Diseño e Impresión 3D');
    }

    public function test_pop_ib_can_show_only_the_3d_design_route(): void
    {
        config(['colegio.niveles.definiciones.pop-del-ib.informacion.rutas_visibles' => ['diseno_3d']]);

        $response = $this->get('/oferta-academica/pop-del-ib');

        $response->assertOk();
        $response->assertDontSee('Ruta de Data Science');
        $response->assertSee('Ruta de Diseño e Impresión 3D');
        $response->assertDontSee('POP del IB - Ruta de Data Science');
        $response->assertSee('POP del IB - Ruta de Diseño e Impresión 3D');
    }
}
