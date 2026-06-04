<?php

namespace App\Filament\Resources\HitoHistorias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HitoHistoriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('anio')
                    ->label('Año')
                    ->searchable(),
                TextColumn::make('titulo')
                    ->label('Hito')
                    ->searchable(),
                TextColumn::make('texto')
                    ->label('Descripción')
                    ->limit(70)
                    ->searchable(),
                ImageColumn::make('imagen_url')
                    ->label('Imagen principal')
                    ->disk('public'),
                ImageColumn::make('imagen_secundaria_url')
                    ->label('Imagen secundaria')
                    ->disk('public'),
                TextColumn::make('orden')
                    ->label('Orden')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('orden')
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
