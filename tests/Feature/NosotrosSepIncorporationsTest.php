<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NosotrosSepIncorporationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_sep_incorporations_include_the_school_name_and_level_colors(): void
    {
        $response = $this->get(route('nosotros'));

        $response->assertOk();
        $response->assertSee('Maternal: 21PDI0093H - Colegio Discovery Nuevo Millenium');
        $response->assertSee('Kinder: 21PJN0912U - Colegio Discovery Nuevo Millenium');
        $response->assertSee('Elementary: 21PPR0078B - Colegio Discovery Nuevo Millenium');
        $response->assertSee('Middle: 21PES0097J - Colegio Discovery Nuevo Millenium');
        $response->assertSee('High: 21PBH0513D - Colegio Discovery Nuevo Millenium');
        $response->assertSee('bg-lime-50', false);
        $response->assertSee('bg-red-50', false);
        $response->assertSee('bg-blue-50', false);
        $response->assertSee('bg-green-50', false);
    }
}
