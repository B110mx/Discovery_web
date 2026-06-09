<?php

namespace App\Filament\Resources\HitoHistorias\Tables;

use App\Models\HitoHistoria;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

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
                ImageColumn::make('imagen_principal_preview')
                    ->label('Imagen principal')
                    ->getStateUsing(fn (HitoHistoria $record) => self::previewUrl($record->imagen_url, $record->imagen_media_path)),
                ImageColumn::make('imagen_secundaria_preview')
                    ->label('Imagen secundaria')
                    ->getStateUsing(fn (HitoHistoria $record) => self::previewUrl($record->imagen_secundaria_url, $record->imagen_secundaria_media_path)),
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
            ->filters([])
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

    private static function previewUrl(?string $uploadPath, ?string $mediaPath): ?string
    {
        if ($uploadPath && Storage::disk('public')->exists($uploadPath)) {
            return Storage::disk('public')->url($uploadPath);
        }

        if (! $mediaPath) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $mediaPath), '/');

        if (! Storage::disk(config('colegio.media.disk', 'videosyfotos'))->exists($path)) {
            return null;
        }

        return '/media/' . collect(explode('/', $path))
            ->map(fn (string $segment) => rawurlencode($segment))
            ->implode('/');
    }
}
