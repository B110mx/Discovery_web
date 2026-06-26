<?php

namespace App\Services;

use App\Models\SeccionImagen;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Resuelve archivos heredados de videosyfotos y cargas públicas de Filament.
 *
 * Las vistas no deben armar rutas de imágenes directamente. Este servicio
 * centraliza prioridad de fuentes, cache-busting y protección de rutas.
 */
class MediaResolver
{
    /**
     * Caché en memoria por request para no repetir lecturas del filesystem ni
     * consultas de imágenes administrables mientras se renderiza una vista.
     */
    private array $filesByDirectory = [];

    private array $existsByDiskAndPath = [];

    private array $lastModifiedByPath = [];

    private array $publicUploadUrlsByPath = [];

    private array $imageRecordsByView = [];

    public function diskName(): string
    {
        return config('colegio.media.disk', 'videosyfotos');
    }

    public function files(string $directory): Collection
    {
        // Una carpeta faltante no debe romper la página; la vista mostrará
        // placeholders o omitirá la galería según corresponda.
        $directory = $this->normalizePath($directory);

        if (array_key_exists($directory, $this->filesByDirectory)) {
            return collect($this->filesByDirectory[$directory]);
        }

        $disk = Storage::disk($this->diskName());

        if (! $disk->directoryExists($directory)) {
            $this->filesByDirectory[$directory] = [];

            return collect();
        }

        $files = $disk->files($directory);
        $this->filesByDirectory[$directory] = $files;

        return collect($files);
    }

    public function filesWithExtensions(string $directory, array $extensions): Collection
    {
        $extensions = collect($extensions)
            ->map(fn (string $extension): string => strtolower($extension))
            ->all();

        return $this->files($directory)
            ->filter(fn (string $path): bool => in_array(
                strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                $extensions,
                true,
            ));
    }

    public function imageFiles(string $directory): Collection
    {
        return $this->filesWithExtensions($directory, config('colegio.media.image_extensions', []));
    }

    public function videoFiles(string $directory): Collection
    {
        return $this->filesWithExtensions($directory, config('colegio.media.video_extensions', []));
    }

    public function url(string $relativePath): string
    {
        // /media/* pasa por PageController@serveMedia. Codificar por segmento
        // conserva nombres con espacios y acentos sin perder las diagonales.
        $relativePath = $this->normalizePath($relativePath);
        $url = '/media/'.collect(explode('/', $relativePath))
            ->map(fn (string $segment) => rawurlencode($segment))
            ->implode('/');
        if ($this->mediaExists($relativePath)) {
            // Cambiar el archivo sin cambiar el nombre invalida caché gracias
            // al timestamp de modificación.
            return $url.'?v='.$this->mediaLastModified($relativePath);
        }

        return $url;
    }

    public function urlIfExists(?string $relativePath): ?string
    {
        if (empty($relativePath)) {
            return null;
        }

        $relativePath = $this->normalizePath($relativePath);

        if (! $this->mediaExists($relativePath)) {
            return null;
        }

        return $this->url($relativePath);
    }

    public function publicUploadUrl(?string $path): ?string
    {
        // Filament guarda cargas en el disco public. Si la carga falta, se deja
        // que image()/images() pruebe el respaldo configurado.
        if (empty($path)) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $path), '/');

        if (array_key_exists($path, $this->publicUploadUrlsByPath)) {
            return $this->publicUploadUrlsByPath[$path];
        }

        if (! $this->exists('public', $path)) {
            return $this->publicUploadUrlsByPath[$path] = null;
        }

        return $this->publicUploadUrlsByPath[$path] = '/storage/'.collect(explode('/', $path))
            ->map(fn (string $segment) => rawurlencode($segment))
            ->implode('/');
    }

    public function uploadedOrMediaUrl(?string $uploadedPath, ?string $mediaPath): ?string
    {
        return $this->publicUploadUrl($uploadedPath)
            ?? $this->urlIfExists($mediaPath);
    }

    public function filePath(string $path): ?string
    {
        $path = $this->normalizePath($path);

        if ($path === '') {
            return null;
        }

        $disk = Storage::disk($this->diskName());

        return $this->mediaExists($path) ? $disk->path($path) : null;
    }

    public function normalizePath(string $path): string
    {
        // Acepta separadores de Windows, elimina segmentos vacíos y bloquea
        // traversal con ".." antes de consultar el disco.
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
        // Convierte defaults declarados con media_path al mismo contrato que
        // consume el componente <x-imagen-seccion>.
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
     *
     * Cada posición editable se identifica por vista + clave. Esas claves deben
     * coincidir con SeccionImagen para que el panel reemplace la imagen correcta.
     */
    public function images(string $view, array $defaults): array
    {
        $records = $this->imageRecordsForView($view);

        return collect($defaults)
            ->map(function (array $default, string $key) use ($records) {
                $record = $records->get($key);
                $image = $this->uploadedOrMediaUrl($record?->imagen, $record?->respaldo_media_path)
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

    private function imageRecordsForView(string $view): Collection
    {
        if (! isset($this->imageRecordsByView[$view])) {
            $this->imageRecordsByView[$view] = SeccionImagen::query()
                ->where('vista', $view)
                ->where('activo', true)
                ->get()
                ->keyBy('clave');
        }

        return $this->imageRecordsByView[$view];
    }

    private function mediaExists(string $path): bool
    {
        return $this->exists($this->diskName(), $path);
    }

    private function exists(string $diskName, string $path): bool
    {
        $key = "{$diskName}:{$path}";

        if (! array_key_exists($key, $this->existsByDiskAndPath)) {
            $this->existsByDiskAndPath[$key] = Storage::disk($diskName)->exists($path);
        }

        return $this->existsByDiskAndPath[$key];
    }

    private function mediaLastModified(string $path): int
    {
        if (! array_key_exists($path, $this->lastModifiedByPath)) {
            $this->lastModifiedByPath[$path] = Storage::disk($this->diskName())->lastModified($path);
        }

        return $this->lastModifiedByPath[$path];
    }
}
