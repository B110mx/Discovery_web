<?php

namespace Database\Seeders;

use App\Models\BannerInicio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BannerInicioSeeder extends Seeder
{
    public function run(): void
    {
        $disk = Storage::disk(config('colegio.media.disk', 'videosyfotos'));
        $extensions = config('colegio.media.image_extensions', []);

        if (! $disk->directoryExists('Banner de inicio')) {
            return;
        }

        collect($disk->files('Banner de inicio'))
            ->filter(fn (string $path): bool => in_array(
                strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                $extensions,
                true,
            ))
            ->sortBy(fn (string $path): string => str_starts_with(
                strtolower(pathinfo($path, PATHINFO_FILENAME)),
                'banner de bienvenida',
            ) ? '000-'.$path : '100-'.$path)
            ->values()
            ->each(function (string $path, int $index): void {
                $title = pathinfo($path, PATHINFO_FILENAME);

                BannerInicio::query()->firstOrCreate(
                    ['imagen_media_path' => $path],
                    [
                        'titulo' => $title,
                        'texto_alternativo' => $title,
                        'orden' => ($index + 1) * 10,
                        'activo' => true,
                    ],
                );
            });
    }
}
