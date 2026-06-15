<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyJourneyTest extends TestCase
{
    use RefreshDatabase;

    public function test_family_journey_has_a_clear_audience_and_decision_path(): void
    {
        $this->assertStringContainsString(
            'Madres, padres y tutores',
            config('experiencia.audiencia_principal'),
        );

        $this->assertSame(
            ['confianza', 'propuesta', 'pertenencia', 'decision'],
            array_keys(config('experiencia.recorrido_familiar')),
        );

        $this->assertSame('Agenda una visita', config('experiencia.cta_principal.texto'));
        $this->assertSame('contacto', config('experiencia.cta_principal.route'));
    }

    public function test_primary_family_call_to_action_is_available_across_the_public_journey(): void
    {
        foreach ([
            route('inicio'),
            route('nosotros'),
            route('oferta-academica'),
            route('nivel', 'preescolar'),
            route('protagonistas'),
        ] as $url) {
            $response = $this->get($url);

            $response->assertOk();
            $response->assertSee('Agenda una visita');
            $response->assertSee(route('contacto'), false);
        }
    }
}
