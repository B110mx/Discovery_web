<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $imagenes = [
            ['clave' => 'historia_2003', 'titulo' => 'Nosotros - Historia 2003', 'referencia' => 'Imagen para el hito Discovery® Kinder en la linea del tiempo de Nosotros.', 'orden' => 30],
            ['clave' => 'historia_2005', 'titulo' => 'Nosotros - Historia 2005', 'referencia' => 'Imagen para el hito Discovery® Elementary en la linea del tiempo de Nosotros.', 'orden' => 40],
            ['clave' => 'historia_2011', 'titulo' => 'Nosotros - Historia 2011', 'referencia' => 'Imagen para el hito Discovery® Middle en la linea del tiempo de Nosotros.', 'orden' => 50],
            ['clave' => 'historia_2016', 'titulo' => 'Nosotros - Historia 2016', 'referencia' => 'Imagen para el hito Discovery® High en la linea del tiempo de Nosotros.', 'orden' => 60],
            ['clave' => 'historia_2018', 'titulo' => 'Nosotros - Historia 2018', 'referencia' => 'Imagen para el hito Colegio del Mundo IB en la linea del tiempo de Nosotros.', 'orden' => 70],
            ['clave' => 'historia_2019', 'titulo' => 'Nosotros - Historia 2019', 'referencia' => 'Imagen para el hito Nuevas instalaciones en la linea del tiempo de Nosotros.', 'orden' => 80],
            ['clave' => 'historia_2023', 'titulo' => 'Nosotros - Historia 2023', 'referencia' => 'Imagen para el hito DKMUN primera edicion en la linea del tiempo de Nosotros.', 'orden' => 90],
            ['clave' => 'historia_2025', 'titulo' => 'Nosotros - Historia 2025', 'referencia' => 'Imagen para el hito Actualmente en la linea del tiempo de Nosotros.', 'orden' => 100],
        ];

        foreach ($imagenes as $imagen) {
            DB::table('seccion_imagenes')->updateOrInsert(
                ['vista' => 'nosotros', 'clave' => $imagen['clave']],
                [
                    'titulo' => $imagen['titulo'],
                    'referencia' => $imagen['referencia'],
                    'orden' => $imagen['orden'],
                    'activo' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );
        }
    }

    public function down(): void
    {
        DB::table('seccion_imagenes')
            ->where('vista', 'nosotros')
            ->whereIn('clave', [
                'historia_2003',
                'historia_2005',
                'historia_2011',
                'historia_2016',
                'historia_2018',
                'historia_2019',
                'historia_2023',
                'historia_2025',
            ])
            ->delete();
    }
};
