<?php

namespace App\Filament\Resources\NivelContenidos\Tables;

use App\Models\NivelContenido;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NivelContenidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Nivel')
                    ->searchable(),
                TextColumn::make('descripcion')
                    ->label('Descripcion')
                    ->limit(70),
                TextColumn::make('oferta_edad')
                    ->label('Etapa'),
                TextColumn::make('pop_routes_summary')
                    ->label('Rutas POP')
                    ->state(fn (NivelContenido $record): ?string => self::popRoutesSummary($record))
                    ->badge(),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('id')
            ->recordActions([
                EditAction::make(),
            ]);
    }

    private static function popRoutesSummary(NivelContenido $record): ?string
    {
        if ($record->slug !== 'pop-del-ib') {
            return null;
        }

        $routes = $record->pop_rutas_visibles ?: config(
            'colegio.niveles.definiciones.pop-del-ib.informacion.rutas_visibles',
            ['data_science', 'diseno_3d'],
        );

        $labels = [
            'data_science' => 'Data Science',
            'diseno_3d' => 'Diseno 3D',
        ];

        return collect($routes)
            ->map(fn (string $route): ?string => $labels[$route] ?? null)
            ->filter()
            ->join(' + ');
    }
}
