<?php

namespace Database\Seeders;

use App\Models\GaleriaImagen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class GaleriaImagenSeeder extends Seeder
{
    private const LEVEL_NAMES = [
        'preescolar' => 'Kinder',
        'primaria' => 'Elementary',
        'secundaria' => 'Middle',
        'bachillerato' => 'High',
    ];

    public function run(): void
    {
        $disk = Storage::disk(config('colegio.media.disk', 'videosyfotos'));
        $extensions = config('colegio.media.image_extensions', []);

        foreach (config('colegio.niveles.carpetas_galeria', []) as $level => $directory) {
            if (! isset(self::LEVEL_NAMES[$level]) || ! $disk->directoryExists($directory)) {
                continue;
            }

            collect($disk->files($directory))
                ->filter(fn (string $path): bool => in_array(
                    strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                    $extensions,
                    true,
                ))
                ->sortByDesc(fn (string $path): bool => $level === 'secundaria'
                    && basename($path) === 'Colegio Discovery-59.jpg')
                ->take(12)
                ->values()
                ->each(function (string $path, int $index) use ($level): void {
                    $title = pathinfo($path, PATHINFO_FILENAME);

                    GaleriaImagen::query()->firstOrCreate(
                        [
                            'nivel' => $level,
                            'imagen_media_path' => $path,
                        ],
                        [
                            'titulo' => $title,
                            'texto_alternativo' => self::LEVEL_NAMES[$level].' en Discovery®',
                            'orden' => ($index + 1) * 10,
                            'activo' => true,
                        ],
                    );
                });
        }
    }
}
