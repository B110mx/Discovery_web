<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('seccion_imagenes')
            ->where('vista', 'protagonistas')
            ->whereIn('clave', ['preescolar', 'primaria', 'secundaria', 'bachillerato'])
            ->delete();
    }

    public function down(): void
    {
        // La vista de protagonistas ahora usa alumnos, docentes, padres y alumni.
    }
};
