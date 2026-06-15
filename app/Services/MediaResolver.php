<?php

namespace App\Services;

use App\Models\SeccionImagen;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Resuelve archivos heredados de videosyfotos y cargas públicas de Filament.
 */
class MediaResolver
{
    public function diskName(): string
    {
        return config('colegio.media.disk', 'videosyfotos');
    }

    public function files(string $directory): Collection
    {
        $disk = Storage::disk($this->diskName());

        if (! $disk->directoryExists($directory)) {
            return collect();
        }

        return collect($disk->files($directory));
    }

    public function url(string $relativePath): string
    {
        $relativePath = $this->normalizePath($relativePath);
        $url = '/media/'.collect(explode('/', $relativePath))
            ->map(fn (string $segment) => rawurlencode($segment))
            ->implode('/');
        $disk = Storage::disk($this->diskName());

        if ($disk->exists($relativePath)) {
            return $url.'?v='.$disk->lastModified($relativePath);
        }

        return $url;
    }

    public function urlIfExists(?string $relativePath): ?string
    {
        if (empty($relativePath)) {
            return null;
        }

        $relativePath = $this->normalizePath($relativePath);

        if (! Storage::disk($this->diskName())->exists($relativePath)) {
            return null;
        }

        return $this->url($relativePath);
    }

    public function publicUploadUrl(?string $path): ?string
    {
        if (empty($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return '/storage/'.collect(explode('/', trim(str_replace('\\', '/', $path), '/')))
            ->map(fn (string $segment) => rawurlencode($segment))
            ->implode('/');
    }

    public function filePath(string $path): ?string
    {
        $path = $this->normalizePath($path);

        if ($path === '') {
            return null;
        }

        $disk = Storage::disk($this->diskName());

        return $disk->exists($path) ? $disk->path($path) : null;
    }

    public function normalizePath(string $path): string
    {
        $path = trim(str_replace('\\', '/', $path), '/');
        $segments = array_filter(
            explode('/', $path),
            fn (string $segment) => $segment !== '' && $segment !== '.',
        );

        abort_if(collect($segments)->contains('..'), 404);

        return implode('/', $segments);
    }

    public function defaultWithUrl(array $default): array
    {
        if (isset($default['media_path'])) {
            $default['url'] = $this->urlIfExists($default['media_path']);
        }

        return $default;
    }

    public function image(string $view, string $key, array $default): array
    {
        return $this->images($view, [$key => $default])[$key];
    }

    /**
     * Prioridad: carga pública, respaldo administrativo, URL predeterminada y
     * archivo predeterminado de videosyfotos.
     */
    public function images(string $view, array $defaults): array
    {
        $records = SeccionImagen::query()
            ->where('vista', $view)
            ->where('activo', true)
            ->whereIn('clave', array_keys($defaults))
            ->get()
            ->keyBy('clave');

        return collect($defaults)
            ->map(function (array $default, string $key) use ($records) {
                $record = $records->get($key);
                $image = $this->publicUploadUrl($record?->imagen)
                    ?? $this->urlIfExists($record?->respaldo_media_path)
                    ?? ($default['url'] ?? null)
                    ?? $this->urlIfExists($default['media_path'] ?? null);

                return [
                    'url' => $image,
                    'titulo' => $record?->titulo ?? $default['titulo'] ?? $key,
                    'referencia' => $record?->referencia ?? $default['referencia'] ?? null,
                    'pendiente' => empty($image),
                ];
            })
            ->all();
    }
}
