<?php

namespace App\Filament\Resources\Pedidos\Tables;

use App\Models\Pedido;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PedidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alumno_nombre')
                    ->label('Alumno')
                    ->searchable(),
                TextColumn::make('alumno_nivel')
                    ->label('Nivel')
                    ->searchable(),
                TextColumn::make('padre_nombre')
                    ->label('Padre o tutor')
                    ->searchable(),
                TextColumn::make('padre_telefono')
                    ->label('Telefono')
                    ->searchable(),
                TextColumn::make('total')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'completado' ? 'success' : 'warning')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options(Pedido::estadosDisponibles()),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
