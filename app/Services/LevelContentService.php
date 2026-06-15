<?php

namespace App\Services;

use App\Models\NivelContenido;
use App\Support\SchoolGradeFormatter;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;

class LevelContentService
{
    public function definitions(): array
    {
        $technicalDefinitions = config('colegio.niveles.definiciones', []);

        return collect($technicalDefinitions)
            ->map(fn (array $definition, string $slug) => $this->mergeDefinition(
                $definition,
                $this->content()[$slug] ?? [],
            ))
            ->all();
    }

    public function offerDefinitions(): array
    {
        return collect(config('colegio.oferta_academica', []))
            ->map(function (array $definition, string $slug) {
                $content = $this->content()[$slug] ?? [];

                return [
                    ...$definition,
                    'titulo' => $content['titulo'] ?? $slug,
                    'subtitulo' => $content['oferta_subtitulo'] ?? '',
                    'descripcion' => $content['oferta_descripcion'] ?? '',
                    'edad' => SchoolGradeFormatter::format($content['oferta_edad'] ?? ''),
                    'puntos' => collect($content['oferta_puntos'] ?? [])
                        ->map(fn (string $point) => SchoolGradeFormatter::format($point))
                        ->all(),
                ];
            })
            ->all();
    }

    private function content(): array
    {
        return Cache::remember(SiteCache::key('niveles_contenido'), SiteCache::ttl(), function () {
            $defaults = require database_path('data/nivel_contenidos.php');
            $records = NivelContenido::query()->get()->keyBy('slug');

            return collect($defaults)
                ->map(function (array $default, string $slug) use ($records) {
                    $record = $records->get($slug);

                    return $record ? [
                        'titulo' => $record->titulo,
                        'descripcion' => $record->descripcion,
                        'contenido_titulo' => $record->contenido_titulo,
                        'contenido_intro' => $record->contenido_intro,
                        'oferta_subtitulo' => $record->oferta_subtitulo,
                        'oferta_descripcion' => $record->oferta_descripcion,
                        'oferta_edad' => $record->oferta_edad,
                        'oferta_puntos' => $record->oferta_puntos,
                    ] : $default;
                })
                ->all();
        });
    }

    private function mergeDefinition(array $definition, array $content): array
    {
        $information = $definition['informacion'] ?? [];

        return [
            ...$definition,
            'titulo' => $content['titulo'] ?? '',
            'descripcion' => $content['descripcion'] ?? '',
            'informacion' => [
                ...$information,
                'titulo' => $content['contenido_titulo'] ?? '',
                'intro' => $content['contenido_intro'] ?? '',
            ],
        ];
    }
}
