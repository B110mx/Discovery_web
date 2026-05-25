<?php

namespace App\Filament\Resources\SeccionImagenes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeccionImagenesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('imagen')
                    ->label('Imagen')
                    ->disk('public'),
                TextColumn::make('vista')
                    ->label('Vista / pagina')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clave')
                    ->label('Parte de la vista')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('titulo')
                    ->label('Referencia')
                    ->searchable(),
                IconColumn::make('activo')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('vista')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
