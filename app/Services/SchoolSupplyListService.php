<?php

namespace App\Services;

use App\Models\ListaUtil;
use App\Support\SchoolGradeFormatter;

class SchoolSupplyListService
{
    public function __construct(private readonly MediaResolver $media) {}

    public function get(): array
    {
        $adminLists = $this->fromAdmin();

        return ! empty($adminLists) ? $adminLists : $this->fromMediaDirectory();
    }

    private function fromAdmin(): array
    {
        return ListaUtil::query()
            ->where('activo', true)
            ->orderByDesc('ciclo_escolar')
            ->orderBy('nivel')
            ->orderBy('orden')
            ->orderBy('grado')
            ->get()
            ->map(fn (ListaUtil $list) => [
                'grado' => $this->formatGrade($list->grado),
                'nivel' => $this->levelName($list->nivel),
                'titulo' => $this->normalizeDegreeSymbol($list->titulo),
                'ciclo' => $list->ciclo_escolar,
                'url' => $this->media->publicUploadUrl($list->archivo_pdf),
            ])
            ->filter(fn (array $list) => ! empty($list['url']))
            ->groupBy('nivel')
            ->sortKeysUsing(fn (string $a, string $b) => $this->levelOrder($a) <=> $this->levelOrder($b))
            ->map(fn ($lists) => $lists->values()->all())
            ->all();
    }

    private function fromMediaDirectory(): array
    {
        return $this->media->files('Listas de útiles')
            ->filter(fn (string $path) => strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf')
            ->map(fn (string $file) => [
                'grado' => $this->gradeFromFilename(basename($file)),
                'nivel' => $this->levelFromFilename(basename($file)),
                'titulo' => $this->normalizeDegreeSymbol(pathinfo($file, PATHINFO_FILENAME)),
                'url' => $this->media->url($file),
            ])
            ->sortBy(fn (array $list) => $this->gradeOrder($list['grado']))
            ->groupBy('nivel')
            ->sortKeysUsing(fn (string $a, string $b) => $this->levelOrder($a) <=> $this->levelOrder($b))
            ->map(fn ($lists) => $lists->values()->all())
            ->all();
    }

    private function levelOrder(string $level): int
    {
        return [
            'Kinder' => 10,
            'Preescolar' => 10,
            'Elementary' => 20,
            'Primaria' => 20,
            'Middle' => 30,
            'Secundaria' => 30,
            'High' => 40,
            'Bachillerato' => 40,
            'General' => 50,
        ][$level] ?? 999;
    }

    private function levelName(string $level): string
    {
        return [
            'Preescolar' => 'Kinder',
            'Primaria' => 'Elementary',
            'Secundaria' => 'Middle',
            'Bachillerato' => 'High',
        ][$level] ?? $level;
    }

    private function gradeFromFilename(string $filename): string
    {
        if (preg_match('/(\d{1,2})\s*[º°]/u', $filename, $matches)) {
            return $matches[1].'° grado';
        }

        return pathinfo($filename, PATHINFO_FILENAME);
    }

    private function levelFromFilename(string $filename): string
    {
        if (preg_match('/\b(1|2|3|4|5|6)\s*[º°]/u', $filename)) {
            return 'Elementary';
        }

        if (preg_match('/\b(7|8|9)\s*[º°]/u', $filename)) {
            return 'Middle';
        }

        if (preg_match('/\b(10|11|12)\s*[º°]/u', $filename)) {
            return 'High';
        }

        return 'General';
    }

    private function gradeOrder(string $grade): int
    {
        if (preg_match('/\d+/', $grade, $matches)) {
            return (int) $matches[0];
        }

        return 999;
    }

    private function formatGrade(string $grade): string
    {
        return SchoolGradeFormatter::format($grade);
    }

    private function normalizeDegreeSymbol(string $text): string
    {
        return preg_replace('/(?<=\d)[º°]/u', '°', $text) ?? $text;
    }
}
