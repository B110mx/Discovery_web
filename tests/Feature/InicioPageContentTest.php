<?php

namespace Tests\Feature;

use App\Filament\Resources\Paginas\InicioResource;
use App\Models\PaginaContenido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InicioPageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_content_controls_the_about_section(): void
    {
        PaginaContenido::query()
            ->where('slug', 'inicio')
            ->update([
                'subtitulo' => 'Etiqueta de prueba',
                'titulo' => 'Título editable de prueba',
                'descripcion' => 'Descripción editable de prueba',
            ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Etiqueta de prueba');
        $response->assertSee('Título editable de prueba');
        $response->assertSee('Descripción editable de prueba');
    }

    public function test_home_admin_editor_explains_which_section_each_field_controls(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $page = PaginaContenido::query()->where('slug', 'inicio')->firstOrFail();

        $response = $this
            ->actingAs($user)
            ->get(InicioResource::getUrl('edit', ['record' => $page]));

        $response->assertOk();
        $response->assertSee('Bloque para familias de Inicio');
        $response->assertSee('Título del bloque');
        $response->assertSee('Imágenes de la página');
        $response->assertSee('sobre_nosotros');
        $response->assertDontSee('Imagen principal');
    }

    public function test_home_addresses_families_without_overlaying_copy_on_the_banner(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Una comunidad para crecer juntos');
        $response->assertSee('Agenda una visita');
        $response->assertSee('Una etapa, un acompañamiento');
        $response->assertSee('Conoce esta etapa');
        $response->assertDontSee('El mejor Kinder de Tehuacán');
        $response->assertDontSee('Saber más');
    }
}
