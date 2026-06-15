<?php

namespace App\Filament\Resources\GaleriaImagenes\Tables;

use App\Filament\Resources\GaleriaImagenes\Schemas\GaleriaImagenForm;
use App\Models\GaleriaImagen;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

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
        if ($record->imagen && Storage::disk('public')->exists($record->imagen)) {
            return Storage::disk('public')->url($record->imagen);
        }

        if (! $record->imagen_media_path) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $record->imagen_media_path), '/');

        if (! Storage::disk(config('colegio.media.disk', 'videosyfotos'))->exists($path)) {
            return null;
        }

        return '/media/'.collect(explode('/', $path))
            ->map(fn (string $segment): string => rawurlencode($segment))
            ->implode('/');
    }
}
