<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSee('Explore level');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('nivel', 'preescolar'))
            ->assertOk()
            ->assertSee('The ideal kinder for your children')
            ->assertSee('A happy, safe, bilingual childhood')
            ->assertSee('Learning in two languages')
            ->assertSee('Why are we the ideal kinder for your children?')
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
            ->assertSee('IB learner profile')
            ->assertSee('Diploma Programme curriculum')
            ->assertSee('Critical thinking');

        $this
            ->withSession(['locale' => 'en'])
            ->get(route('nivel', 'pop-del-ib'))
            ->assertOk()
            ->assertSee('Data Science Path')
            ->assertSee('Professional interest areas in 11th and 12th grade')
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
}
