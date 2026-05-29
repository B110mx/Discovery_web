<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('seccion_imagenes')
            ->where('vista', 'carruseles')
            ->whereIn('clave', ['inicio_eventos_1', 'inicio_eventos_2', 'inicio_eventos_3'])
            ->delete();
    }

    public function down(): void
    {
        // Los eventos del inicio ahora se administran desde la tabla eventos.
    }
};
