<?php

namespace App\Filament\Resources\PaginaContenidos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaginaContenidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')
                    ->label('Página')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'inicio' => 'Inicio',
                        'nosotros' => 'Conócenos / Nosotros',
                        'oferta-academica' => 'Oferta Educativa',
                        'protagonistas' => 'Comunidad',
                        'contacto' => 'Contacto',
                        default => $state,
                    })
                    ->searchable(),
                TextColumn::make('titulo')
                    ->label('Título principal')
                    ->searchable(),
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(70)
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
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
