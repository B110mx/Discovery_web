<?php

namespace App\Services;

use App\Models\Contacto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactoService
{
    /**
     * Procesa y guarda un nuevo contacto desde la web.
     */
    public function registrarContacto(array $datos): Contacto
    {
        $contacto = Contacto::create($datos);

        $this->notificarNuevoContacto($contacto);
        $this->enviarAgradecimiento($contacto);

        return $contacto;
    }

    private function notificarNuevoContacto(Contacto $contacto): void
    {
        try {
            Mail::raw(
                implode("\n\n", [
                    'Se recibio un nuevo mensaje desde el formulario de contacto.',
                    'Nombre: ' . $contacto->nombre,
                    'Email: ' . $contacto->email,
                    'Datos enviados:',
                    $contacto->mensaje,
                ]),
                function ($message) use ($contacto) {
                    $message
                        ->to(config('colegio.contacto.correo', 'informes@colegio-discovery.edu.mx'))
                        ->replyTo($contacto->email, $contacto->nombre)
                        ->subject('Nuevo mensaje desde el sitio web');
                }
            );
        } catch (Throwable $exception) {
            Log::warning('No se pudo enviar el correo interno de contacto.', [
                'contacto_id' => $contacto->id,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    private function enviarAgradecimiento(Contacto $contacto): void
    {
        try {
            Mail::raw(
                implode("\n\n", [
                    'Hola ' . $contacto->nombre . ',',
                    'Gracias por contactar a Colegio Discovery®. Hemos recibido tu información y nuestro equipo se pondrá en contacto contigo a la brevedad.',
                    'Saludos,',
                    'Colegio Internacional Discovery®',
                ]),
                function ($message) use ($contacto) {
                    $message
                        ->to($contacto->email, $contacto->nombre)
                        ->subject('Gracias por contactar a Colegio Discovery®');
                }
            );
        } catch (Throwable $exception) {
            Log::warning('No se pudo enviar el correo de agradecimiento de contacto.', [
                'contacto_id' => $contacto->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
