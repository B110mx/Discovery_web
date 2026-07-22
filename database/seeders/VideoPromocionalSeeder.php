<?php

namespace Database\Seeders;

use App\Models\VideoPromocional;
use Illuminate\Database\Seeder;

class VideoPromocionalSeeder extends Seeder
{
    public function run(): void
    {
        $directory = 'Videos Promocionales DSC_ Deliveries Video';
        $videos = [
            ['preescolar', 'Desarrollo de habilidades', 'DSC-CNT-33_ Desarrollo de habilidades_ Reel_ Mayo 2026 V2.mp4', 10],
            ['preescolar', 'Neuroaprendizaje en Maternal', 'DSC-CNT-34_ Maternal - Neuroaprendizaje_ Reel_ Mayo 2026 V3.mp4', 20],
            ['preescolar', 'Ingles en Preescolar', 'DSC-CNT-42_ Preescolar - Inglés_ Reel_ Mayo 2026 V2.mp4', 30],
            ['primaria', 'Ingles en Elementary', 'DSC-CNT-35_ Primaria - Inglés_ Reel_ Mayo 2026 V4.mp4', 10],
            ['secundaria', 'Deporte diario', 'DSC-CNT-37_ Secundaria y Prepa - Deporte diario_ Reel_ Mayo 2026 V3.mp4', 10],
            ['secundaria', 'Ingles y frances', 'DSC-CNT-43_ Middle School - Inglés y Francés_ Reel_ Mayo 2026 V2.mp4', 20],
            ['bachillerato', 'Deporte diario', 'DSC-CNT-37_ Secundaria y Prepa - Deporte diario_ Reel_ Mayo 2026 V3.mp4', 10],
            ['bachillerato', 'Ingles en High School', 'DSC-CNT-45_ High School – Inglés_ Reel_ Mayo 2026.mp4', 20],
            ['pop-del-ib', 'Programa de Orientacion Profesional del IB', 'DSC-CNT-44_ Prepa - POP_ Reel_ Mayo 2026 V2.mp4', 10],
        ];

        foreach ($videos as [$nivel, $titulo, $archivo, $orden]) {
            $baseName = pathinfo($archivo, PATHINFO_FILENAME);
            $optimizedPath = "{$directory}/optimizados/{$archivo}";
            $originalPath = "{$directory}/{$archivo}";

            VideoPromocional::updateOrCreate(
                [
                    'nivel' => $nivel,
                    'titulo' => $titulo,
                ],
                [
                    'video_media_path' => file_exists(base_path('videosyfotos/'.$optimizedPath)) ? $optimizedPath : $originalPath,
                    'portada_media_path' => "{$directory}/portadas/{$baseName}.webp",
                    'orden' => $orden,
                    'activo' => true,
                ],
            );
        }
    }
}
