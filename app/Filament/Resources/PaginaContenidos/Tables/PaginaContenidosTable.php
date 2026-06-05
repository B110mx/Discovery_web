<?php

namespace App\Filament\Resources\PaginaContenidos\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
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
                TextColumn::make('subtitulo')
                    ->label('Etiqueta / hero')
                    ->placeholder('Sin etiqueta')
                    ->searchable(),
                TextColumn::make('titulo')
                    ->label('Título principal')
                    ->searchable(),
                ImageColumn::make('imagen_principal')
                    ->label('Imagen principal')
                    ->disk('public')
                    ->square(),
                ImageColumn::make('imagen_secundaria')
                    ->label('Imagen secundaria')
                    ->disk('public')
                    ->square(),
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
            ]);
    }
}
