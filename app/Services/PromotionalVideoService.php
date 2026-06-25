<?php

namespace App\Services;

use App\Models\VideoPromocional;
use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class PromotionalVideoService
{
    public function __construct(private readonly MediaResolver $media) {}

    public function get(string $level): array
    {
        return Cache::remember(
            SiteCache::key("videos_promocionales.{$level}"),
            SiteCache::ttl(),
            fn () => $this->resolve(
                VideoPromocional::query()->where('nivel', $level),
            ),
        );
    }

    public function featured(): array
    {
        $kindergartenVideos = collect($this->get('preescolar'));
        $featured = $kindergartenVideos
            ->first(fn (array $video): bool => str($video['titulo'])->contains('Desarrollo de habilidades', true))
            ?? $kindergartenVideos->first();

        return $featured ? [$featured] : [];
    }

    public function getAll(): array
    {
        return Cache::remember(
            SiteCache::key('videos_promocionales.all'),
            SiteCache::ttl(),
            fn () => collect($this->resolve(VideoPromocional::query()))
                ->unique('url')
                ->values()
                ->all(),
        );
    }

    private function resolve(Builder $query): array
    {
        return $query
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('id')
            ->get()
            ->map(function (VideoPromocional $video): ?array {
                $url = $this->media->publicUploadUrl($video->video)
                    ?? $this->media->urlIfExists($video->video_media_path);

                if (! $url) {
                    return null;
                }

                return [
                    'titulo' => $video->titulo,
                    'url' => $url,
                    'portada' => $this->media->publicUploadUrl($video->portada)
                        ?? $this->media->urlIfExists($video->portada_media_path),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
