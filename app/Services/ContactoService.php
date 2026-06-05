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

        return $contacto;
    }

    private function notificarNuevoContacto(Contacto $contacto): void
    {
        try {
            Mail::raw(
                implode("\n\n", [
                    'Se recibió una nueva solicitud desde el formulario de contacto del sitio web.',
                    'Datos del tutor:',
                    'Nombre: ' . $contacto->nombre,
                    'Correo: ' . $contacto->email,
                    'Fecha de envío: ' . $contacto->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i'),
                    'Datos enviados:',
                    $contacto->mensaje,
                    'Puedes responder directamente a este correo para contactar a la familia.',
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
}
