<?php

namespace App\Services;

use App\Models\NivelContenido;
use App\Support\SchoolGradeFormatter;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;

class LevelContentService
{
    /**
     * Devuelve la definición completa de cada página de nivel.
     *
     * Combina tres fuentes:
     * - config/colegio.php: estructura técnica, layouts, rutas de archivos y temas.
     * - database/data/nivel_contenidos.php o BD: textos editables desde el panel.
     * - archivos de idioma levels.php: traducciones cuando el sitio está en otro idioma.
     */
    public function definitions(): array
    {
        $technicalDefinitions = config('colegio.niveles.definiciones', []);

        return collect($technicalDefinitions)
            ->map(fn (array $definition, string $slug) => $this->mergeDefinition(
                $this->localizedDefinition($slug, $definition),
                $this->localizedContent($slug, $this->content()[$slug] ?? []),
            ))
            ->all();
    }

    /**
     * Devuelve las tarjetas de Oferta Educativa.
     *
     * Usa la misma fuente de contenido de los niveles para que título,
     * descripción, edad y puntos se mantengan sincronizados entre la tarjeta
     * de listado y la página detallada del nivel.
     */
    public function offerDefinitions(): array
    {
        return collect(config('colegio.oferta_academica', []))
            ->map(function (array $definition, string $slug) {
                $content = $this->localizedContent($slug, $this->content()[$slug] ?? []);

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

    /**
     * Textos administrables de niveles.
     *
     * Los defaults permiten que el sitio funcione recién migrado. Si existe un
     * registro en NivelContenido, el panel toma prioridad para ese slug.
     */
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
                        'pop_rutas_visibles' => $record->pop_rutas_visibles,
                    ] : $default;
                })
                ->all();
        });
    }

    /**
     * Traduce solo los textos de contenido. La estructura técnica se mantiene
     * estable para no duplicar rutas de archivos o reglas de layout por idioma.
     */
    private function localizedContent(string $slug, array $content): array
    {
        if (app()->getLocale() === 'es') {
            return $content;
        }

        $translated = trans("levels.{$slug}.content");

        return is_array($translated) ? [...$content, ...$translated] : $content;
    }

    /**
     * Traduce bloques anidados como informacion, rutas POP o avisos IB.
     * array_replace_recursive conserva claves técnicas no traducidas.
     */
    private function localizedDefinition(string $slug, array $definition): array
    {
        if (app()->getLocale() === 'es') {
            return $definition;
        }

        $translatedInformation = trans("levels.{$slug}.informacion");

        if (! is_array($translatedInformation)) {
            return $definition;
        }

        return [
            ...$definition,
            'informacion' => array_replace_recursive(
                $definition['informacion'] ?? [],
                $translatedInformation,
            ),
        ];
    }

    /**
     * Inyecta el contenido editable dentro de la definición técnica que usa la
     * vista pages/nivel.blade.php.
     */
    private function mergeDefinition(array $definition, array $content): array
    {
        $information = $definition['informacion'] ?? [];

        if (! empty($content['pop_rutas_visibles'] ?? null)) {
            $information['rutas_visibles'] = $content['pop_rutas_visibles'];
        }

        if (($definition['layout'] ?? null) === 'pop') {
            $information = $this->filterPopRoutes($information);
        }

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

    /**
     * Permite publicar una o ambas rutas preuniversitarias del POP desde
     * config/colegio.php sin tocar la vista Blade.
     */
    private function filterPopRoutes(array $information): array
    {
        $visibleRoutes = $information['rutas_visibles'] ?? ['data_science', 'diseno_3d'];

        if ($visibleRoutes === 'todas') {
            $visibleRoutes = ['data_science', 'diseno_3d'];
        }

        $visibleRoutes = collect((array) $visibleRoutes)
            ->map(fn (string $route): string => strtolower($route))
            ->all();

        $information['rutas'] = collect($information['rutas'] ?? [])
            ->filter(fn (array $route): bool => in_array($route['clave'] ?? '', $visibleRoutes, true))
            ->values()
            ->all();

        return $information;
    }
}
