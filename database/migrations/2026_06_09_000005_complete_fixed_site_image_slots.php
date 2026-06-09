<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $imagenes = [
            ['vista' => 'inicio', 'clave' => 'logo_preescolar', 'titulo' => 'Inicio - Logo Kinder', 'referencia' => 'Logo de Kinder mostrado en la sección de niveles de Inicio.', 'respaldo' => 'Logos de niveles educativos/logo preescolar.png', 'orden' => 20],
            ['vista' => 'inicio', 'clave' => 'logo_primaria', 'titulo' => 'Inicio - Logo Elementary', 'referencia' => 'Logo de Elementary mostrado en la sección de niveles de Inicio.', 'respaldo' => 'Logos de niveles educativos/logo primaria.png', 'orden' => 30],
            ['vista' => 'inicio', 'clave' => 'logo_secundaria', 'titulo' => 'Inicio - Logo Middle', 'referencia' => 'Logo de Middle mostrado en la sección de niveles de Inicio.', 'respaldo' => 'Logos de niveles educativos/logo secundaria.png', 'orden' => 40],
            ['vista' => 'inicio', 'clave' => 'logo_bachillerato', 'titulo' => 'Inicio - Logo High', 'referencia' => 'Logo de High mostrado en la sección de niveles de Inicio.', 'respaldo' => 'Logos de niveles educativos/logo bachillerato.png', 'orden' => 50],

            ['vista' => 'preescolar', 'clave' => 'logo', 'titulo' => 'Kinder - Logo', 'referencia' => 'Logo mostrado en Oferta Educativa y en el encabezado de Kinder.', 'respaldo' => 'Logos de niveles educativos/Preescolar-extendido.png', 'orden' => 20],
            ['vista' => 'preescolar', 'clave' => 'modelo_academico', 'titulo' => 'Kinder - Modelo académico', 'referencia' => 'Infografía del modelo académico de Kinder.', 'respaldo' => 'Modelos educativos/modelo educativo kínder.png', 'orden' => 30],
            ['vista' => 'primaria', 'clave' => 'logo', 'titulo' => 'Elementary - Logo', 'referencia' => 'Logo mostrado en Oferta Educativa y en el encabezado de Elementary.', 'respaldo' => 'Logos de niveles educativos/Primaria-extendido.png', 'orden' => 20],
            ['vista' => 'primaria', 'clave' => 'modelo_academico', 'titulo' => 'Elementary - Modelo académico', 'referencia' => 'Infografía del modelo académico de Elementary.', 'respaldo' => 'Modelos educativos/modelo educativo elementary.png', 'orden' => 30],
            ['vista' => 'secundaria', 'clave' => 'logo', 'titulo' => 'Middle - Logo', 'referencia' => 'Logo mostrado en Oferta Educativa y en el encabezado de Middle.', 'respaldo' => 'Logos de niveles educativos/Secundaria-extendido.png', 'orden' => 20],
            ['vista' => 'secundaria', 'clave' => 'modelo_academico', 'titulo' => 'Middle - Modelo académico', 'referencia' => 'Infografía del modelo académico de Middle.', 'respaldo' => 'Modelos educativos/modelo educativo middle.png', 'orden' => 30],
            ['vista' => 'bachillerato', 'clave' => 'logo', 'titulo' => 'High - Logo', 'referencia' => 'Logo mostrado en Oferta Educativa y en el encabezado de High.', 'respaldo' => 'Logos de niveles educativos/Bachillerato-extendido.png', 'orden' => 20],
            ['vista' => 'bachillerato', 'clave' => 'modelo_academico', 'titulo' => 'High - Modelo académico', 'referencia' => 'Infografía del modelo académico de High.', 'respaldo' => 'Modelos educativos/modelo educativo high.png', 'orden' => 30],

            ['vista' => 'ib-en-discovery', 'clave' => 'hero', 'titulo' => 'IB en Discovery® - Imagen principal', 'referencia' => 'Imagen principal del encabezado de IB en Discovery®.', 'respaldo' => 'Logos principales/IB Internacional.png', 'orden' => 10],
            ['vista' => 'ib-en-discovery', 'clave' => 'logo', 'titulo' => 'IB en Discovery® - Logo', 'referencia' => 'Logo mostrado en Oferta Educativa y en el encabezado de IB en Discovery®.', 'respaldo' => 'Logos principales/logo-ib-y-discovery-principal-1024x342.png', 'orden' => 20],
            ['vista' => 'ib-en-discovery', 'clave' => 'imagen_enfoque', 'titulo' => 'IB en Discovery® - Enfoques del aprendizaje', 'referencia' => 'Imagen de apoyo para los enfoques de aprendizaje IB.', 'respaldo' => 'Enfoque de aprendizaje/Enfoque de aprendizaje Pacis.png', 'orden' => 30],

            ['vista' => 'pop-del-ib', 'clave' => 'logo', 'titulo' => 'POP del IB - Logo', 'referencia' => 'Logo mostrado en Oferta Educativa y en el encabezado del POP del IB.', 'respaldo' => 'Logos principales/logo-ib-y-discovery-principal-1024x342.png', 'orden' => 15],
            ['vista' => 'certificacion-de-ingles', 'clave' => 'hero', 'titulo' => 'Certificación de Inglés - Imagen principal', 'referencia' => 'Imagen principal del encabezado de Certificación de Inglés.', 'respaldo' => 'Certificacion fotos/unicam.jpg', 'orden' => 10],
            ['vista' => 'recursos-escolares', 'clave' => 'calendario', 'titulo' => 'Recursos escolares - Calendario escolar', 'referencia' => 'Imagen del calendario escolar mostrada en Recursos escolares.', 'respaldo' => 'Calendario Escolar/Calendario Escolar 2025-2026.jpg', 'orden' => 10],
        ];

        foreach ($imagenes as $imagen) {
            DB::table('seccion_imagenes')->updateOrInsert(
                ['vista' => $imagen['vista'], 'clave' => $imagen['clave']],
                [
                    'titulo' => $imagen['titulo'],
                    'referencia' => $imagen['referencia'],
                    'respaldo_media_path' => $imagen['respaldo'],
                    'orden' => $imagen['orden'],
                    'activo' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }

        // Protagonistas obtiene grupos completos desde carpetas y rota el hero;
        // no pertenece al editor de imágenes fijas.
        DB::table('seccion_imagenes')
            ->where('vista', 'protagonistas')
            ->delete();
    }

    public function down(): void
    {
        $claves = [
            'inicio' => ['logo_preescolar', 'logo_primaria', 'logo_secundaria', 'logo_bachillerato'],
            'preescolar' => ['logo', 'modelo_academico'],
            'primaria' => ['logo', 'modelo_academico'],
            'secundaria' => ['logo', 'modelo_academico'],
            'bachillerato' => ['logo', 'modelo_academico'],
            'ib-en-discovery' => ['hero', 'logo', 'imagen_enfoque'],
            'pop-del-ib' => ['logo'],
            'certificacion-de-ingles' => ['hero'],
            'recursos-escolares' => ['calendario'],
        ];

        foreach ($claves as $vista => $clavesVista) {
            DB::table('seccion_imagenes')
                ->where('vista', $vista)
                ->whereIn('clave', $clavesVista)
                ->delete();
        }
    }
};
