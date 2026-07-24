<?php

namespace Tests\Feature;

use App\Services\ContactoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Symfony\Component\Mime\Email;
use Tests\TestCase;

class ContactoFormTest extends TestCase
{
    use RefreshDatabase;

    private function validContactData(array $overrides = []): array
    {
        return array_merge([
            'aspirante_nombre' => 'Alumno Prueba',
            'tutor_nombre' => 'Tutor Prueba',
            'email' => 'familia@example.com',
            'telefono' => '2381234567',
            'grado' => 'Elementary',
        ], $overrides);
    }

    public function test_contact_form_sends_only_internal_email(): void
    {
        config(['colegio.contacto.correo' => 'informes@colegio-discovery.edu.mx']);

        Mail::shouldReceive('raw')
            ->once()
            ->with(
                Mockery::on(fn (string $body): bool => str_contains($body, 'Se recibió una nueva solicitud')),
                Mockery::on(function (callable $callback): bool {
                    $email = new Email;
                    $message = new Message($email);

                    $callback($message);

                    $to = collect($email->getTo())->map(fn ($address) => $address->getAddress())->all();

                    return $to === ['informes@colegio-discovery.edu.mx']
                        && ! in_array('familia@example.com', $to, true);
                }),
            );

        $response = $this->post(route('contacto.store'), $this->validContactData());

        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Gracias por comunicarte con el Colegio Discovery®. Te contactaremos a la brevedad.');
    }

    public function test_contact_controller_maps_and_sanitizes_form_fields_for_the_service(): void
    {
        $service = Mockery::mock(ContactoService::class);
        $service->shouldReceive('registrarContacto')
            ->once()
            ->with([
                'nombre' => 'Tutor Prueba',
                'email' => 'familia@example.com',
                'mensaje' => implode("\n", [
                    'Nombre completo del aspirante: Alumno Prueba',
                    'Nombre completo del tutor (a): Tutor Prueba',
                    'Email: familia@example.com',
                    'Teléfono de contacto: 2381234567',
                    'Grado al que aplica: Elementary',
                ]),
            ]);
        $this->app->instance(ContactoService::class, $service);

        $response = $this
            ->from(route('contacto'))
            ->post(route('contacto.store'), $this->validContactData([
                'aspirante_nombre' => '<b>Alumno Prueba</b>',
                'tutor_nombre' => '<script>Tutor Prueba</script>',
            ]));

        $response
            ->assertRedirect(route('contacto'))
            ->assertSessionHas('success');
    }

    public function test_contact_service_failure_returns_an_error_and_preserves_input(): void
    {
        $service = Mockery::mock(ContactoService::class);
        $service->shouldReceive('registrarContacto')
            ->once()
            ->andThrow(new \RuntimeException('Mail provider unavailable'));
        $this->app->instance(ContactoService::class, $service);

        $response = $this
            ->from(route('contacto'))
            ->post(route('contacto.store'), $this->validContactData());

        $response
            ->assertRedirect(route('contacto'))
            ->assertSessionHasInput('email', 'familia@example.com')
            ->assertSessionHas(
                'error',
                'No pudimos enviar tu mensaje en este momento. Por favor intenta nuevamente o comunícate directamente con el colegio.',
            );
    }

    public function test_invalid_contact_form_never_calls_the_service(): void
    {
        $service = Mockery::mock(ContactoService::class);
        $service->shouldNotReceive('registrarContacto');
        $this->app->instance(ContactoService::class, $service);

        $response = $this
            ->from(route('contacto'))
            ->post(route('contacto.store'), $this->validContactData([
                'email' => 'correo-invalido',
                'grado' => 'Universidad',
            ]));

        $response
            ->assertRedirect(route('contacto'))
            ->assertSessionHasErrors(['email', 'grado']);
    }
}
