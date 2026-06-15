<?php

namespace App\Filament\Resources\BannersInicio\Tables;

use App\Models\BannerInicio;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class BannersInicioTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview')
                    ->label('Banner')
                    ->getStateUsing(fn (BannerInicio $record): ?string => self::previewUrl($record))
                    ->height(64)
                    ->width(140),
                TextColumn::make('titulo')
                    ->label('Título')
                    ->default('Sin título')
                    ->searchable(),
                TextColumn::make('texto_alternativo')
                    ->label('Descripción')
                    ->limit(55)
                    ->toggleable(),
                TextColumn::make('enlace')
                    ->label('Enlace')
                    ->limit(35)
                    ->toggleable(),
                TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
                IconColumn::make('activo')
                    ->label('Visible')
                    ->boolean(),
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

    private static function previewUrl(BannerInicio $record): ?string
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
