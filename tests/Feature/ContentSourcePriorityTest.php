<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\SeccionImagen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentSourcePriorityTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_section_image_has_priority_over_configured_fallbacks(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('secciones/inicio-prioritaria.jpg', 'imagen');

        SeccionImagen::query()
            ->where('vista', 'inicio')
            ->where('clave', 'sobre_nosotros')
            ->firstOrFail()
            ->update([
                'imagen' => 'secciones/inicio-prioritaria.jpg',
                'respaldo_media_path' => 'Kinder fotos actuales/IMG_5775.JPG',
                'activo' => true,
            ]);

        $response = $this->get(route('inicio'));

        $response->assertOk();
        $response->assertSee('/storage/secciones/inicio-prioritaria.jpg', false);
    }

    public function test_active_admin_events_replace_configured_default_events(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('eventos/administrado.jpg', 'imagen');

        Evento::query()->delete();
        Evento::query()->create([
            'titulo' => 'Evento administrado desde Filament',
            'descripcion' => 'Contenido principal de prueba',
            'imagen_url' => 'eventos/administrado.jpg',
            'orden' => 10,
            'activo' => true,
        ]);

        $response = $this->get(route('inicio'));

        $response->assertOk();
        $response->assertSee('Evento administrado desde Filament');
        $response->assertSee('/storage/eventos/administrado.jpg', false);
        $response->assertDontSee(config('colegio.inicio.eventos_default.0.titulo'));
    }
}
