<?php

namespace App\Filament\Widgets;

use App\Models\Contacto;
use App\Models\Evento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?int $sort = -1;

    protected function getStats(): array
    {
        return [
            Stat::make('Mensajes Recibidos', Contacto::count())
                ->description('Total de contactos web')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('success'),
            Stat::make('Eventos Activos', Evento::where('activo', true)->count())
                ->description('Eventos publicados')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
        ];
    }
}
