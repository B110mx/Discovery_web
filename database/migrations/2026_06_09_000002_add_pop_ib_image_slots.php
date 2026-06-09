<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $imagenes = [
            ['vista' => 'oferta-academica', 'clave' => 'pop_ib', 'titulo' => 'Oferta Educativa - POP del IB', 'referencia' => 'Imagen destacada para presentar el Programa de Orientación Profesional del IB en la vista Oferta Educativa. Se recomienda una fotografía horizontal de Explorers de High trabajando en un proyecto.', 'orden' => 55],
            ['vista' => 'pop-del-ib', 'clave' => 'hero', 'titulo' => 'POP del IB - Encabezado', 'referencia' => 'Imagen principal horizontal del POP. Se recomienda una fotografía de Explorers de High colaborando, presentando un proyecto o recibiendo orientación profesional.', 'orden' => 10],
            ['vista' => 'pop-del-ib', 'clave' => 'componentes', 'titulo' => 'POP del IB - Componentes del programa', 'referencia' => 'Fotografía horizontal de Explorers participando en aprendizaje-servicio, trabajo colaborativo, investigación o una presentación relacionada con los componentes del POP.', 'orden' => 20],
            ['vista' => 'pop-del-ib', 'clave' => 'ruta_data_science', 'titulo' => 'POP del IB - Ruta de Data Science', 'referencia' => 'Fotografía horizontal de Explorers trabajando con computadoras, programación, análisis de datos, inteligencia artificial o tecnología.', 'orden' => 30],
            ['vista' => 'pop-del-ib', 'clave' => 'ruta_diseno_3d', 'titulo' => 'POP del IB - Ruta de Diseño e Impresión 3D', 'referencia' => 'Fotografía horizontal de Explorers diseñando, creando prototipos o utilizando una impresora 3D.', 'orden' => 40],
            ['vista' => 'pop-del-ib', 'clave' => 'areas_academicas', 'titulo' => 'POP del IB - Áreas académicas de 11° y 12°', 'referencia' => 'Fotografía horizontal de Explorers de 11° y 12° en una actividad académica, laboratorio, exposición o sesión de orientación universitaria.', 'orden' => 50],
            ['vista' => 'pop-del-ib', 'clave' => 'cierre', 'titulo' => 'POP del IB - Preparación universitaria', 'referencia' => 'Fotografía horizontal de graduación, orientación universitaria o Explorers proyectando su futuro profesional.', 'orden' => 60],
        ];

        foreach ($imagenes as $imagen) {
            DB::table('seccion_imagenes')->updateOrInsert(
                ['vista' => $imagen['vista'], 'clave' => $imagen['clave']],
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
            ->where(function ($query) {
                $query->where('vista', 'pop-del-ib')
                    ->orWhere(function ($query) {
                        $query->where('vista', 'oferta-academica')
                            ->where('clave', 'pop_ib');
                    });
            })
            ->delete();
    }
};
