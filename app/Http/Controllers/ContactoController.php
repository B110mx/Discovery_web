<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;

class ContactoController extends Controller
{
    public function store(Request $request)
    {
        Contacto::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'mensaje' => $request->mensaje,
        ]);

        return back()->with('success', 'Mensaje enviado correctamente');
    }

    public function index()
    {
        $contactos = Contacto::latest()->get();
        return view('admin.contactos', compact('contactos'));
    }
}