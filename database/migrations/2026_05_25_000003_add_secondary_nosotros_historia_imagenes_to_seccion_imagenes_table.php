<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $imagenes = [
            ['clave' => 'historia_2003_2', 'titulo' => 'Nosotros - Historia 2003 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito Discovery® Kinder en la linea del tiempo de Nosotros.', 'orden' => 31],
            ['clave' => 'historia_2005_2', 'titulo' => 'Nosotros - Historia 2005 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito Discovery® Elementary en la linea del tiempo de Nosotros.', 'orden' => 41],
            ['clave' => 'historia_2019_2', 'titulo' => 'Nosotros - Historia 2019 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito Nuevas instalaciones en la linea del tiempo de Nosotros.', 'orden' => 81],
            ['clave' => 'historia_2023_2', 'titulo' => 'Nosotros - Historia 2023 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito DKMUN primera edicion en la linea del tiempo de Nosotros.', 'orden' => 91],
        ];

        foreach ($imagenes as $imagen) {
            DB::table('seccion_imagenes')->updateOrInsert(
                ['vista' => 'nosotros', 'clave' => $imagen['clave']],
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
            ->where('vista', 'nosotros')
            ->whereIn('clave', [
                'historia_2003_2',
                'historia_2005_2',
                'historia_2019_2',
                'historia_2023_2',
            ])
            ->delete();
    }
};
