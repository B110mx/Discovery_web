<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class FilamentMediaPreview
{
    public static function url(?string $uploadedPath, ?string $mediaPath): ?string
    {
        if ($uploadedPath && Storage::disk('public')->exists($uploadedPath)) {
            return Storage::disk('public')->url($uploadedPath);
        }

        if (! $mediaPath) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $mediaPath), '/');

        if (! Storage::disk(config('colegio.media.disk', 'videosyfotos'))->exists($path)) {
            return null;
        }

        return '/media/'.collect(explode('/', $path))
            ->map(fn (string $segment): string => rawurlencode($segment))
            ->implode('/');
    }
}
