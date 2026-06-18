<?php

namespace App\Filament\Resources\ListaUtiles\Tables;

use App\Models\ListaUtil;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ListaUtilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Lista visible')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ciclo_escolar')
                    ->label('Ciclo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nivel')
                    ->label('Nivel')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('grado')
                    ->label('Grado')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('archivo_pdf')
                    ->label('PDF')
                    ->limit(32)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
                IconColumn::make('activo')
                    ->label('Visible')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Actualizada')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('ciclo_escolar')
                    ->label('Ciclo escolar')
                    ->options(fn () => ListaUtil::query()
                        ->distinct()
                        ->orderByDesc('ciclo_escolar')
                        ->pluck('ciclo_escolar', 'ciclo_escolar')
                        ->all()),
                SelectFilter::make('nivel')
                    ->options([
                        'Kinder' => 'Kindergarten',
                        'Elementary' => 'Elementary',
                        'Middle' => 'Middle School',
                        'High' => 'High School',
                        'General' => 'General',
                    ]),
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
}
