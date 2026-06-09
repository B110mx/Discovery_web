<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hito_historias', function (Blueprint $table) {
            $table->string('imagen_media_path')->nullable()->after('imagen_url');
            $table->string('imagen_secundaria_media_path')->nullable()->after('imagen_secundaria_url');
        });

        $imagenes = DB::table('seccion_imagenes')
            ->where('vista', 'nosotros')
            ->where('clave', 'like', 'historia_%')
            ->get()
            ->keyBy('clave');

        DB::table('hito_historias')->orderBy('id')->get()->each(function (object $hito) use ($imagenes): void {
            $principal = $imagenes->get("historia_{$hito->anio}");
            $secundaria = $imagenes->get("historia_{$hito->anio}_2");

            DB::table('hito_historias')
                ->where('id', $hito->id)
                ->update([
                    'imagen_url' => $hito->imagen_url ?: $principal?->imagen,
                    'imagen_media_path' => $principal?->respaldo_media_path,
                    'imagen_secundaria_url' => $hito->imagen_secundaria_url ?: $secundaria?->imagen,
                    'imagen_secundaria_media_path' => $secundaria?->respaldo_media_path,
                    'updated_at' => now(),
                ]);
        });

        DB::table('seccion_imagenes')
            ->where('vista', 'nosotros')
            ->where('clave', 'like', 'historia_%')
            ->delete();
    }

    public function down(): void
    {
        $now = now();

        DB::table('hito_historias')->orderBy('id')->get()->each(function (object $hito) use ($now): void {
            foreach ([
                ["historia_{$hito->anio}", $hito->imagen_url, $hito->imagen_media_path, 0],
                ["historia_{$hito->anio}_2", $hito->imagen_secundaria_url, $hito->imagen_secundaria_media_path, 1],
            ] as [$clave, $imagen, $mediaPath, $offset]) {
                if (! $imagen && ! $mediaPath) {
                    continue;
                }

                DB::table('seccion_imagenes')->updateOrInsert(
                    ['vista' => 'nosotros', 'clave' => $clave],
                    [
                        'titulo' => "Nosotros - Historia {$hito->anio}" . ($offset ? ' - Imagen secundaria' : ''),
                        'referencia' => "Imagen de la linea del tiempo para el hito {$hito->titulo}.",
                        'imagen' => $imagen,
                        'respaldo_media_path' => $mediaPath,
                        'orden' => ((int) $hito->orden * 10) + $offset,
                        'activo' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                );
            }
        });

        Schema::table('hito_historias', function (Blueprint $table) {
            $table->dropColumn(['imagen_media_path', 'imagen_secundaria_media_path']);
        });
    }
};
