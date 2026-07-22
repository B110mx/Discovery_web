<?php

namespace App\Filament\Resources\SeccionImagenes\Tables;

use App\Models\SeccionImagen;
use App\Support\FilamentMediaPreview;
use App\Support\SiteImageViewOptions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
                    ->label('Vista / página')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'inicio' => 'Inicio',
                        'nosotros' => 'Conócenos / Nosotros',
                        'oferta-academica' => 'Oferta Educativa',
                        'preescolar' => 'Nivel - Kindergarten',
                        'primaria' => 'Nivel - Elementary',
                        'secundaria' => 'Nivel - Middle School',
                        'bachillerato' => 'Nivel - High School',
                        'ib-en-discovery' => 'Nivel - IB en Discovery®',
                        'pop-del-ib' => 'POP del IB',
                        'certificacion-de-ingles' => 'Certificación de Inglés',
                        'academias-vespertinas' => 'Academias Vespertinas',
                        'recursos-escolares' => 'Recursos escolares',
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
                    ->options(SiteImageViewOptions::all()),
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
        return FilamentMediaPreview::url($record->imagen, $record->respaldo_media_path);
    }
}
