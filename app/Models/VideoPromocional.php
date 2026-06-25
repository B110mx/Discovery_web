<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;

class VideoPromocional extends Model
{
    protected $table = 'video_promocionales';

    protected $fillable = [
        'nivel',
        'titulo',
        'video',
        'video_media_path',
        'portada',
        'portada_media_path',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (VideoPromocional $video): void {
            SiteCache::forget("videos_promocionales.{$video->nivel}");
            SiteCache::forget('videos_promocionales.all');

            $previousLevel = $video->getOriginal('nivel');

            if ($previousLevel && $previousLevel !== $video->nivel) {
                SiteCache::forget("videos_promocionales.{$previousLevel}");
            }
        });
        static::deleted(fn (VideoPromocional $video) => SiteCache::forgetMany([
            "videos_promocionales.{$video->nivel}",
            'videos_promocionales.all',
        ]));
    }
}
