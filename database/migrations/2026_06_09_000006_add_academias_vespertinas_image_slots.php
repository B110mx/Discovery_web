<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $imagenes = [
            ['clave' => 'academia_basketball', 'titulo' => 'Basketball', 'respaldo' => 'Academias vespertinas/Basketball.png', 'orden' => 10],
            ['clave' => 'academia_origami', 'titulo' => 'Origami', 'respaldo' => 'Academias vespertinas/Origami.png', 'orden' => 20],
            ['clave' => 'academia_pickleball', 'titulo' => 'Pickleball', 'respaldo' => 'Academias vespertinas/Pickleball.png', 'orden' => 30],
            ['clave' => 'academia_soccer', 'titulo' => 'Soccer', 'respaldo' => 'Academias vespertinas/Soccer.png', 'orden' => 40],
            ['clave' => 'academia_urban_dance', 'titulo' => 'Urban Dance', 'respaldo' => 'Academias vespertinas/Urban Dance.png', 'orden' => 50],
            ['clave' => 'academia_volleyball', 'titulo' => 'Volleyball', 'respaldo' => 'Academias vespertinas/Volleyball.png', 'orden' => 60],
        ];

        foreach ($imagenes as $imagen) {
            DB::table('seccion_imagenes')->updateOrInsert(
                ['vista' => 'academias-vespertinas', 'clave' => $imagen['clave']],
                [
                    'titulo' => $imagen['titulo'],
                    'referencia' => 'Imagen mostrada en la sección Academias en acción.',
                    'respaldo_media_path' => $imagen['respaldo'],
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
            ->where('vista', 'academias-vespertinas')
            ->delete();
    }
};
