<?php

namespace App\Services;

use App\Models\HitoHistoria;

class HistoryTimelineService
{
    public function __construct(private readonly MediaResolver $media) {}

    public function get(): array
    {
        $defaults = $this->defaults();
        $records = HitoHistoria::query()->orderBy('orden')->get();

        if ($records->isEmpty()) {
            return $defaults;
        }

        $fallbacksByYear = collect($defaults)->keyBy('anio');

        return $records
            ->map(function (HitoHistoria $milestone) use ($fallbacksByYear) {
                $fallback = $fallbacksByYear->get($milestone->anio, ['imagenes' => []]);
                $images = $fallback['imagenes'] ?? [];

                if ($url = $this->media->uploadedOrMediaUrl($milestone->imagen_url, $milestone->imagen_media_path)) {
                    $images[0] = [
                        'url' => $url,
                        'titulo' => $milestone->titulo,
                        'referencia' => 'Imagen principal del hito '.$milestone->titulo.'.',
                        'pendiente' => false,
                    ];
                }

                if ($url = $this->media->uploadedOrMediaUrl($milestone->imagen_secundaria_url, $milestone->imagen_secundaria_media_path)) {
                    $images[1] = [
                        'url' => $url,
                        'titulo' => $milestone->titulo.' - Imagen secundaria',
                        'referencia' => 'Imagen secundaria del hito '.$milestone->titulo.'.',
                        'pendiente' => false,
                    ];
                }

                $content = app()->getLocale() === 'es'
                    ? ['titulo' => $milestone->titulo, 'texto' => $milestone->texto]
                    : [
                        'titulo' => $fallback['titulo'] ?? $milestone->titulo,
                        'texto' => $fallback['texto'] ?? $milestone->texto,
                    ];

                return [
                    'anio' => $milestone->anio,
                    'titulo' => $content['titulo'],
                    'texto' => $content['texto'],
                    'imagenes' => collect($images)->filter(fn (array $image) => ! empty($image['url']))->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    private function defaults(): array
    {
        $images = collect([
            'historia_2003' => ['titulo' => 'Nosotros - Historia 2003', 'referencia' => 'Imagen para el hito Discovery® Kindergarten en la línea del tiempo de Nosotros.'],
            'historia_2003_2' => ['titulo' => 'Nosotros - Historia 2003 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito Discovery® Kindergarten en la línea del tiempo de Nosotros.'],
            'historia_2005' => ['titulo' => 'Nosotros - Historia 2005', 'referencia' => 'Imagen para el hito Discovery® Elementary en la línea del tiempo de Nosotros.'],
            'historia_2005_2' => ['titulo' => 'Nosotros - Historia 2005 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito Discovery® Elementary en la línea del tiempo de Nosotros.'],
            'historia_2011' => ['titulo' => 'Nosotros - Historia 2011', 'referencia' => 'Imagen para el hito Discovery® Middle School en la línea del tiempo de Nosotros.'],
            'historia_2016' => ['titulo' => 'Nosotros - Historia 2016', 'referencia' => 'Imagen para el hito Discovery® High School en la línea del tiempo de Nosotros.'],
            'historia_2018' => ['titulo' => 'Nosotros - Historia 2018', 'referencia' => 'Imagen para el hito Colegio del Mundo IB en la línea del tiempo de Nosotros.'],
            'historia_2019' => ['titulo' => 'Nosotros - Historia 2019', 'referencia' => 'Imagen para el hito Nuevas instalaciones en la línea del tiempo de Nosotros.'],
            'historia_2019_2' => ['titulo' => 'Nosotros - Historia 2019 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito Nuevas instalaciones en la línea del tiempo de Nosotros.'],
            'historia_2023' => ['titulo' => 'Nosotros - Historia 2023', 'referencia' => 'Imagen para el hito DKMUN primera edición en la línea del tiempo de Nosotros.'],
            'historia_2023_2' => ['titulo' => 'Nosotros - Historia 2023 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito DKMUN primera edición en la línea del tiempo de Nosotros.'],
            'historia_2025' => ['titulo' => 'Nosotros - Historia 2025', 'referencia' => 'Imagen para el hito Actualmente en la línea del tiempo de Nosotros.'],
        ])->map(fn (array $image) => [
            'url' => null,
            'titulo' => $image['titulo'],
            'referencia' => $image['referencia'],
            'pendiente' => true,
        ])->all();

        return collect(__('site.pages.about.history_milestones'))
            ->map(fn (array $milestone): array => [
                ...$milestone,
                'imagenes' => collect($milestone['imagenes'])
                    ->map(fn (string $key): array => $images[$key])
                    ->all(),
            ])
            ->all();
    }
}
