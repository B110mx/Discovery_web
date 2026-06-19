<?php

namespace App\Filament\Resources\VideosPromocionales\Tables;

use App\Filament\Resources\VideosPromocionales\Schemas\VideoPromocionalForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VideosPromocionalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')->label('Video')->searchable(),
                TextColumn::make('nivel')
                    ->label('Página')
                    ->formatStateUsing(fn (string $state): string => VideoPromocionalForm::levelOptions()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('video_media_path')->label('/videosyfotos')->limit(45)->toggleable(),
                TextColumn::make('orden')->label('Orden')->sortable(),
                IconColumn::make('activo')->label('Visible')->boolean(),
            ])
            ->filters([
                SelectFilter::make('nivel')->label('Página')->options(VideoPromocionalForm::levelOptions()),
            ])
            ->defaultSort('orden')
            ->reorderable('orden')
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
