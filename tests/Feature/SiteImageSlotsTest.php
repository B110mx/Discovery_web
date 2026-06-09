<?php

namespace Tests\Feature;

use App\Filament\Resources\SeccionImagenes\SeccionImagenResource;
use App\Models\SeccionImagen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteImageSlotsTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_fixed_view_images_have_an_admin_slot(): void
    {
        $expected = [
            'inicio' => ['sobre_nosotros', 'logo_preescolar', 'logo_primaria', 'logo_secundaria', 'logo_bachillerato'],
            'nosotros' => ['hero', 'modelo'],
            'oferta-academica' => ['preescolar', 'primaria', 'secundaria', 'bachillerato', 'ib', 'pop_ib', 'certificacion_ingles'],
            'preescolar' => ['hero', 'logo', 'modelo_academico'],
            'primaria' => ['hero', 'logo', 'modelo_academico'],
            'secundaria' => ['hero', 'logo', 'modelo_academico'],
            'bachillerato' => ['logo', 'modelo_academico'],
            'ib-en-discovery' => ['hero', 'logo', 'imagen_enfoque'],
            'pop-del-ib' => ['hero', 'logo', 'componentes', 'ruta_data_science', 'ruta_diseno_3d', 'areas_academicas', 'cierre'],
            'certificacion-de-ingles' => ['hero'],
            'academias-vespertinas' => ['academia_basketball', 'academia_origami', 'academia_pickleball', 'academia_soccer', 'academia_urban_dance', 'academia_volleyball'],
            'recursos-escolares' => ['calendario'],
            'contacto' => ['hero', 'secundaria'],
        ];

        foreach ($expected as $vista => $claves) {
            foreach ($claves as $clave) {
                $this->assertTrue(
                    SeccionImagen::query()
                        ->where('vista', $vista)
                        ->where('clave', $clave)
                        ->exists(),
                    "Falta la imagen fija {$vista}:{$clave}.",
                );
            }
        }
    }

    public function test_specialized_image_groups_are_not_listed_as_site_images(): void
    {
        $this->assertFalse(SeccionImagen::query()->where('vista', 'protagonistas')->exists());
        $this->assertFalse(SeccionImagen::query()->where('clave', 'like', 'historia_%')->exists());
        $this->assertFalse(SeccionImagen::query()->where('clave', 'like', 'evento_%')->exists());
        $this->assertFalse(SeccionImagen::query()->where('clave', 'like', 'galeria_%')->exists());
        $this->assertFalse(SeccionImagen::query()->where('clave', 'like', 'banner_%')->exists());
    }

    public function test_pages_using_new_fixed_slots_render_successfully(): void
    {
        $this->get('/')->assertOk();
        $this->get('/oferta-academica')->assertOk();
        $this->get('/oferta-academica/ib-en-discovery')->assertOk();
        $this->get('/oferta-academica/certificacion-de-ingles')->assertOk();
        $this->get('/comunidad/academias-vespertinas')->assertOk();
        $this->get('/recursos-escolares')->assertOk();
    }

    public function test_admin_site_images_list_shows_fixed_slots_without_protagonistas(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($user)
            ->get(SeccionImagenResource::getUrl('index'));

        $response->assertOk();
        $response->assertSee('IB en Discovery® - Imagen principal');
        $response->assertDontSee('Comunidad - Alumnos');
        $response->assertDontSee('Comunidad - Explorers');

        $createResponse = $this
            ->actingAs($user)
            ->get(SeccionImagenResource::getUrl('create'));

        $createResponse->assertOk();
        $createResponse->assertSee('Recursos escolares');
        $createResponse->assertSee('Certificación de Inglés');
        $createResponse->assertSee('Academias Vespertinas');
    }
}
