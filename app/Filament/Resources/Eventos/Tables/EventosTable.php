<?php

namespace App\Filament\Resources\Eventos\Tables;

use App\Models\Evento;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class EventosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Evento visible en inicio')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('preview')
                    ->label('Imagen')
                    ->getStateUsing(fn (Evento $record) => self::previewUrl($record))
                    ->height(56)
                    ->width(72),
                TextColumn::make('descripcion')
                    ->label('Descripcion')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('imagen_media_path')
                    ->label('/videosyfotos')
                    ->limit(36)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
                IconColumn::make('activo')
                    ->label('Visible')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime('Y-m-d H:i')
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

    private static function previewUrl(Evento $record): ?string
    {
        if ($record->imagen_url && Storage::disk('public')->exists($record->imagen_url)) {
            return Storage::disk('public')->url($record->imagen_url);
        }

        if (! $record->imagen_media_path) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $record->imagen_media_path), '/');

        if (! Storage::disk(config('colegio.media.disk', 'videosyfotos'))->exists($path)) {
            return null;
        }

        return '/media/' . collect(explode('/', $path))
            ->map(fn (string $segment) => rawurlencode($segment))
            ->implode('/');
    }
}
