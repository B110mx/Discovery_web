<?php

namespace Tests\Feature;

use App\Models\ListaUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicPageContractsTest extends TestCase
{
    use RefreshDatabase;

    public function test_configured_academic_levels_render_their_current_content(): void
    {
        $levels = [
            'preescolar' => ['Kindergarten', 'Programas de neuroaprendizaje'],
            'primaria' => ['Elementary', 'Unidades de Indagación'],
            'secundaria' => ['Middle School', 'Habilidades del Siglo XXI'],
            'bachillerato' => ['High School', 'Orientación vocacional personalizada'],
            'ib-en-discovery' => ['Bachillerato Internacional® en Discovery®', 'Perfil IB®'],
            'pop-del-ib' => ['Preparando Explorers para construir su futuro', 'Ruta de Data Science'],
            'certificacion-de-ingles' => ['Certificación de Inglés', 'Cambridge'],
        ];

        foreach ($levels as $slug => $expectedTexts) {
            $response = $this->get(route('nivel', $slug));

            $response->assertOk();

            foreach ($expectedTexts as $text) {
                $response->assertSee($text);
            }
        }
    }

    public function test_unknown_academic_level_is_not_available(): void
    {
        $this->get(route('nivel', 'nivel-inexistente'))->assertNotFound();
    }

    public function test_academic_offer_lists_current_clickable_university_agreements(): void
    {
        $response = $this->get(route('oferta-academica'));

        $response->assertOk();
        $response->assertSee('INQBA');
        $response->assertSee('https://inqba.edu.mx/', false);
        $response->assertSee('Beca empresarial de 40%');
        $response->assertSee('Revisar convocatoria');
        $response->assertSee('data-university-linkage', false);
        $response->assertDontSee('Universidad Panamericana');
    }

    public function test_active_admin_school_supply_lists_are_rendered(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('listas-utiles/elementary-tercero.pdf', 'PDF de prueba');

        ListaUtil::query()->create([
            'ciclo_escolar' => '2026-2027',
            'nivel' => 'Elementary',
            'grado' => '3°',
            'titulo' => 'Lista administrable de prueba',
            'archivo_pdf' => 'listas-utiles/elementary-tercero.pdf',
            'orden' => 10,
            'activo' => true,
        ]);

        $response = $this->get(route('recursos-escolares'));

        $response->assertOk();
        $response->assertSee('Lista administrable de prueba');
        $response->assertSee('Elementary');
        $response->assertSee('2026-2027');
        $response->assertSee('/storage/listas-utiles/elementary-tercero.pdf', false);
    }
}
