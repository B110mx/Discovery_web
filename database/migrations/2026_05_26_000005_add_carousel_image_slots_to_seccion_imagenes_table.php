<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $slots = [
            [
                'clave' => 'inicio_eventos_1',
                'titulo' => 'Inicio - Carrusel de eventos - Slide 1',
                'referencia' => 'Espacio editable para el primer modulo del carrusel de eventos en Inicio. Subir aqui el arte o foto definitiva.',
                'orden' => 10,
            ],
            [
                'clave' => 'inicio_eventos_2',
                'titulo' => 'Inicio - Carrusel de eventos - Slide 2',
                'referencia' => 'Espacio editable para el segundo modulo del carrusel de eventos en Inicio. Subir aqui el arte o foto definitiva.',
                'orden' => 20,
            ],
            [
                'clave' => 'inicio_eventos_3',
                'titulo' => 'Inicio - Carrusel de eventos - Slide 3',
                'referencia' => 'Espacio editable para el tercer modulo del carrusel de eventos en Inicio. Subir aqui el arte o foto definitiva.',
                'orden' => 30,
            ],
        ];

        foreach ($slots as $slot) {
            DB::table('seccion_imagenes')->updateOrInsert(
                ['vista' => 'carruseles', 'clave' => $slot['clave']],
                [
                    'titulo' => $slot['titulo'],
                    'referencia' => $slot['referencia'],
                    'imagen' => null,
                    'respaldo_media_path' => null,
                    'orden' => $slot['orden'],
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
            ->where('vista', 'carruseles')
            ->whereIn('clave', ['inicio_eventos_1', 'inicio_eventos_2', 'inicio_eventos_3'])
            ->delete();
    }
};
