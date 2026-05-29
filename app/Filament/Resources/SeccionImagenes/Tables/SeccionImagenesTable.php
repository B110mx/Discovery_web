<?php

namespace App\Filament\Resources\SeccionImagenes\Tables;

use App\Models\SeccionImagen;
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

class SeccionImagenesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview')
                    ->label('Vista')
                    ->getStateUsing(fn (SeccionImagen $record) => self::previewUrl($record))
                    ->height(56)
                    ->width(72),
                TextColumn::make('vista')
                    ->label('Vista / pagina')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'inicio' => 'Inicio',
                        'nosotros' => 'Conocenos / Nosotros',
                        'oferta-academica' => 'Oferta Educativa',
                        'protagonistas' => 'Comunidad / Protagonistas',
                        'preescolar' => 'Nivel - Preescolar',
                        'primaria' => 'Nivel - Primaria',
                        'secundaria' => 'Nivel - Secundaria',
                        'contacto' => 'Contacto',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clave')
                    ->label('Parte de la vista')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('titulo')
                    ->label('Referencia')
                    ->searchable(),
                TextColumn::make('respaldo_media_path')
                    ->label('/videosyfotos')
                    ->searchable()
                    ->limit(42),
                IconColumn::make('activo')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('vista')
                    ->label('Vista')
                    ->options([
                        'inicio' => 'Inicio',
                        'nosotros' => 'Nosotros',
                        'oferta-academica' => 'Oferta Educativa',
                        'protagonistas' => 'Comunidad / Protagonistas',
                        'preescolar' => 'Nivel - Preescolar',
                        'primaria' => 'Nivel - Primaria',
                        'secundaria' => 'Nivel - Secundaria',
                        'contacto' => 'Contacto',
                    ]),
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

    private static function previewUrl(SeccionImagen $record): ?string
    {
        if ($record->imagen && Storage::disk('public')->exists($record->imagen)) {
            return Storage::disk('public')->url($record->imagen);
        }

        if (! $record->respaldo_media_path) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $record->respaldo_media_path), '/');

        if (! Storage::disk(config('colegio.media.disk', 'videosyfotos'))->exists($path)) {
            return null;
        }

        return '/media/' . collect(explode('/', $path))
            ->map(fn (string $segment) => rawurlencode($segment))
            ->implode('/');
    }
}
