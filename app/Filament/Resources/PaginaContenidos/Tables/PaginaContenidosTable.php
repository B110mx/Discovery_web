<?php

namespace App\Filament\Resources\PaginaContenidos\Tables;

use Filament\Actions\EditAction;
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
                    ->label('Etiqueta')
                    ->placeholder('Sin etiqueta')
                    ->searchable(),
                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable(),
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
