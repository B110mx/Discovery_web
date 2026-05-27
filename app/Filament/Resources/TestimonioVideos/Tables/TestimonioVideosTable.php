<?php

namespace App\Filament\Resources\TestimonioVideos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TestimonioVideosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Video')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('video')
                    ->label('Video subido')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('video_media_path')
                    ->label('/videosyfotos')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
                IconColumn::make('activo')
                    ->label('Visible')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable(),
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
}
