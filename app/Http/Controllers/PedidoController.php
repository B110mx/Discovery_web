<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function create()
    {
        return view('pages.tienda', [
            'productos' => Pedido::productosDisponibles(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'alumno_nombre' => ['required', 'string', 'max:255'],
            'alumno_nivel' => ['required', 'string', 'max:255'],
            'alumno_grado' => ['nullable', 'string', 'max:255'],
            'padre_nombre' => ['required', 'string', 'max:255'],
            'padre_telefono' => ['required', 'string', 'max:255'],
            'padre_email' => ['nullable', 'email', 'max:255'],
            'notas' => ['nullable', 'string'],
            'productos' => ['required', 'array'],
        ]);

        $catalogo = Pedido::productosDisponibles();
        $productos = [];
        $total = 0;

        foreach ($request->input('productos', []) as $slug => $pedidoProducto) {
            if (! isset($pedidoProducto['seleccionado'], $catalogo[$slug])) {
                continue;
            }

            $cantidad = max(1, (int) ($pedidoProducto['cantidad'] ?? 1));
            $producto = $catalogo[$slug];
            $subtotal = $producto['precio'] * $cantidad;

            $productos[] = [
                'slug' => $slug,
                'nombre' => $producto['nombre'],
                'nivel' => $producto['nivel'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        if ($productos === []) {
            return back()
                ->withInput()
                ->withErrors(['productos' => 'Selecciona al menos un producto para realizar el pedido.']);
        }

        Pedido::create([
            ...$data,
            'productos' => $productos,
            'total' => $total,
            'estado' => 'incompleto',
        ]);

        return back()->with('success', 'Pedido enviado correctamente. Nos pondremos en contacto para confirmar la entrega.');
    }
}
