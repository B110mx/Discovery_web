<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => is_string($this->nombre) ? strip_tags($this->nombre) : $this->nombre,
            'mensaje' => is_string($this->mensaje) ? strip_tags($this->mensaje) : $this->mensaje,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'mensaje' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio para poder contactarte.',
            'email.required' => 'Necesitamos un correo electronico valido.',
        ];
    }
}
