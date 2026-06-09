<?php

namespace App\Filament\Resources\HitoHistorias\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HitoHistoriaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('anio'),
                TextEntry::make('titulo'),
                TextEntry::make('texto')
                    ->columnSpanFull(),
                ImageEntry::make('imagen_url')
                    ->label('Imagen principal')
                    ->disk('public'),
                TextEntry::make('imagen_media_path')
                    ->label('Imagen principal de /videosyfotos')
                    ->placeholder('-'),
                ImageEntry::make('imagen_secundaria_url')
                    ->label('Imagen secundaria')
                    ->disk('public'),
                TextEntry::make('imagen_secundaria_media_path')
                    ->label('Imagen secundaria de /videosyfotos')
                    ->placeholder('-'),
                TextEntry::make('orden')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
