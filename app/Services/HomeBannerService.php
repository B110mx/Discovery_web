<?php

namespace App\Services;

use App\Models\BannerInicio;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;

class HomeBannerService
{
    public function __construct(private readonly MediaResolver $media) {}

    public function get(): array
    {
        return Cache::remember(
            SiteCache::key('inicio_banners'),
            SiteCache::ttl(),
            fn (): array => $this->resolve(),
        );
    }

    private function resolve(): array
    {
        if (BannerInicio::query()->exists()) {
            return BannerInicio::query()
                ->where('activo', true)
                ->orderBy('orden')
                ->orderBy('id')
                ->get()
                ->map(function (BannerInicio $banner): array {
                    $url = $this->media->uploadedOrMediaUrl($banner->imagen, $banner->imagen_media_path);

                    return [
                        'url' => $url,
                        'titulo' => $banner->titulo ?: 'Banner de Inicio',
                        'alt' => $banner->texto_alternativo ?: $banner->titulo ?: 'Colegio Internacional Discovery®',
                        'enlace' => $this->safeLink($banner->enlace),
                        'referencia' => 'Banner administrado desde el panel.',
                        'pendiente' => empty($url),
                    ];
                })
                ->filter(fn (array $banner): bool => ! empty($banner['url']))
                ->values()
                ->all();
        }

        return $this->media->imageFiles('Banner de inicio')
            ->sortBy(fn (string $path): string => str_starts_with(
                strtolower(pathinfo($path, PATHINFO_FILENAME)),
                'banner de bienvenida',
            ) ? '000-'.$path : '100-'.$path)
            ->map(fn (string $path): array => [
                'url' => $this->media->url($path),
                'titulo' => pathinfo($path, PATHINFO_FILENAME),
                'alt' => pathinfo($path, PATHINFO_FILENAME),
                'enlace' => null,
                'referencia' => 'Banner de respaldo de la carpeta Banner de inicio.',
                'pendiente' => false,
            ])
            ->values()
            ->all();
    }

    private function safeLink(?string $link): ?string
    {
        if (empty($link)) {
            return null;
        }

        $link = trim($link);

        return str_starts_with($link, '/') || preg_match('/^https?:\/\//i', $link)
            ? $link
            : null;
    }
}
