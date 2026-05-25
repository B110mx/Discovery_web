<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $imagenes = [
            ['clave' => 'preescolar', 'titulo' => 'Oferta Educativa - Preescolar', 'referencia' => 'Imagen destacada para Preescolar en la vista Oferta Educativa.', 'orden' => 10],
            ['clave' => 'primaria', 'titulo' => 'Oferta Educativa - Primaria', 'referencia' => 'Imagen destacada para Primaria en la vista Oferta Educativa.', 'orden' => 20],
            ['clave' => 'secundaria', 'titulo' => 'Oferta Educativa - Secundaria', 'referencia' => 'Imagen destacada para Secundaria en la vista Oferta Educativa.', 'orden' => 30],
            ['clave' => 'bachillerato', 'titulo' => 'Oferta Educativa - Bachillerato', 'referencia' => 'Imagen destacada para Bachillerato en la vista Oferta Educativa.', 'orden' => 40],
            ['clave' => 'ib', 'titulo' => 'Oferta Educativa - IB en Discovery', 'referencia' => 'Imagen destacada para IB en Discovery en la vista Oferta Educativa.', 'orden' => 50],
            ['clave' => 'certificacion_ingles', 'titulo' => 'Oferta Educativa - Certificacion de Ingles', 'referencia' => 'Imagen destacada para Certificacion de Ingles en la vista Oferta Educativa.', 'orden' => 60],
        ];

        foreach ($imagenes as $imagen) {
            DB::table('seccion_imagenes')->updateOrInsert(
                ['vista' => 'oferta-academica', 'clave' => $imagen['clave']],
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
            ->where('vista', 'oferta-academica')
            ->whereIn('clave', [
                'preescolar',
                'primaria',
                'secundaria',
                'bachillerato',
                'ib',
                'certificacion_ingles',
            ])
            ->delete();
    }
};
