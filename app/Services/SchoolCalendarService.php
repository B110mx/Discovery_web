<?php

namespace App\Services;

use App\Models\Evento;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class SchoolCalendarService
{
    public function month(?string $requestedMonth): array
    {
        $month = $this->parseMonth($requestedMonth);
        $monthEnd = $month->endOfMonth();
        $events = Evento::query()
            ->where('activo', true)
            ->whereNotNull('fecha_evento')
            ->whereBetween('fecha_evento', [$month->toDateString(), $monthEnd->toDateString()])
            ->orderBy('fecha_evento')
            ->orderBy('orden')
            ->get()
            ->groupBy(fn (Evento $event) => $event->fecha_evento->toDateString());

        $days = collect();
        $cursor = $month->startOfWeek(CarbonInterface::MONDAY);
        $gridEnd = $monthEnd->endOfWeek(CarbonInterface::SUNDAY);

        while ($cursor->lte($gridEnd)) {
            $date = $cursor->toDateString();

            $days->push([
                'date' => $date,
                'number' => $cursor->day,
                'in_month' => $cursor->month === $month->month,
                'is_today' => $cursor->isToday(),
                'events' => $this->presentEvents($events->get($date, collect())),
            ]);

            $cursor = $cursor->addDay();
        }

        return [
            'title' => ucfirst($month->locale('es')->translatedFormat('F Y')),
            'month' => $month->format('Y-m'),
            'previous' => $month->subMonth()->format('Y-m'),
            'next' => $month->addMonth()->format('Y-m'),
            'weeks' => $days->chunk(7)->values()->all(),
        ];
    }

    public function upcoming(int $limit = 4): array
    {
        return $this->presentEvents(
            Evento::query()
                ->where('activo', true)
                ->whereNotNull('fecha_evento')
                ->whereDate('fecha_evento', '>=', today())
                ->orderBy('fecha_evento')
                ->orderBy('orden')
                ->limit($limit)
                ->get(),
        );
    }

    private function parseMonth(?string $requestedMonth): CarbonImmutable
    {
        if ($requestedMonth && preg_match('/^\d{4}-\d{2}$/', $requestedMonth)) {
            try {
                $month = CarbonImmutable::createFromFormat('!Y-m', $requestedMonth);

                if ($month && $month->format('Y-m') === $requestedMonth) {
                    return $month->startOfMonth();
                }
            } catch (\Throwable) {
                // Invalid query values use the current month.
            }
        }

        return CarbonImmutable::today()->startOfMonth();
    }

    private function presentEvents(Collection $events): array
    {
        return $events
            ->map(fn (Evento $event) => [
                'title' => $event->titulo,
                'description' => $event->descripcion,
                'date' => $event->fecha_evento->toDateString(),
                'day' => $event->fecha_evento->day,
                'month_short' => ucfirst($event->fecha_evento->locale('es')->translatedFormat('M')),
                'date_label' => $event->fecha_evento->locale('es')->translatedFormat('j \d\e F \d\e Y'),
                'level' => $event->nivel ?: 'general',
                'level_label' => Evento::levelOptions()[$event->nivel ?: 'general'] ?? 'Toda la comunidad',
            ])
            ->values()
            ->all();
    }
}
