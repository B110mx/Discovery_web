<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Solicitud de informes recibida desde el formulario público. */
class Contacto extends Model
{
    protected $fillable = ['nombre', 'email', 'mensaje'];
}
