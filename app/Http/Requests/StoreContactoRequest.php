<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'aspirante_nombre' => is_string($this->aspirante_nombre) ? strip_tags($this->aspirante_nombre) : $this->aspirante_nombre,
            'tutor_nombre' => is_string($this->tutor_nombre) ? strip_tags($this->tutor_nombre) : $this->tutor_nombre,
            'telefono' => is_string($this->telefono) ? strip_tags($this->telefono) : $this->telefono,
            'grado' => is_string($this->grado) ? strip_tags($this->grado) : $this->grado,
        ]);
    }

    public function rules(): array
    {
        return [
            'aspirante_nombre' => ['required', 'string', 'max:255'],
            'tutor_nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'grado' => ['required', 'string', 'max:120', Rule::in(['Kinder', 'Elementary', 'Middle', 'High'])],
        ];
    }

    public function messages(): array
    {
        return [
            'aspirante_nombre.required' => 'El nombre del aspirante es obligatorio.',
            'tutor_nombre.required' => 'El nombre del tutor es obligatorio.',
            'email.required' => 'Necesitamos un correo electronico valido.',
            'telefono.required' => 'Necesitamos un telefono de contacto.',
            'grado.required' => 'Selecciona el grado al que aplica.',
        ];
    }
}
