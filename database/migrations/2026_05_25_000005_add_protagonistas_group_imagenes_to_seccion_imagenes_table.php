<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $imagenes = [
            ['clave' => 'alumnos', 'titulo' => 'Comunidad - Alumnos', 'referencia' => 'Imagen para representar a los alumnos en la seccion Quienes hacen viva nuestra comunidad.', 'orden' => 50],
            ['clave' => 'docentes', 'titulo' => 'Comunidad - Docentes', 'referencia' => 'Imagen para representar a docentes en la seccion Quienes hacen viva nuestra comunidad.', 'orden' => 60],
            ['clave' => 'padres', 'titulo' => 'Comunidad - Familias', 'referencia' => 'Imagen para representar a familias en la seccion Quienes hacen viva nuestra comunidad.', 'orden' => 70],
            ['clave' => 'alumni', 'titulo' => 'Comunidad - Alumni', 'referencia' => 'Imagen para representar a alumni en la seccion Quienes hacen viva nuestra comunidad.', 'orden' => 80],
        ];

        foreach ($imagenes as $imagen) {
            DB::table('seccion_imagenes')->updateOrInsert(
                ['vista' => 'protagonistas', 'clave' => $imagen['clave']],
                [
                    'titulo' => $imagen['titulo'],
                    'referencia' => $imagen['referencia'],
                    'orden' => $imagen['orden'],
                    'activo' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }

    public function down(): void
    {
        DB::table('seccion_imagenes')
            ->where('vista', 'protagonistas')
            ->whereIn('clave', ['alumnos', 'docentes', 'padres', 'alumni'])
            ->delete();
    }
};
