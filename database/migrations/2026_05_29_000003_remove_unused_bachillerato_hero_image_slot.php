<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('seccion_imagenes')
            ->where('vista', 'bachillerato')
            ->where('clave', 'hero')
            ->delete();
    }

    public function down(): void
    {
        // Bachillerato toma su hero desde oferta-academica/bachillerato.
    }
};
