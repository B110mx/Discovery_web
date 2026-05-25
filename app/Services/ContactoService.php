<?php

namespace App\Services;

use App\Models\Contacto;

class ContactoService
{
    /**
     * Procesa y guarda un nuevo contacto desde la web.
     */
    public function registrarContacto(array $datos): Contacto
    {
        // Aquí centralizamos la creación. 
        // Más adelante, aquí podrías integrar el envío de emails (Ej: Mail::to(...))
        return Contacto::create($datos);
    }
}