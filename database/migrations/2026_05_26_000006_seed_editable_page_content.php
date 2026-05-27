<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $paginas = [
            [
                'slug' => 'inicio',
                'titulo' => 'Colegio Internacional Discovery',
                'subtitulo' => 'Inicio',
                'descripcion' => 'Formando lideres del futuro con educacion de excelencia',
            ],
            [
                'slug' => 'nosotros',
                'titulo' => 'Colegio Discovery',
                'subtitulo' => 'Conocenos',
                'descripcion' => 'Una comunidad educativa que acompana a cada estudiante en su crecimiento academico, humano y social.',
            ],
            [
                'slug' => 'oferta-academica',
                'titulo' => 'Una ruta academica para cada etapa',
                'subtitulo' => 'Oferta Educativa',
                'descripcion' => 'Explora niveles, enfoques y experiencias de aprendizaje para encontrar el programa que mejor acompana a tu familia.',
            ],
            [
                'slug' => 'protagonistas',
                'titulo' => 'Protagonistas',
                'subtitulo' => 'Comunidad Discovery',
                'descripcion' => 'En Discovery alumnos, padres de familia, docentes y alumni trabajamos en equipo para formar una comunidad de aprendizaje con mentalidad internacional.',
            ],
        ];

        foreach ($paginas as $pagina) {
            DB::table('pagina_contenidos')->updateOrInsert(
                ['slug' => $pagina['slug']],
                [
                    ...$pagina,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );
        }
    }

    public function down(): void
    {
        DB::table('pagina_contenidos')
            ->whereIn('slug', ['inicio', 'nosotros', 'oferta-academica', 'protagonistas'])
            ->delete();
    }
};
