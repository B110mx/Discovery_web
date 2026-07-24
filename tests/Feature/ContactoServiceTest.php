<?php

namespace Tests\Feature;

use App\Services\ContactoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class ContactoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_mail_failure_is_logged_rethrown_and_keeps_the_contact_record(): void
    {
        Mail::shouldReceive('raw')
            ->once()
            ->andThrow(new RuntimeException('Proveedor no disponible'));
        Log::shouldReceive('warning')
            ->once()
            ->with(
                'No se pudo enviar el correo interno de contacto.',
                \Mockery::on(fn (array $context): bool => is_int($context['contacto_id'])
                    && $context['error'] === 'Proveedor no disponible'),
            );

        try {
            app(ContactoService::class)->registrarContacto([
                'nombre' => 'Tutor de prueba',
                'email' => 'tutor@example.com',
                'mensaje' => 'Solicitud de informes',
            ]);

            $this->fail('La excepción del proveedor debía relanzarse.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Proveedor no disponible', $exception->getMessage());
        }

        $this->assertDatabaseHas('contactos', [
            'email' => 'tutor@example.com',
            'mensaje' => 'Solicitud de informes',
        ]);
    }
}
