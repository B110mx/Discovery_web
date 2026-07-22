<?php

namespace App\Filament\Resources\GaleriaImagenes\Tables;

use App\Filament\Resources\GaleriaImagenes\Schemas\GaleriaImagenForm;
use App\Models\GaleriaImagen;
use App\Support\FilamentMediaPreview;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GaleriaImagenesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview')
                    ->label('Imagen')
                    ->getStateUsing(fn (GaleriaImagen $record): ?string => self::previewUrl($record))
                    ->height(64)
                    ->width(86),
                TextColumn::make('titulo')
                    ->label('Título')
                    ->default('Sin título')
                    ->searchable(),
                TextColumn::make('nivel')
                    ->label('Galería')
                    ->formatStateUsing(fn (string $state): string => GaleriaImagenForm::levelOptions()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('texto_alternativo')
                    ->label('Descripción')
                    ->limit(55)
                    ->toggleable(),
                TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
                IconColumn::make('activo')
                    ->label('Visible')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('nivel')
                    ->label('Nivel')
                    ->options(GaleriaImagenForm::levelOptions()),
            ])
            ->defaultSort('orden')
            ->reorderable('orden')
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

    private static function previewUrl(GaleriaImagen $record): ?string
    {
        return FilamentMediaPreview::url($record->imagen, $record->imagen_media_path);
    }
}
