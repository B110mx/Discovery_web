<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\ListaUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LanguageSwitchingTest extends TestCase
{
    use RefreshDatabase;

    public function test_language_query_updates_locale_and_stores_the_preference(): void
    {
        $response = $this->get(route('inicio', ['lang' => 'en']));

        $response->assertOk();
        $response->assertSessionHas('locale', 'en');
        $response->assertSee('<html lang="en">', false);
        $response->assertSee('aria-label="Language selector"', false);
        $response->assertSee('?lang=es', false);
        $response->assertSee('Schedule a visit');
        $response->assertSee('About Us');
        $response->assertSee('School Resources');
        $response->assertSee('What is an Explorer?');
        $response->assertSee('Customer support');
        $response->assertSee('Privacy notice');
    }

    public function test_session_locale_is_used_when_no_query_is_present(): void
    {
        $response = $this
            ->withSession(['locale' => 'en'])
            ->get(route('nosotros'));

        $response->assertOk();
        $response->assertSee('<html lang="en">', false);
        $response->assertSee('/nosotros?lang=en', false);
        $response->assertSee('Academic Offer');
        $response->assertSee('A community that educates to transcend');
    }

    public function test_public_page_copy_uses_the_selected_language(): void
    {
        $this
            ->withSession(['locale' => 'en'])
            ->get(route('oferta-academica'))
            ->assertOk()
            ->assertSee('An academic path for every stage')
            ->assertSee('A close environment to begin learning with creativity')
            ->assertSee('Quick comparison')
            ->assertSee('Explore level')
            ->assertSee('Business, excellence, and project-based scholarships.')
            ->assertSee('40% business scholarship with an 8.5 GPA')
            ->assertDontSee('Beca empresarial de 40%');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('nivel', 'preescolar'))
            ->assertOk()
            ->assertSee('The ideal Kindergarten for your children')
            ->assertSee('A happy, safe, bilingual childhood')
            ->assertSee('Learning in two languages')
            ->assertSee('Why are we the ideal Kindergarten for your children?')
            ->assertSee('Neurolearning programs');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('nivel', 'primaria'))
            ->assertOk()
            ->assertSee('Why is Elementary right for your children?')
            ->assertSee('Bilingual and trilingual education');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('nivel', 'ib-en-discovery'))
            ->assertOk()
            ->assertSee('IB® learner profile')
            ->assertSee('Diploma Programme curriculum')
            ->assertSee('Critical thinking');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('nivel', 'pop-del-ib'))
            ->assertOk()
            ->assertSee('Data Science Path')
            ->assertSee('3D Design and Printing Path')
            ->assertSee('Discovery pre-university paths')
            ->assertSee('The future is not waited for, it is built.');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('nivel', 'certificacion-de-ingles'))
            ->assertOk()
            ->assertSee('Comprehensive preparation by skills')
            ->assertSee('Evidence of proficiency for new opportunities');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('protagonistas'))
            ->assertOk()
            ->assertSee('The people who bring our community to life')
            ->assertSee('Message from our founder');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('academias-vespertinas'))
            ->assertOk()
            ->assertSee('Afternoon Academies')
            ->assertSee('Spaces designed so our Explorers and families')
            ->assertSee('Available academies')
            ->assertSee('Mondays and Wednesdays');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('recursos-escolares'))
            ->assertOk()
            ->assertSee('Supply lists and calendar')
            ->assertSee('Monthly calendar');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('contacto'))
            ->assertOk()
            ->assertSee('Schedule information or request admissions')
            ->assertSee('Come visit us');
    }

    public function test_invalid_language_falls_back_to_spanish(): void
    {
        $response = $this->get(route('inicio', ['lang' => 'fr']));

        $response->assertOk();
        $response->assertSee('<html lang="es">', false);
    }

    public function test_dynamic_public_content_uses_safe_english_fallbacks(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('listas-utiles/elementary-tercero.pdf', 'PDF de prueba');

        Evento::query()->create([
            'titulo' => 'Evento administrado en español',
            'descripcion' => 'Descripción administrada en español',
            'fecha_evento' => '2026-08-05',
            'nivel' => 'general',
            'imagen_url' => '',
            'orden' => 10,
            'activo' => true,
        ]);

        ListaUtil::query()->create([
            'ciclo_escolar' => '2026-2027',
            'nivel' => 'Elementary',
            'grado' => '3° grado',
            'titulo' => 'Lista administrable en español',
            'archivo_pdf' => 'listas-utiles/elementary-tercero.pdf',
            'orden' => 10,
            'activo' => true,
        ]);

        $this
            ->get(route('recursos-escolares', ['lang' => 'en', 'mes' => '2026-08']))
            ->assertOk()
            ->assertSee('August 2026')
            ->assertSee('Wed')
            ->assertSee('Discovery® event')
            ->assertSee('the whole community')
            ->assertSee('Supply list for 3rd grade')
            ->assertDontSee('Evento administrado en español')
            ->assertDontSee('Descripción administrada en español')
            ->assertDontSee('Lista administrable en español')
            ->assertDontSee('Mié');
    }

    public function test_english_contact_page_uses_localized_seo_map_and_whatsapp_text(): void
    {
        $response = $this->get(route('contacto', ['lang' => 'en']));

        $response->assertOk();
        $response->assertSee('<title>Contact | Colegio Discovery</title>', false);
        $response->assertSee('<meta name="description" content="Information and admissions for Colegio Discovery in Tehuacan, Puebla.">', false);
        $response->assertSee('hl=en-US', false);
        $response->assertSee('Hello%2C%20I%20would%20like%20to%20receive%20information%20about%20Colegio%20Discovery', false);
    }
}
