<?php

namespace App\Filament\Resources\HitoHistorias\Schemas;

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
                TextEntry::make('imagen_url')
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
