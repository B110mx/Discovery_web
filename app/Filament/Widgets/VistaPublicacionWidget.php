<?php

namespace App\Filament\Widgets;

use App\Models\VistaPublicacion;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

/**
 * Interruptores del dashboard para publicar o poner en mantenimiento cada vista.
 */
class VistaPublicacionWidget extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.vista-publicacion-widget';

    protected int|string|array $columnSpan = 'full';

    public function alternar(string $clave): void
    {
        // Solo se aceptan claves declaradas; evita crear estados huérfanos por
        // una llamada Livewire manipulada.
        abort_unless(array_key_exists($clave, config('publicacion.vistas', [])), 404);

        $vista = VistaPublicacion::query()->firstOrCreate(
            ['clave' => $clave],
            ['publicada' => true],
        );

        $vista->update([
            'publicada' => ! $vista->publicada,
            'actualizada_por' => auth()->id(),
        ]);

        $notificacion = Notification::make()
            ->title($vista->publicada ? 'Vista publicada' : 'Vista en mantenimiento')
            ->body(config("publicacion.vistas.{$clave}.nombre"));

        ($vista->publicada ? $notificacion->success() : $notificacion->warning())->send();
    }

    protected function getViewData(): array
    {
        $estados = VistaPublicacion::query()
            ->with('actualizadaPor:id,name')
            ->get()
            ->keyBy('clave');

        $grupos = collect(config('publicacion.vistas', []))
            ->map(function (array $configuracion, string $clave) use ($estados): array {
                return [
                    'clave' => $clave,
                    'nombre' => $configuracion['nombre'],
                    'publicada' => (bool) ($estados->get($clave)?->publicada ?? true),
                    'actualizada_por' => $estados->get($clave)?->actualizadaPor?->name,
                    'actualizada_en' => $estados->get($clave)?->updated_at,
                ];
            })
            ->groupBy(fn (array $vista) => config("publicacion.vistas.{$vista['clave']}.grupo", 'Otras vistas'));

        return compact('grupos');
    }
}
