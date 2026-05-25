<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Retorna true porque es un formulario público
        return true; 
    }

    public function rules(): array
    {
        return [
            'nombre'  => ['required', 'string', 'max:255', 'strip_tags'],
            'email'   => ['required', 'email', 'max:255'],
            'telefono'=> ['nullable', 'string', 'max:20'],
            'mensaje' => ['required', 'string', 'max:2000', 'strip_tags'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio para poder contactarte.',
            'email.required' => 'Necesitamos un correo electrónico válido.',
        ];
    }
}