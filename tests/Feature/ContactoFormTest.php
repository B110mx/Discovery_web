<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Symfony\Component\Mime\Email;
use Tests\TestCase;

class ContactoFormTest extends TestCase
{
    use RefreshDatabase;

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

        $response = $this->post(route('contacto.store'), [
            'aspirante_nombre' => 'Alumno Prueba',
            'tutor_nombre' => 'Tutor Prueba',
            'email' => 'familia@example.com',
            'telefono' => '2381234567',
            'grado' => 'Elementary',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Gracias por comunicarte con el Colegio Discovery®. Te contactaremos a la brevedad.');
    }
}
