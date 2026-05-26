<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alumno_nombre' => ['required', 'string', 'max:255'],
            'alumno_nivel' => ['required', 'string', Rule::in(array_keys(config('colegio.tienda.niveles', [])))],
            'alumno_grado' => ['nullable', 'string', 'max:255'],
            'padre_nombre' => ['required', 'string', 'max:255'],
            'padre_telefono' => ['required', 'string', 'max:255'],
            'padre_email' => ['nullable', 'email', 'max:255'],
            'notas' => ['nullable', 'string', 'max:2000'],
            'productos' => ['required', 'array'],
            'productos.*.seleccionado' => ['nullable', 'accepted'],
            'productos.*.cantidad' => ['nullable', 'integer', 'min:1', 'max:99'],
        ];
    }
}
