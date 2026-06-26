<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Genera opciones para selectores de Filament leyendo el disco videosyfotos.
 */
class VideoFotoOptions
{
    public static function images(): array
    {
        return self::optionsForExtensions(config('colegio.media.image_extensions', []));
    }

    public static function videos(): array
    {
        return self::optionsForExtensions(config('colegio.media.video_extensions', []));
    }

    private static function optionsForExtensions(array $extensions): array
    {
        $disk = Storage::disk(config('colegio.media.disk', 'videosyfotos'));
        $extensions = collect($extensions)
            ->map(fn (string $extension): string => strtolower($extension))
            ->all();

        return collect($disk->allFiles())
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $extensions, true))
            ->sort()
            ->mapWithKeys(fn (string $path) => [$path => $path])
            ->all();
    }
}
