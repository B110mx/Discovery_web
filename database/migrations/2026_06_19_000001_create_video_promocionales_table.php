<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_promocionales', function (Blueprint $table) {
            $table->id();
            $table->string('nivel');
            $table->string('titulo');
            $table->string('video')->nullable();
            $table->string('video_media_path')->nullable();
            $table->string('portada')->nullable();
            $table->string('portada_media_path')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['nivel', 'activo', 'orden']);
        });

        $directory = 'Videos Promocionales DSC_ Deliveries Video';
        $videos = [
            ['preescolar', 'Desarrollo de habilidades', 'DSC-CNT-33_ Desarrollo de habilidades_ Reel_ Mayo 2026 V2.mp4', 10],
            ['preescolar', 'Neuroaprendizaje en Maternal', 'DSC-CNT-34_ Maternal - Neuroaprendizaje_ Reel_ Mayo 2026 V3.mp4', 20],
            ['preescolar', 'Inglés en Preescolar', 'DSC-CNT-42_ Preescolar - Inglés_ Reel_ Mayo 2026 V2.mp4', 30],
            ['primaria', 'Inglés en Elementary', 'DSC-CNT-35_ Primaria - Inglés_ Reel_ Mayo 2026 V4.mp4', 10],
            ['secundaria', 'Deporte diario', 'DSC-CNT-37_ Secundaria y Prepa - Deporte diario_ Reel_ Mayo 2026 V3.mp4', 10],
            ['secundaria', 'Inglés y francés', 'DSC-CNT-43_ Middle School - Inglés y Francés_ Reel_ Mayo 2026 V2.mp4', 20],
            ['bachillerato', 'Deporte diario', 'DSC-CNT-37_ Secundaria y Prepa - Deporte diario_ Reel_ Mayo 2026 V3.mp4', 10],
            ['bachillerato', 'Inglés en High School', 'DSC-CNT-45_ High School – Inglés_ Reel_ Mayo 2026.mp4', 20],
            ['pop-del-ib', 'Programa de Orientación Profesional del IB®', 'DSC-CNT-44_ Prepa - POP_ Reel_ Mayo 2026 V2.mp4', 10],
        ];
        $now = now();

        foreach ($videos as [$nivel, $titulo, $archivo, $orden]) {
            $baseName = pathinfo($archivo, PATHINFO_FILENAME);
            $optimizedPath = "{$directory}/optimizados/{$archivo}";
            $originalPath = "{$directory}/{$archivo}";

            DB::table('video_promocionales')->insert([
                'nivel' => $nivel,
                'titulo' => $titulo,
                'video_media_path' => file_exists(base_path('videosyfotos/'.$optimizedPath)) ? $optimizedPath : $originalPath,
                'portada_media_path' => "{$directory}/portadas/{$baseName}.webp",
                'orden' => $orden,
                'activo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('video_promocionales');
    }
};
