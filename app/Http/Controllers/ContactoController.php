<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactoRequest;
use App\Services\ContactoService;
use Illuminate\Http\RedirectResponse;

class ContactoController extends Controller
{
    public function __construct(
        protected ContactoService $contactoService
    ) {}

    public function store(StoreContactoRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        $this->contactoService->registrarContacto([
            'nombre' => $datos['tutor_nombre'],
            'email' => $datos['email'],
            'mensaje' => implode("\n", [
                'Nombre completo del aspirante: ' . $datos['aspirante_nombre'],
                'Nombre completo del tutor (a): ' . $datos['tutor_nombre'],
                'Email: ' . $datos['email'],
                'Telefono de contacto: ' . $datos['telefono'],
                'Grado al que aplica: ' . $datos['grado'],
            ]),
        ]);

        return redirect()->back()->with('success', '¡Gracias por comunicarte con el Colegio Discovery! Te contactaremos a la brevedad.');
    }
}
