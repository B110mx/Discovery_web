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
                'descripcion' => 'Descripcion editable del hero.',
                'contenido_titulo' => 'Titulo interior editable',
                'contenido_intro' => 'Introduccion interior editable.',
                'oferta_subtitulo' => 'Subtitulo editable de oferta',
                'oferta_descripcion' => 'Descripcion editable de oferta.',
                'oferta_edad' => 'Etapa editable',
                'oferta_puntos' => ['Punto editable uno', 'Punto editable dos'],
            ]);

        $levelResponse = $this->get(route('nivel', 'preescolar'));

        $levelResponse->assertOk();
        $levelResponse->assertSee('Kinder editable');
        $levelResponse->assertSee('Descripcion editable del hero.');
        $levelResponse->assertSee('Titulo interior editable');
        $levelResponse->assertSee('Introduccion interior editable.');

        $offerResponse = $this->get(route('oferta-academica'));

        $offerResponse->assertOk();
        $offerResponse->assertSee('Subtitulo editable de oferta');
        $offerResponse->assertSee('Descripcion editable de oferta.');
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
        $editResponse->assertSee('Pagina del nivel');
        $editResponse->assertSee('Tarjeta de Oferta Educativa');
        $editResponse->assertSee('primaria');

        $popContent = NivelContenido::query()->where('slug', 'pop-del-ib')->firstOrFail();
        $popEditResponse = $this
            ->actingAs($user)
            ->get(NivelContenidoResource::getUrl('edit', ['record' => $popContent]));

        $popEditResponse->assertOk();
        $popEditResponse->assertSee('Rutas preuniversitarias POP');
        $popEditResponse->assertSee('Ruta de Data Science');
        $popEditResponse->assertSee('Ruta de Diseno e Impresion 3D');
    }

    public function test_pop_route_visibility_can_be_controlled_from_database(): void
    {
        NivelContenido::query()
            ->where('slug', 'pop-del-ib')
            ->firstOrFail()
            ->update(['pop_rutas_visibles' => ['diseno_3d']]);

        $response = $this->get(route('nivel', 'pop-del-ib'));

        $response->assertOk();
        $response->assertDontSee('Ruta de Data Science');
        $response->assertSee('Ruta de Diseño e Impresión 3D');
        $response->assertDontSee('POP del IB - Ruta de Data Science');
        $response->assertSee('POP del IB - Ruta de Diseño e Impresión 3D');
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
        $response->assertSee('1° a 5° grado');
        $response->assertSee('5° grado');
        $response->assertSee('6° a 9° grado');
        $response->assertSee('10° a 12° grado');
        $response->assertDontSee('1° a 6° grado');
        $response->assertDontSee('7° a 9° grado');
    }
}
