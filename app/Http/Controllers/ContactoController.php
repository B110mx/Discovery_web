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
        // El $request->validated() ya nos devuelve los datos limpios y seguros
        $this->contactoService->registrarContacto($request->validated());

        return redirect()->back()->with('success', '¡Gracias por comunicarte con el Colegio Discovery! Te contactaremos a la brevedad.');
    }
}