<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class VideoFotoOptions
{
    public static function images(): array
    {
        $disk = Storage::disk(config('colegio.media.disk', 'videosyfotos'));
        $extensions = config('colegio.media.image_extensions', []);

        return collect($disk->allFiles())
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $extensions, true))
            ->sort()
            ->mapWithKeys(fn (string $path) => [$path => $path])
            ->all();
    }

    public static function videos(): array
    {
        $disk = Storage::disk(config('colegio.media.disk', 'videosyfotos'));
        $extensions = config('colegio.media.video_extensions', []);

        return collect($disk->allFiles())
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $extensions, true))
            ->sort()
            ->mapWithKeys(fn (string $path) => [$path => $path])
            ->all();
    }
}
