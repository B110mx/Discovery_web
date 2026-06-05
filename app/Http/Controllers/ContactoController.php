<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactoRequest;
use App\Services\ContactoService;
use Illuminate\Http\RedirectResponse;
use Throwable;

class ContactoController extends Controller
{
    public function __construct(
        protected ContactoService $contactoService
    ) {}

    public function store(StoreContactoRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        try {
            $this->contactoService->registrarContacto([
                'nombre' => $datos['tutor_nombre'],
                'email' => $datos['email'],
                'mensaje' => implode("\n", [
                    'Nombre completo del aspirante: ' . $datos['aspirante_nombre'],
                    'Nombre completo del tutor (a): ' . $datos['tutor_nombre'],
                    'Email: ' . $datos['email'],
                    'Teléfono de contacto: ' . $datos['telefono'],
                    'Grado al que aplica: ' . $datos['grado'],
                ]),
            ]);
        } catch (Throwable) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No pudimos enviar tu mensaje en este momento. Por favor intenta nuevamente o comunícate directamente con el colegio.');
        }

        return redirect()->back()->with('success', 'Gracias por comunicarte con el Colegio Discovery®. Te contactaremos a la brevedad.');
    }
}
