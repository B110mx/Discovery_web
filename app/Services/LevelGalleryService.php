<?php

namespace App\Services;

use App\Models\GaleriaImagen;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;

class LevelGalleryService
{
    public function __construct(private readonly MediaResolver $media) {}

    public function get(string $level, string $levelTitle): array
    {
        return Cache::remember(
            SiteCache::key("galeria.{$level}"),
            SiteCache::ttl(),
            fn (): array => $this->resolve($level, $levelTitle),
        );
    }

    private function resolve(string $level, string $levelTitle): array
    {
        $hasAdminGallery = GaleriaImagen::query()
            ->where('nivel', $level)
            ->exists();

        if ($hasAdminGallery) {
            return GaleriaImagen::query()
                ->where('nivel', $level)
                ->where('activo', true)
                ->orderBy('orden')
                ->orderBy('id')
                ->get()
                ->map(function (GaleriaImagen $image) use ($levelTitle): array {
                    $url = $this->media->publicUploadUrl($image->imagen)
                        ?? $this->media->urlIfExists($image->imagen_media_path);

                    return [
                        'alt' => $image->texto_alternativo ?: $image->titulo ?: $levelTitle,
                        'titulo' => $image->titulo ?: $levelTitle,
                        'url' => $url,
                    ];
                })
                ->filter(fn (array $image): bool => ! empty($image['url']))
                ->values()
                ->all();
        }

        $directory = config("colegio.niveles.carpetas_galeria.{$level}");

        if (! $directory) {
            return [];
        }

        return $this->media->files($directory)
            ->filter(fn (string $file): bool => in_array(
                strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                config('colegio.media.image_extensions', []),
                true,
            ))
            ->sortByDesc(fn (string $file): bool => $level === 'secundaria' && basename($file) === 'Colegio Discovery-59.jpg')
            ->take(12)
            ->map(fn (string $file): array => [
                'alt' => $levelTitle,
                'titulo' => $levelTitle,
                'url' => $this->media->url($file),
            ])
            ->values()
            ->all();
    }
}
