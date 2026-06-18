<?php

namespace Tests\Feature;

use App\Models\Evento;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolCalendarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-06-15 10:00:00');
        Evento::query()->delete();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_resources_page_shows_the_requested_month_and_its_events(): void
    {
        Evento::query()->create([
            'titulo' => 'Festival de la familia',
            'descripcion' => 'Actividad para toda la comunidad.',
            'fecha_evento' => '2026-07-10',
            'nivel' => 'general',
            'imagen_url' => 'eventos/festival.jpg',
            'orden' => 1,
            'activo' => true,
        ]);

        Evento::query()->create([
            'titulo' => 'Evento de otro mes',
            'fecha_evento' => '2026-08-10',
            'nivel' => 'primaria',
            'imagen_url' => 'eventos/otro-mes.jpg',
            'orden' => 2,
            'activo' => true,
        ]);

        $response = $this->get(route('recursos-escolares', ['mes' => '2026-07']));

        $response->assertOk();
        $response->assertSee('Julio 2026');
        $response->assertSee('Festival de la familia');
        $response->assertDontSee('Evento de otro mes');
        $response->assertSee('mes=2026-06', false);
        $response->assertSee('mes=2026-08', false);
    }

    public function test_inactive_events_are_hidden_from_the_monthly_calendar(): void
    {
        Evento::query()->create([
            'titulo' => 'Actividad no publicada',
            'fecha_evento' => '2026-06-20',
            'nivel' => 'secundaria',
            'imagen_url' => 'eventos/inactivo.jpg',
            'activo' => false,
        ]);

        $this->get(route('recursos-escolares', ['mes' => '2026-06']))
            ->assertOk()
            ->assertDontSee('Actividad no publicada');
    }

    public function test_home_shows_the_next_dated_events_and_links_to_their_month(): void
    {
        Evento::query()->create([
            'titulo' => 'Reunión de familias',
            'descripcion' => 'Encuentro informativo.',
            'fecha_evento' => '2026-07-03',
            'nivel' => 'preescolar',
            'imagen_url' => 'eventos/reunion.jpg',
            'orden' => 1,
            'activo' => true,
        ]);

        $response = $this->get(route('inicio'));

        $response->assertOk();
        $response->assertSee('Próximas fechas en Discovery®');
        $response->assertSee('Reunión de familias');
        $response->assertSee('Kindergarten');
        $response->assertSeeInOrder([
            'Encuentra el espacio que acompaña lo que tus hijos necesitan hoy',
            'Próximas fechas en Discovery®',
            'Evento próximo',
            'Kindergarten',
            'Reunión de familias',
            'Historias que muestran lo que significa crecer en Discovery®',
            'El siguiente paso puede comenzar aquí',
        ]);
        $response->assertSee('mes=2026-07', false);
        $response->assertSee('#calendario-mensual', false);
    }

    public function test_invalid_month_query_falls_back_to_the_current_month(): void
    {
        $this->get(route('recursos-escolares', ['mes' => 'fecha-invalida']))
            ->assertOk()
            ->assertSee('Junio 2026');
    }
}
