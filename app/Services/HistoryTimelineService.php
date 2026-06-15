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

                if ($url = $this->media->publicUploadUrl($milestone->imagen_url) ?? $this->media->urlIfExists($milestone->imagen_media_path)) {
                    $images[0] = [
                        'url' => $url,
                        'titulo' => $milestone->titulo,
                        'referencia' => 'Imagen principal del hito '.$milestone->titulo.'.',
                        'pendiente' => false,
                    ];
                }

                if ($url = $this->media->publicUploadUrl($milestone->imagen_secundaria_url) ?? $this->media->urlIfExists($milestone->imagen_secundaria_media_path)) {
                    $images[1] = [
                        'url' => $url,
                        'titulo' => $milestone->titulo.' - Imagen secundaria',
                        'referencia' => 'Imagen secundaria del hito '.$milestone->titulo.'.',
                        'pendiente' => false,
                    ];
                }

                return [
                    'anio' => $milestone->anio,
                    'titulo' => $milestone->titulo,
                    'texto' => $milestone->texto,
                    'imagenes' => collect($images)->filter(fn (array $image) => ! empty($image['url']))->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    private function defaults(): array
    {
        $images = collect([
            'historia_2003' => ['titulo' => 'Nosotros - Historia 2003', 'referencia' => 'Imagen para el hito Discovery® Kinder en la línea del tiempo de Nosotros.'],
            'historia_2003_2' => ['titulo' => 'Nosotros - Historia 2003 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito Discovery® Kinder en la línea del tiempo de Nosotros.'],
            'historia_2005' => ['titulo' => 'Nosotros - Historia 2005', 'referencia' => 'Imagen para el hito Discovery® Elementary en la línea del tiempo de Nosotros.'],
            'historia_2005_2' => ['titulo' => 'Nosotros - Historia 2005 - Imagen secundaria', 'referencia' => 'Imagen secundaria para el hito Discovery® Elementary en la línea del tiempo de Nosotros.'],
            'historia_2011' => ['titulo' => 'Nosotros - Historia 2011', 'referencia' => 'Imagen para el hito Discovery® Middle en la línea del tiempo de Nosotros.'],
            'historia_2016' => ['titulo' => 'Nosotros - Historia 2016', 'referencia' => 'Imagen para el hito Discovery® High en la línea del tiempo de Nosotros.'],
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

        return [
            ['anio' => '2003', 'titulo' => 'Discovery® Kinder', 'texto' => 'Nace Discovery® Kinder, el inicio de un sueño educativo porque los primeros pasos trascienden.', 'imagenes' => [$images['historia_2003'], $images['historia_2003_2']]],
            ['anio' => '2005', 'titulo' => 'Discovery® Elementary', 'texto' => 'Inauguración de Discovery® Elementary, creciendo con pasos firmes.', 'imagenes' => [$images['historia_2005'], $images['historia_2005_2']]],
            ['anio' => '2011', 'titulo' => 'Discovery® Middle', 'texto' => 'Se suma Discovery® Middle, ampliando horizontes.', 'imagenes' => [$images['historia_2011']]],
            ['anio' => '2016', 'titulo' => 'Discovery® High', 'texto' => 'Llega Discovery® High, preparando grandes Explorers y descubriendo su potencial.', 'imagenes' => [$images['historia_2016']]],
            ['anio' => '2018', 'titulo' => 'Colegio del Mundo', 'texto' => 'Nos convertimos en Colegio del Mundo IB, abrazando la educación internacional.', 'imagenes' => [$images['historia_2018']]],
            ['anio' => '2019', 'titulo' => 'Nuevas instalaciones', 'texto' => 'Estrenamos nuevas instalaciones para seguir innovando.', 'imagenes' => [$images['historia_2019'], $images['historia_2019_2']]],
            ['anio' => '2023', 'titulo' => 'DKMUN primera edición', 'texto' => 'Realizamos nuestra primera edición DKMUN, un espacio para el debate y la diplomacia.', 'imagenes' => [$images['historia_2023'], $images['historia_2023_2']]],
            ['anio' => '2025', 'titulo' => 'Actualmente', 'texto' => 'Seguimos escribiendo nuestra historia, creciendo y evolucionando juntos.', 'imagenes' => [$images['historia_2025']]],
        ];
    }
}
