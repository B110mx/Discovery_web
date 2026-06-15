<?php

namespace Tests\Feature;

use App\Filament\Resources\NivelContenidos\NivelContenidoResource;
use App\Models\NivelContenido;
use App\Models\User;
use Database\Seeders\NivelContenidoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LevelContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(NivelContenidoSeeder::class);
    }

    public function test_all_configured_levels_have_editable_content_records(): void
    {
        $this->assertSame(
            [
                'bachillerato',
                'certificacion-de-ingles',
                'ib-en-discovery',
                'pop-del-ib',
                'preescolar',
                'primaria',
                'secundaria',
            ],
            NivelContenido::query()->orderBy('slug')->pluck('slug')->all(),
        );
    }

    public function test_level_content_from_database_controls_level_and_offer_pages(): void
    {
        NivelContenido::query()
            ->where('slug', 'preescolar')
            ->firstOrFail()
            ->update([
                'titulo' => 'Kinder editable',
                'descripcion' => 'Descripción editable del hero.',
                'contenido_titulo' => 'Título interior editable',
                'contenido_intro' => 'Introducción interior editable.',
                'oferta_subtitulo' => 'Subtítulo editable de oferta',
                'oferta_descripcion' => 'Descripción editable de oferta.',
                'oferta_edad' => 'Etapa editable',
                'oferta_puntos' => ['Punto editable uno', 'Punto editable dos'],
            ]);

        $levelResponse = $this->get(route('nivel', 'preescolar'));

        $levelResponse->assertOk();
        $levelResponse->assertSee('Kinder editable');
        $levelResponse->assertSee('Descripción editable del hero.');
        $levelResponse->assertSee('Título interior editable');
        $levelResponse->assertSee('Introducción interior editable.');

        $offerResponse = $this->get(route('oferta-academica'));

        $offerResponse->assertOk();
        $offerResponse->assertSee('Subtítulo editable de oferta');
        $offerResponse->assertSee('Descripción editable de oferta.');
        $offerResponse->assertSee('Etapa editable');
        $offerResponse->assertSee('Punto editable uno');
    }

    public function test_level_editor_is_available_without_create_actions(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $content = NivelContenido::query()->where('slug', 'primaria')->firstOrFail();

        $listResponse = $this
            ->actingAs($user)
            ->get(NivelContenidoResource::getUrl('index'));

        $listResponse->assertOk();
        $listResponse->assertSee('Elementary');
        $listResponse->assertDontSee('Crear contenido de nivel');

        $editResponse = $this
            ->actingAs($user)
            ->get(NivelContenidoResource::getUrl('edit', ['record' => $content]));

        $editResponse->assertOk();
        $editResponse->assertSee('Página del nivel');
        $editResponse->assertSee('Tarjeta de Oferta Educativa');
        $editResponse->assertSee('primaria');
    }

    public function test_technical_config_does_not_duplicate_editable_level_copy(): void
    {
        $this->assertArrayNotHasKey('titulo', config('colegio.niveles.definiciones.preescolar'));
        $this->assertArrayNotHasKey('descripcion', config('colegio.niveles.definiciones.preescolar'));
        $this->assertArrayNotHasKey('titulo', config('colegio.oferta_academica.preescolar'));
        $this->assertArrayNotHasKey('descripcion', config('colegio.oferta_academica.preescolar'));
    }

    public function test_default_level_content_uses_degree_symbols_for_school_grades(): void
    {
        $response = $this->get(route('oferta-academica'));

        $response->assertOk();
        $response->assertSee('1° a 6° grado');
        $response->assertSee('5° grado');
        $response->assertSee('7° a 9° grado');
        $response->assertSee('10° a 12° grado');
        $response->assertDontSee('1 a 6 grado');
    }
}
