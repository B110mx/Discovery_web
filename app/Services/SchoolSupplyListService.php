<?php

namespace App\Services;

use App\Models\ListaUtil;
use App\Support\SchoolGradeFormatter;
use Illuminate\Support\Collection;

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
                'titulo' => $this->listTitle($list->titulo, $list->grado),
                'ciclo' => $list->ciclo_escolar,
                'url' => $this->media->publicUploadUrl($list->archivo_pdf),
            ])
            ->filter(fn (array $list) => ! empty($list['url']))
            ->pipe(fn ($lists) => $this->groupByLevel($lists));
    }

    private function fromMediaDirectory(): array
    {
        return $this->media->filesWithExtensions('Listas de útiles', ['pdf'])
            ->map(fn (string $file) => [
                'grado' => $this->formatGrade($this->gradeFromFilename(basename($file))),
                'nivel' => $this->levelFromFilename(basename($file)),
                'titulo' => $this->listTitle(pathinfo($file, PATHINFO_FILENAME), $this->gradeFromFilename(basename($file))),
                'url' => $this->media->url($file),
            ])
            ->sortBy(fn (array $list) => $this->gradeOrder($list['grado']))
            ->pipe(fn ($lists) => $this->groupByLevel($lists));
    }

    private function groupByLevel(Collection $lists): array
    {
        return $lists
            ->groupBy('nivel')
            ->sortKeysUsing(fn (string $a, string $b) => $this->levelOrder($a) <=> $this->levelOrder($b))
            ->map(fn ($lists) => $lists->values()->all())
            ->all();
    }

    private function levelOrder(string $level): int
    {
        return [
            'Kinder' => 10,
            'Kindergarten' => 10,
            'Preescolar' => 10,
            'Elementary' => 20,
            'Primaria' => 20,
            'Middle' => 30,
            'Middle School' => 30,
            'Secundaria' => 30,
            'High' => 40,
            'High School' => 40,
            'Bachillerato' => 40,
            'General' => 50,
        ][$level] ?? 999;
    }

    private function levelName(string $level): string
    {
        return [
            'Kinder' => 'Kindergarten',
            'Preescolar' => 'Kindergarten',
            'Primaria' => 'Elementary',
            'Middle' => 'Middle School',
            'Secundaria' => 'Middle School',
            'High' => 'High School',
            'Bachillerato' => 'High School',
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
            return 'Middle School';
        }

        if (preg_match('/\b(10|11|12)\s*[º°]/u', $filename)) {
            return 'High School';
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
        $grade = SchoolGradeFormatter::format($grade);

        if (app()->getLocale() !== 'en') {
            return $grade;
        }

        return preg_replace_callback('/\b(\d{1,2})°(?:\s+grado)?\b/u', fn (array $matches): string => $this->ordinal((int) $matches[1]).' grade', $grade) ?? $grade;
    }

    private function normalizeDegreeSymbol(string $text): string
    {
        return preg_replace('/(?<=\d)[º°]/u', '°', $text) ?? $text;
    }

    private function listTitle(string $title, string $grade): string
    {
        if (app()->getLocale() === 'es') {
            return $this->normalizeDegreeSymbol($title);
        }

        return __('site.pages.resources.supply_list_title', ['grade' => $this->formatGrade($grade)]);
    }

    private function ordinal(int $number): string
    {
        if (in_array($number % 100, [11, 12, 13], true)) {
            return $number.'th';
        }

        return $number.match ($number % 10) {
            1 => 'st',
            2 => 'nd',
            3 => 'rd',
            default => 'th',
        };
    }
}
