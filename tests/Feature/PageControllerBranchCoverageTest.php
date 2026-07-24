<?php

namespace Tests\Feature;

use App\Http\Controllers\PageController;
use App\Models\Evento;
use App\Models\SeccionImagen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PageControllerBranchCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_does_not_fall_back_to_defaults_when_all_admin_events_are_expired(): void
    {
        Carbon::setTestNow('2026-07-24 16:00:00');
        Evento::create([
            'titulo' => 'Evento vencido',
            'fecha_evento' => '2026-07-24',
            'nivel' => 'general',
            'imagen_url' => '',
            'orden' => 1,
            'activo' => true,
        ]);

        $this->get(route('inicio'))
            ->assertOk()
            ->assertViewHas('eventos', []);

        Carbon::setTestNow();
    }

    public function test_english_home_localizes_an_admin_event_without_a_description(): void
    {
        Evento::create([
            'titulo' => 'Título administrativo',
            'descripcion' => null,
            'fecha_evento' => now()->addDay(),
            'nivel' => 'primaria',
            'imagen_url' => '',
            'orden' => 1,
            'activo' => true,
        ]);

        $response = $this->get(route('inicio', ['lang' => 'en']));

        $response->assertOk();
        $event = $response->viewData('eventos')[0];
        $this->assertNotSame('Título administrativo', $event['titulo']);
        $this->assertNotEmpty($event['descripcion']);
    }

    public function test_contact_accepts_an_empty_map_configuration(): void
    {
        config([
            'colegio.contacto.mapa_embed_url' => null,
            'colegio.contacto.mapa_url' => null,
        ]);

        $this->get(route('contacto'))
            ->assertOk()
            ->assertViewHas('mapaUrl', null)
            ->assertViewHas('mapaExternoUrl', null);
    }

    public function test_english_fallback_testimonial_and_academy_video_titles_are_localized(): void
    {
        Storage::fake('videosyfotos');
        Storage::disk('videosyfotos')->put('Testimonios Alumni/alumni.mp4', 'video');
        Storage::disk('videosyfotos')->put('Academias vespertinas/robotica.mp4', 'video');

        $home = $this->get(route('inicio', ['lang' => 'en']));
        $academy = $this->get(route('academias-vespertinas', ['lang' => 'en']));

        $home->assertOk();
        $academy->assertOk();
        $this->assertNotSame('alumni', $home->viewData('testimonios')[0]['titulo']);
        $this->assertNotSame('robotica', $academy->viewData('mediaAcademias')['videos'][0]['titulo']);
    }

    public function test_spanish_fallback_media_titles_keep_the_file_names(): void
    {
        Storage::fake('videosyfotos');
        Storage::disk('videosyfotos')->put('Testimonios Alumni/alumni-destacado.mp4', 'video');
        Storage::disk('videosyfotos')->put('Academias vespertinas/robotica_creativa.mp4', 'video');

        $home = $this->get(route('inicio'));
        $academy = $this->get(route('academias-vespertinas'));

        $this->assertSame('alumni-destacado', $home->viewData('testimonios')[0]['titulo']);
        $this->assertSame('robotica creativa', $academy->viewData('mediaAcademias')['videos'][0]['titulo']);
    }

    public function test_level_resolves_optional_reference_images(): void
    {
        Storage::fake('videosyfotos');
        Storage::disk('videosyfotos')->put('referencias/aula.jpg', 'imagen');
        $definitions = config('colegio.niveles.definiciones');
        $definitions['preescolar']['informacion']['imagenes_referencia'] = [
            ['media_path' => 'referencias/aula.jpg'],
            ['url' => 'https://example.com/existente.jpg'],
        ];
        config(['colegio.niveles.definiciones' => $definitions]);

        $response = $this->get(route('nivel', 'preescolar'));

        $response->assertOk();
        $images = $response->viewData('nivel')['informacion']['imagenes_referencia'];
        $this->assertStringStartsWith('/media/referencias/aula.jpg?v=', $images[0]['url']);
        $this->assertSame('https://example.com/existente.jpg', $images[1]['url']);
    }

    public function test_offer_handles_a_non_array_university_translation(): void
    {
        app('translator')->addLines([
            'site.pages.offer.universities' => 'sin traducciones estructuradas',
        ], 'es');

        $view = app(PageController::class)->ofertaAcademica();

        $this->assertSame('', $view->getData()['universidadesVinculacion'][0]['resumen']);
        $this->assertSame([], $view->getData()['universidadesVinculacion'][0]['beneficios']);
    }

    public function test_protagonist_images_use_admin_records_and_then_defaults(): void
    {
        Storage::fake('videosyfotos');
        Storage::fake('public');
        Storage::disk('public')->put('protagonistas/alumno.jpg', 'imagen');
        SeccionImagen::create([
            'vista' => 'protagonistas',
            'clave' => 'alumnos',
            'titulo' => 'Alumno administrado',
            'referencia' => 'Referencia administrada',
            'imagen' => 'protagonistas/alumno.jpg',
            'orden' => 1,
            'activo' => true,
        ]);

        $response = $this->get(route('protagonistas'));

        $response->assertOk();
        $groups = $response->viewData('comunidad')['protagonistas'];
        $this->assertSame('/storage/protagonistas/alumno.jpg', $groups['alumnos']['imagenes'][0]['url']);
        $this->assertCount(1, $groups['docentes']['imagenes']);
    }
}
